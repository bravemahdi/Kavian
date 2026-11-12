/**
 * search.js — سیستم جستجوی AJAX با dropdown پیشنهادات
 * کار می‌کند در همه صفحات سایت مشاهیر
 */

(function () {
    'use strict';

    const cfg = window.SEARCH_CONFIG || {
        IS_FA: false,
        LANG: 'en',
        PROFILE_URL: '/Kavian/mashahir/english/profile.php',
        SEARCH_API: '/Kavian/mashahir/api/search-celebrities.php',
        TEXT: { no_results: 'No results found', more: 'See all results', searching: 'Searching...' }
    };

    let debounceTimer = null;
    let currentQuery  = '';
    let activeIndex   = -1;
    let suggestions   = [];

    // ─── DOM ───────────────────────────────────────────────────────────────
    function getInput()    { return document.getElementById('searchInput'); }
    function getDropdown() { return document.getElementById('searchDropdown'); }

    // ─── Entry point — onkeyup در header ───────────────────────────────────
    window.handleSearchKeyup = function (e) {
        const input = getInput();
        if (!input) return;

        // ناوبری با کیبورد
        if (e.key === 'ArrowDown') { e.preventDefault(); moveActive(1);  return; }
        if (e.key === 'ArrowUp')   { e.preventDefault(); moveActive(-1); return; }
        if (e.key === 'Escape')    { closeDropdown(); return; }
        if (e.key === 'Enter') {
            e.preventDefault();
            if (activeIndex >= 0 && suggestions[activeIndex]) {
                selectSuggestion(suggestions[activeIndex]);
            } else {
                submitSearch(input.value.trim());
            }
            return;
        }

        const q = input.value.trim();

        // اگر در profile.php هستیم، filterCards موجود رو هم اجرا کن
        if (typeof window.filterCards === 'function') window.filterCards();

        clearTimeout(debounceTimer);

        if (q.length < 1) { closeDropdown(); return; }

        // نمایش حالت loading
        showLoading();

        debounceTimer = setTimeout(function () {
            fetchSuggestions(q);
        }, 280);
    };

    // ─── Fetch ──────────────────────────────────────────────────────────────
    function fetchSuggestions(q) {
        if (q === currentQuery) return;
        currentQuery = q;

        const url = cfg.SEARCH_API
            + '?q=' + encodeURIComponent(q)
            + '&lang=' + cfg.LANG;

        fetch(url)
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) renderDropdown(data.data, q);
                else closeDropdown();
            })
            .catch(function () { closeDropdown(); });
    }

    // ─── Render ─────────────────────────────────────────────────────────────
    function showLoading() {
        const dd = getDropdown();
        if (!dd) return;
        dd.innerHTML = '<div class="search-dd-loading"><span class="search-dd-spinner"></span>'
            + escHtml(cfg.TEXT.searching) + '</div>';
        openDropdown(dd);
    }

    function renderDropdown(results, q) {
        const dd = getDropdown();
        if (!dd) return;

        suggestions  = results;
        activeIndex  = -1;

        if (results.length === 0) {
            dd.innerHTML = '<div class="search-dd-empty">'
                + '<i class="fas fa-search"></i> '
                + escHtml(cfg.TEXT.no_results)
                + ' <strong>' + escHtml(q) + '</strong></div>';
            openDropdown(dd);
            return;
        }

        const items = results.map(function (item, i) {
            const img = item.img
                ? '<img src="' + escHtml(item.img) + '" alt="' + escHtml(item.name) + '" class="search-dd-img" onerror="this.style.display=\'none\'">'
                : '<div class="search-dd-img search-dd-img-placeholder"><i class="fas fa-user"></i></div>';

            const highlighted = highlight(item.name, q);

            return '<div class="search-dd-item" role="option" tabindex="-1"'
                + ' data-index="' + i + '"'
                + ' onclick="window._searchSelect(' + i + ')"'
                + ' onmouseenter="window._searchHover(' + i + ')">'
                + img
                + '<div class="search-dd-info">'
                + '<span class="search-dd-name">' + highlighted + '</span>'
                + (item.job ? '<span class="search-dd-job">' + escHtml(item.job) + '</span>' : '')
                + (item.birth ? '<span class="search-dd-birth">' + escHtml(item.birth) + '</span>' : '')
                + '</div>'
                + '</div>';
        }).join('');

        // ردیف "مشاهده همه نتایج"
        const seeAll = '<div class="search-dd-seeall" onclick="window._searchSubmit()">'
            + '<i class="fas fa-search"></i> '
            + escHtml(cfg.TEXT.more)
            + ' <strong>' + escHtml(q) + '</strong>'
            + '</div>';

        dd.innerHTML = items + seeAll;
        openDropdown(dd);
    }

    // ─── Actions ────────────────────────────────────────────────────────────
    window._searchSelect = function (i) { selectSuggestion(suggestions[i]); };
    window._searchHover  = function (i) { setActive(i); };
    window._searchSubmit = function ()  { submitSearch(currentQuery); };

    function selectSuggestion(item) {
        closeDropdown();
        if (!item) return;
        // رفتن مستقیم به پروفایل
        window.location.href = cfg.PROFILE_URL + '?id=' + item.id;
    }

    function submitSearch(q) {
        if (!q) return;
        closeDropdown();
        window.location.href = cfg.PROFILE_URL + '?search=' + encodeURIComponent(q);
    }

    // ─── Keyboard navigation ────────────────────────────────────────────────
    function moveActive(dir) {
        const total = suggestions.length;
        if (total === 0) return;
        activeIndex = (activeIndex + dir + total) % total;
        setActive(activeIndex);
    }

    function setActive(i) {
        activeIndex = i;
        const dd = getDropdown();
        if (!dd) return;
        dd.querySelectorAll('.search-dd-item').forEach(function (el, idx) {
            el.classList.toggle('search-dd-item--active', idx === i);
        });
    }

    // ─── Open / Close ───────────────────────────────────────────────────────
    function openDropdown(dd) {
        dd.style.display = 'block';
        const input = getInput();
        if (input) input.setAttribute('aria-expanded', 'true');
    }

    function closeDropdown() {
        currentQuery = '';
        activeIndex  = -1;
        suggestions  = [];
        const dd = getDropdown();
        if (dd) { dd.style.display = 'none'; dd.innerHTML = ''; }
        const input = getInput();
        if (input) input.setAttribute('aria-expanded', 'false');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────
    function escHtml(str) {
        return String(str || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function highlight(text, q) {
        if (!q) return escHtml(text);
        const escaped = q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        return escHtml(text).replace(
            new RegExp('(' + escaped + ')', 'gi'),
            '<mark class="search-dd-mark">$1</mark>'
        );
    }

    // ─── بستن با کلیک بیرون ─────────────────────────────────────────────────
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.search-box-header')) closeDropdown();
    });

    // ─── بستن با فوکوس خروج ─────────────────────────────────────────────────
    document.addEventListener('focusin', function (e) {
        if (!e.target.closest('.search-box-header')) closeDropdown();
    });

})();

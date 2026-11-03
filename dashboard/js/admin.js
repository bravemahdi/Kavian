
// ── helpers دسترسی — همه از window.ADMIN_PERMISSIONS ─────
function hasPermission(key) {
    const perms = window.ADMIN_PERMISSIONS || [];
    if (perms.includes('special:*')) return true;
    if (perms.includes(key)) return true;
    // بالا به پایین: section:celebrities → true اگه section:celebrities:X وجود داشت
    const prefix = key + ':';
    if (perms.some(p => p.startsWith(prefix))) return true;
    // پایین به بالا: section:X:Y → section:X
    const parts = key.split(':');
    if (parts.length >= 3) {
        const parent = parts[0] + ':' + parts[1];
        if (perms.includes(parent)) return true;
    }
    return false;
}

const ENTITY_DEFS = {
    products: {
        label: 'محصولات',
        listColumns: [
            { key: 'id', label: '#' },
            { key: 'image', label: 'تصویر', type: 'image' },
            { key: 'name', label: 'نام' },
            { key: 'price', label: 'قیمت' },
            { key: 'status', label: 'وضعیت', type: 'status' },
        ],
        formFields: [
            { key: 'name', label: 'نام محصول', type: 'text', required: true },
            { key: 'description', label: 'توضیحات', type: 'textarea' },
            { key: 'price', label: 'قیمت (تومان)', type: 'number' },
            { key: 'image', label: 'آدرس تصویر', type: 'text' },
            { key: 'status', label: 'وضعیت', type: 'select', options: [['active', 'فعال'], ['inactive', 'غیرفعال']] },
        ],
    },
    orders: {
        label: 'سفارشات',
        listColumns: [
            { key: 'id', label: '#' },
            { key: 'customer_name', label: 'نام مشتری' },
            { key: 'customer_phone', label: 'شماره تماس' },
            { key: 'quantity', label: 'تعداد' },
            { key: 'status', label: 'وضعیت', type: 'status' },
        ],
        formFields: [
            { key: 'customer_name', label: 'نام مشتری', type: 'text', required: true },
            { key: 'customer_phone', label: 'شماره تماس', type: 'text' },
            { key: 'product_id', label: 'شناسه محصول', type: 'number' },
            { key: 'quantity', label: 'تعداد', type: 'number' },
            { key: 'status', label: 'وضعیت', type: 'select', options: [['pending', 'در انتظار'], ['processing', 'در حال پردازش'], ['done', 'تکمیل‌شده'], ['cancelled', 'لغو‌شده']] },
        ],
    },
    comments: {
        label: 'کامنت‌ها',
        listColumns: [
            { key: 'id', label: '#' },
            { key: 'author_name', label: 'نویسنده' },
            { key: 'content', label: 'متن' },
            { key: 'status', label: 'وضعیت', type: 'status' },
        ],
        formFields: [
            { key: 'author_name', label: 'نام نویسنده', type: 'text', required: true },
            { key: 'content', label: 'متن کامنت', type: 'textarea', required: true },
            { key: 'product_id', label: 'شناسه محصول (اختیاری)', type: 'number' },
            { key: 'status', label: 'وضعیت', type: 'select', options: [['pending', 'در انتظار'], ['approved', 'تایید‌شده'], ['rejected', 'رد‌شده']] },
        ],
    },
    slides: {
        label: 'اسلایدها',
        listColumns: [
            { key: 'id', label: '#' },
            { key: 'image', label: 'تصویر', type: 'image' },
            { key: 'title', label: 'عنوان' },
            { key: 'sort_order', label: 'ترتیب' },
            { key: 'status', label: 'وضعیت', type: 'status' },
        ],
        formFields: [
            { key: 'title', label: 'عنوان اسلاید', type: 'text', required: true },
            { key: 'image', label: 'آدرس تصویر', type: 'text' },
            { key: 'link', label: 'لینک مقصد', type: 'text' },
            { key: 'sort_order', label: 'ترتیب نمایش', type: 'number' },
            { key: 'status', label: 'وضعیت', type: 'select', options: [['active', 'فعال'], ['inactive', 'غیرفعال']] },
        ],
    },
    celebrities: {
        label: 'موارد',
        listColumns: [
            { key: 'id', label: '#' },
            { key: 'image', label: 'تصویر', type: 'image' },
            { key: 'name', label: 'نام' },
            { key: 'status', label: 'وضعیت', type: 'status' },
        ],
        formFields: [
            { key: 'name', label: 'نام', type: 'text', required: true },
            { key: 'description', label: 'توضیحات', type: 'textarea' },
            { key: 'image', label: 'آدرس تصویر', type: 'text' },
            { key: 'status', label: 'وضعیت', type: 'select', options: [['active', 'فعال'], ['inactive', 'غیرفعال']] },
        ],
    },
};

let currentEntity = null;
let currentSection = null;
let currentSpecial = null;
let editingId = null;

const panelTitle = document.getElementById('panelTitle');
const panelBody = document.getElementById('panelBody');
const adminMessage = document.getElementById('adminMessage');
const modalOverlay = document.getElementById('adminModalOverlay');
const modalForm = document.getElementById('modalForm');
const modalTitle = document.getElementById('modalTitle');

document.querySelectorAll('.nav-group-toggle').forEach(btn => {
    btn.addEventListener('click', () => {
        btn.closest('.nav-group').classList.toggle('open');
    });
});

// toggle برای nav-subgroup (زیرمنوی تو در تو)
document.querySelectorAll('.nav-subgroup-toggle').forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        btn.closest('.nav-subgroup').classList.toggle('open');
    });
});
const adminSidebarEl = document.getElementById('adminSidebar');
const sidebarOverlayEl = document.getElementById('sidebarOverlay');

function openMobileSidebar() {
    adminSidebarEl.classList.add('sidebar-open');
    if (sidebarOverlayEl) sidebarOverlayEl.classList.add('show');
}

function closeMobileSidebar() {
    adminSidebarEl.classList.remove('sidebar-open');
    if (sidebarOverlayEl) sidebarOverlayEl.classList.remove('show');
}

document.getElementById('sidebarToggle').addEventListener('click', () => {
    adminSidebarEl.classList.contains('sidebar-open') ? closeMobileSidebar() : openMobileSidebar();
});

if (sidebarOverlayEl) {
    sidebarOverlayEl.addEventListener('click', closeMobileSidebar);
}

document.querySelectorAll('#adminSidebar .nav-link').forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        document.querySelectorAll('#adminSidebar .nav-link').forEach(l => l.classList.remove('active'));
        link.classList.add('active');

        if (window.innerWidth <= 860) {
            closeMobileSidebar();
        }

        const special = link.dataset.special;
        if (special === 'mashahir_categories') {
            currentSpecial = 'mashahir_categories';
            loadMashahirCategoriesPanel();
            return;
        }
        if (special === 'mashahir_celebrities') {
            currentSpecial = 'mashahir_celebrities';
            loadMashahirCelebritiesPanel();
            return;
        }
        if (special === 'mashahir_products') {
            currentSpecial = 'mashahir_products';
            loadMashahirProductsPanel();
            return;
        }
        if (special === 'mashahir_reviews') {
            currentSpecial = 'mashahir_reviews';
            loadMashahirReviewsPanel();
            return;
        }
        if (special === 'mashahir_product_register') {
            currentSpecial = 'mashahir_product_register';
            loadMashahirProductRegisterPanel();
            return;
        }
        if (special === 'users') {
            currentSpecial = 'users';
            loadUsersPanel();
            return;
        }
        if (special === 'logs') {
            currentSpecial = 'logs';
            loadLogsPanel();
            return;
        }
        if (special === 'subsites') {
            currentSpecial = 'subsites';
            loadSubsitesPanel();
            return;
        }
        if (special === 'roles') {
            currentSpecial = 'roles';
            loadRolesPanel();
            return;
        }
        if (special === 'wip') {
            currentSpecial = 'wip';
            loadWipPanel(link.textContent.trim());
            return;
        }

        currentSpecial = null;
        currentSection = link.dataset.section;
        currentEntity  = link.dataset.entity;

        // اگر لینک data-user-tab داره (پروفایل، سفارشات و ...) کاری نکن
        if (!currentSection || !currentEntity) return;

        loadEntityPanel(currentSection, currentEntity);
    });
});

function showMessage(text, type) {
    adminMessage.textContent = text;
    adminMessage.className = 'admin-message show ' + type;
    setTimeout(() => { adminMessage.className = 'admin-message'; }, 3500);
}

function statusBadge(value) {
    if (!value) return '-';
    const map = {
        active: 'فعال', inactive: 'غیرفعال',
        pending: 'در انتظار', approved: 'تایید‌شده', rejected: 'رد‌شده',
        processing: 'در حال پردازش', done: 'تکمیل‌شده', cancelled: 'لغو‌شده',
    };
    return `<span class="badge badge-${value}">${map[value] || value}</span>`;
}

function loadEntityPanel(section, entityKey) {
    const def = ENTITY_DEFS[entityKey];
    const sectionInfo = window.ADMIN_SECTIONS[section];
    const label = entityKey === 'celebrities' ? sectionInfo.sub5_label : (window.ADMIN_PANELS[entityKey] || def.label);

    panelTitle.textContent = `${sectionInfo.title} — ${label}`;
    panelBody.innerHTML = `
        <div class="panel-toolbar">
            <h2>${label}</h2>
            <button class="btn btn-primary" id="addNewBtn">+ افزودن مورد جدید</button>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr>${def.listColumns.map(c => `<th>${c.label}</th>`).join('')}<th>عملیات</th></tr></thead>
                <tbody id="entityTableBody"><tr><td class="loading-row" colspan="${def.listColumns.length + 1}">در حال بارگذاری...</td></tr></tbody>
            </table>
        </div>
    `;

    document.getElementById('addNewBtn').addEventListener('click', () => openModal(entityKey, section, null));

    fetchEntityList(entityKey, section);
}

function fetchEntityList(entityKey, section) {
    fetch(`api/generic_api.php?entity=${entityKey}&section=${section}`)
        .then(r => r.json())
        .then(res => {
            const tbody = document.getElementById('entityTableBody');
            if (!tbody) return;
            const def = ENTITY_DEFS[entityKey];
            if (!res.success || !res.data || res.data.length === 0) {
                tbody.innerHTML = `<tr><td class="empty-row" colspan="${def.listColumns.length + 1}">موردی یافت نشد</td></tr>`;
                return;
            }
            tbody.innerHTML = res.data.map(row => {
                const cells = def.listColumns.map(c => {
                    let val = row[c.key];
                    if (c.type === 'status') return `<td>${statusBadge(val)}</td>`;
                    if (c.type === 'image') return `<td>${val ? `<img src="${val}" class="thumb" onerror="this.style.display='none'">` : '-'}</td>`;
                    if (val === null || val === undefined || val === '') val = '-';
                    return `<td>${String(val).length > 60 ? String(val).slice(0, 60) + '…' : val}</td>`;
                }).join('');
                return `<tr>
                    ${cells}
                    <td class="row-actions">
                        <button class="btn btn-edit" onclick='editEntityRow(${JSON.stringify(row).replace(/'/g, "&apos;")}, "${entityKey}", "${section}")'>ویرایش</button>
                        <button class="btn btn-danger" onclick="deleteEntityRow(${row.id}, '${entityKey}', '${section}')">حذف</button>
                    </td>
                </tr>`;
            }).join('');
        })
        .catch(() => showMessage('خطا در دریافت اطلاعات', 'error'));
}

function editEntityRow(row, entityKey, section) {
    openModal(entityKey, section, row);
}

function deleteEntityRow(id, entityKey, section) {
    if (!confirm('آیا از حذف این مورد مطمئن هستید؟')) return;
    const fd = new FormData();
    fd.append('action', 'delete');
    fd.append('entity', entityKey);
    fd.append('section', section);
    fd.append('id', id);
    fetch('api/generic_api.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                showMessage('با موفقیت حذف شد', 'success');
                fetchEntityList(entityKey, section);
            } else {
                showMessage(res.message || 'خطا در حذف', 'error');
            }
        });
}

function openModal(entityKey, section, existingRow) {
    const def = ENTITY_DEFS[entityKey];
    editingId = existingRow ? existingRow.id : null;
    modalTitle.textContent = existingRow ? 'ویرایش مورد' : 'افزودن مورد جدید';

    modalForm.innerHTML = def.formFields.map(f => {
        const val = existingRow ? (existingRow[f.key] ?? '') : '';
        if (f.type === 'textarea') {
            return `<div class="form-group"><label>${f.label}</label><textarea name="${f.key}">${val}</textarea></div>`;
        }
        if (f.type === 'select') {
            const opts = f.options.map(([v, l]) => `<option value="${v}" ${val === v ? 'selected' : ''}>${l}</option>`).join('');
            return `<div class="form-group"><label>${f.label}</label><select name="${f.key}">${opts}</select></div>`;
        }
        return `<div class="form-group"><label>${f.label}</label><input type="${f.type}" name="${f.key}" value="${val}" ${f.required ? 'required' : ''}></div>`;
    }).join('') + `
        <div class="modal-actions">
            <button type="submit" class="btn btn-primary">ذخیره</button>
            <button type="button" class="btn btn-outline" id="modalCancelBtn">انصراف</button>
        </div>
    `;

    document.getElementById('modalCancelBtn').addEventListener('click', closeModal);

    modalForm.onsubmit = (e) => {
        e.preventDefault();
        const fd = new FormData(modalForm);
        fd.append('entity', entityKey);
        fd.append('section', section);
        if (editingId) {
            fd.append('action', 'update');
            fd.append('id', editingId);
        } else {
            fd.append('action', 'create');
        }
        fetch('api/generic_api.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    showMessage(editingId ? 'با موفقیت ویرایش شد' : 'با موفقیت افزوده شد', 'success');
                    closeModal();
                    fetchEntityList(entityKey, section);
                } else {
                    showMessage(res.message || 'خطا در ذخیره‌سازی', 'error');
                }
            });
    };

    modalOverlay.classList.add('show');
}

function closeModal() {
    modalOverlay.classList.remove('show');
    modalForm.innerHTML = '';
    editingId = null;
}

document.getElementById('modalClose').addEventListener('click', closeModal);
modalOverlay.addEventListener('click', (e) => { if (e.target === modalOverlay) closeModal(); });

function loadUsersPanel() {
    if (!hasPermission('special:users')) return;
    panelTitle.textContent = 'کاربران سایت';
    panelBody.innerHTML = `
        <div class="panel-toolbar">
            <h2>کسانی که در سایت ثبت‌نام/لاگین کرده‌اند</h2>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th><th>نام</th><th>ایمیل</th>
                        <th>نقش</th>
                        <th>تاریخ عضویت</th><th>آخرین ورود</th><th>عملیات</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    <tr><td class="loading-row" colspan="7">در حال بارگذاری...</td></tr>
                </tbody>
            </table>
        </div>
    `;

    const userSection = document.getElementById('userDashboardSection');
    if (userSection) userSection.style.display = 'none';
    panelBody.style.display = 'block';

    fetchUsers();
}

let customRolesCache = null;

function loadCustomRolesCache() {
    if (customRolesCache) return Promise.resolve(customRolesCache);
    return fetch('api/roles_api.php')
        .then(r => r.json())
        .then(res => { customRolesCache = res.data || []; return customRolesCache; });
}

function fetchUsers() {
    Promise.all([fetch('api/users_api.php').then(r => r.json()), loadCustomRolesCache()])
        .then(([res, allRoles]) => {
            const tbody = document.getElementById('usersTableBody');
            if (!tbody) return;
            if (!res.success || !res.data.length) {
                tbody.innerHTML = `<tr><td class="empty-row" colspan="7">کاربری یافت نشد</td></tr>`;
                return;
            }

            tbody.innerHTML = res.data.map(u => {
                const currentVal = u.custom_role_id ? String(u.custom_role_id) : '';

                // همه نقش‌ها در یک select
                const opts = allRoles.map(r =>
                    `<option value="${r.id}" ${currentVal === String(r.id) ? 'selected' : ''}>${r.label}</option>`
                ).join('');

                return `
                    <tr>
                        <td>${u.id}</td>
                        <td>${u.name}</td>
                        <td>${u.email}</td>
                        <td>
                            <select onchange="changeUserRole(${u.id}, this.value)"
                                style="padding:4px 8px;border-radius:6px;border:1px solid #e8e4dc;min-width:140px;">
                                <option value="" ${!u.custom_role_id ? 'selected' : ''}>— انتخاب نقش —</option>
                                ${opts}
                            </select>
                        </td>
                        <td>${u.created_at || '-'}</td>
                        <td>${u.last_login || '<span style="color:#aaa;">هرگز</span>'}</td>
                        <td class="row-actions">
                            <button class="btn btn-danger" onclick="deleteUser(${u.id})">حذف</button>
                        </td>
                    </tr>
                `;
            }).join('');
        })
        .catch(() => showMessage('خطا در دریافت اطلاعات کاربران', 'error'));
}

function changeUserRole(userId, roleId) {
    const fd = new FormData();
    fd.append('action', 'assign_custom_role');
    fd.append('user_id', userId);
    fd.append('role_id', roleId);   // خالی = پاک کردن نقش
    fetch('api/users_api.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                showMessage('نقش کاربر به‌روزرسانی شد', 'success');
            } else {
                showMessage(res.message || 'خطا', 'error');
                fetchUsers();
            }
        });
}

function deleteUser(id) {
    if (!confirm('این کاربر برای همیشه حذف شود؟')) return;
    const fd = new FormData();
    fd.append('action', 'delete');
    fd.append('id', id);
    fetch('api/users_api.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                showMessage('کاربر حذف شد', 'success');
                fetchUsers();
            } else {
                showMessage(res.message || 'خطا', 'error');
            }
        });
}

function loadLogsPanel() {
    if (!hasPermission('special:logs')) return;
    panelTitle.textContent = 'مدیریت لاگ‌های سایت';
    panelBody.innerHTML = `
        <div class="panel-toolbar">
            <h2>لاگ‌های اخیر</h2>
            <button class="btn btn-danger" id="clearLogsBtn">پاک کردن همه لاگ‌ها</button>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>#</th><th>کاربر</th><th>عملیات</th><th>توضیحات</th><th>تاریخ</th></tr></thead>
                <tbody id="logsTableBody"><tr><td class="loading-row" colspan="5">در حال بارگذاری...</td></tr></tbody>
            </table>
        </div>
    `;
    document.getElementById('clearLogsBtn').addEventListener('click', () => {
        if (!confirm('همه لاگ‌ها پاک شوند؟ این عمل قابل بازگشت نیست.')) return;
        const fd = new FormData();
        fd.append('action', 'clear');
        fetch('api/logs_api.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(() => { showMessage('لاگ‌ها پاک شدند', 'success'); loadLogsPanel(); });
    });
    fetchLogs();
}

function fetchLogs() {
    fetch('api/logs_api.php?limit=150')
        .then(r => r.json())
        .then(res => {
            const tbody = document.getElementById('logsTableBody');
            if (!tbody) return;
            if (!res.success || !res.data.length) {
                tbody.innerHTML = `<tr><td class="empty-row" colspan="5">لاگی ثبت نشده</td></tr>`;
                return;
            }
            tbody.innerHTML = res.data.map(l => `
                <tr>
                    <td>${l.id}</td>
                    <td>${l.user_name || '-'}</td>
                    <td>${l.action}</td>
                    <td>${l.description}</td>
                    <td>${l.created_at}</td>
                </tr>
            `).join('');
        });
}

function loadSubsitesPanel() {
    if (!hasPermission('special:subsites')) return;
    panelTitle.textContent = 'مدیریت سایت‌های زیرمجموعه';
    panelBody.innerHTML = `
        <div class="panel-toolbar">
            <h2>سایت‌های زیرمجموعه</h2>
            <button class="btn btn-primary" id="addSubsiteBtn">+ افزودن سایت جدید</button>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>#</th><th>نام</th><th>آدرس</th><th>توضیحات</th><th>وضعیت</th><th>عملیات</th></tr></thead>
                <tbody id="subsitesTableBody"><tr><td class="loading-row" colspan="6">در حال بارگذاری...</td></tr></tbody>
            </table>
        </div>
    `;
    document.getElementById('addSubsiteBtn').addEventListener('click', () => openSubsiteModal(null));
    fetchSubsites();
}

function fetchSubsites() {
    fetch('api/subsites_api.php')
        .then(r => r.json())
        .then(res => {
            const tbody = document.getElementById('subsitesTableBody');
            if (!tbody) return;
            if (!res.success || !res.data.length) {
                tbody.innerHTML = `<tr><td class="empty-row" colspan="6">سایت زیرمجموعه‌ای ثبت نشده</td></tr>`;
                return;
            }
            tbody.innerHTML = res.data.map(s => `
                <tr>
                    <td>${s.id}</td>
                    <td>${s.name}</td>
                    <td><a href="${s.url}" target="_blank">${s.url}</a></td>
                    <td>${s.description || '-'}</td>
                    <td>${statusBadge(s.status)}</td>
                    <td class="row-actions">
                        <button class="btn btn-edit" onclick='editSubsite(${JSON.stringify(s).replace(/'/g, "&apos;")})'>ویرایش</button>
                        <button class="btn btn-danger" onclick="deleteSubsite(${s.id})">حذف</button>
                    </td>
                </tr>
            `).join('');
        });
}

function editSubsite(s) { openSubsiteModal(s); }

function deleteSubsite(id) {
    if (!confirm('این سایت زیرمجموعه حذف شود؟')) return;
    const fd = new FormData();
    fd.append('action', 'delete');
    fd.append('id', id);
    fetch('api/subsites_api.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(() => { showMessage('حذف شد', 'success'); fetchSubsites(); });
}

function openSubsiteModal(existing) {
    editingId = existing ? existing.id : null;
    modalTitle.textContent = existing ? 'ویرایش سایت زیرمجموعه' : 'افزودن سایت زیرمجموعه';
    modalForm.innerHTML = `
        <div class="form-group"><label>نام سایت</label><input type="text" name="name" value="${existing ? existing.name : ''}" required></div>
        <div class="form-group"><label>آدرس (URL)</label><input type="text" name="url" value="${existing ? existing.url : ''}" required></div>
        <div class="form-group"><label>توضیحات</label><textarea name="description">${existing ? (existing.description || '') : ''}</textarea></div>
        ${existing ? `<div class="form-group"><label>وضعیت</label>
            <select name="status">
                <option value="active" ${existing.status === 'active' ? 'selected' : ''}>فعال</option>
                <option value="inactive" ${existing.status === 'inactive' ? 'selected' : ''}>غیرفعال</option>
            </select></div>` : ''}
        <div class="modal-actions">
            <button type="submit" class="btn btn-primary">ذخیره</button>
            <button type="button" class="btn btn-outline" id="modalCancelBtn">انصراف</button>
        </div>
    `;
    document.getElementById('modalCancelBtn').addEventListener('click', closeModal);
    modalForm.onsubmit = (e) => {
        e.preventDefault();
        const fd = new FormData(modalForm);
        fd.append('action', editingId ? 'update' : 'create');
        if (editingId) fd.append('id', editingId);
        fetch('api/subsites_api.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    showMessage('ذخیره شد', 'success');
                    closeModal();
                    fetchSubsites();
                } else {
                    showMessage(res.message || 'خطا', 'error');
                }
            });
    };
    modalOverlay.classList.add('show');
}


let mashahirCategoryCache = null;

function loadMashahirCategoryOptions(callback) {
    if (mashahirCategoryCache) return callback(mashahirCategoryCache);
    fetch('api/mashahir_categories_api.php')
        .then(r => r.json())
        .then(res => {
            mashahirCategoryCache = (res.data || []).map(c => [c.id, c.name_fa]);
            callback(mashahirCategoryCache);
        });
}


function loadMashahirCategoriesPanel() {
    panelTitle.textContent = 'دسته‌بندی‌های مشاهیر';
    panelBody.innerHTML = `
        <div class="panel-toolbar">
            <h2>دسته‌بندی‌های مشاهیر</h2>
            <button class="btn btn-primary" id="addMcBtn">+ افزودن دسته جدید</button>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>#</th><th>آیکون</th><th>نام فارسی</th><th>نام انگلیسی</th><th>ترتیب</th><th>فعال</th><th>عملیات</th></tr></thead>
                <tbody id="mcTableBody"><tr><td class="loading-row" colspan="7">در حال بارگذاری...</td></tr></tbody>
            </table>
        </div>
    `;
    document.getElementById('addMcBtn').addEventListener('click', () => openMashahirCategoryModal(null));
    fetchMashahirCategories();
}

function fetchMashahirCategories() {
    fetch('api/mashahir_categories_api.php')
        .then(r => r.json())
        .then(res => {
            mashahirCategoryCache = null; // رفرش کش دسته‌ها برای فرم مشاهیر
            const tbody = document.getElementById('mcTableBody');
            if (!tbody) return;
            if (!res.success || !res.data.length) {
                tbody.innerHTML = `<tr><td class="empty-row" colspan="7">دسته‌ای یافت نشد</td></tr>`;
                return;
            }
            tbody.innerHTML = res.data.map(c => `
                <tr>
                    <td>${c.id}</td>
                    <td>${c.image ? `<img src="../mashahir/public/img/${c.image}" class="thumb" onerror="this.style.display='none'">` : '-'}</td>
                    <td>${c.name_fa}</td>
                    <td>${c.name_en || '-'}</td>
                    <td>${c.sort_order}</td>
                    <td>${c.is_active == 1 ? '<span class="badge badge-active">بله</span>' : '<span class="badge badge-inactive">خیر</span>'}</td>
                    <td class="row-actions">
                        <button class="btn btn-edit" onclick='openMashahirCategoryModal(${JSON.stringify(c).replace(/'/g, "&apos;")})'>ویرایش</button>
                        <button class="btn btn-danger" onclick="deleteMashahirCategory(${c.id})">حذف</button>
                    </td>
                </tr>
            `).join('');
        });
}

function deleteMashahirCategory(id) {
    if (!confirm('این دسته حذف شود؟')) return;
    const fd = new FormData(); fd.append('action', 'delete'); fd.append('id', id);
    fetch('api/mashahir_categories_api.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(() => { showMessage('حذف شد', 'success'); fetchMashahirCategories(); });
}

function openMashahirCategoryModal(existing) {
    editingId = existing ? existing.id : null;
    modalTitle.textContent = existing ? 'ویرایش دسته' : 'افزودن دسته جدید';
    modalForm.innerHTML = `
        <div class="form-group"><label>نام فارسی</label><input type="text" name="name_fa" value="${existing ? existing.name_fa : ''}" required></div>
        <div class="form-group"><label>نام انگلیسی</label><input type="text" name="name_en" value="${existing ? (existing.name_en || '') : ''}"></div>
        <div class="form-group"><label>نام فایل آیکون</label><input type="text" name="image" value="${existing ? (existing.image || '') : ''}"></div>
        <div class="form-group"><label>ترتیب نمایش</label><input type="number" name="sort_order" value="${existing ? existing.sort_order : 0}"></div>
        <div class="form-group"><label>فعال؟</label>
            <select name="is_active">
                <option value="1" ${!existing || existing.is_active == 1 ? 'selected' : ''}>بله</option>
                <option value="0" ${existing && existing.is_active == 0 ? 'selected' : ''}>خیر</option>
            </select>
        </div>
        <div class="modal-actions">
            <button type="submit" class="btn btn-primary">ذخیره</button>
            <button type="button" class="btn btn-outline" id="modalCancelBtn">انصراف</button>
        </div>
    `;
    document.getElementById('modalCancelBtn').addEventListener('click', closeModal);
    modalForm.onsubmit = (e) => {
        e.preventDefault();
        const fd = new FormData(modalForm);
        fd.append('action', editingId ? 'update' : 'create');
        if (editingId) fd.append('id', editingId);
        fetch('api/mashahir_categories_api.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(res => {
                if (res.success) { showMessage('ذخیره شد', 'success'); closeModal(); fetchMashahirCategories(); }
                else showMessage(res.message || 'خطا', 'error');
            });
    };
    modalOverlay.classList.add('show');
}

function loadMashahirCelebritiesPanel() {
    panelTitle.textContent = 'مدیریت مشاهیر';
    panelBody.innerHTML = `
        <div class="panel-toolbar">
            <h2>مشاهیر</h2>
            <button class="btn btn-primary" id="addMcelBtn">+ افزودن فرد جدید</button>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>#</th><th>تصویر</th><th>نام</th><th>دسته</th><th>شغل/جایگاه</th><th>ویژه</th><th>فعال</th><th>عملیات</th></tr></thead>
                <tbody id="mcelTableBody"><tr><td class="loading-row" colspan="8">در حال بارگذاری...</td></tr></tbody>
            </table>
        </div>
    `;
    document.getElementById('addMcelBtn').addEventListener('click', () => openMashahirCelebrityModal(null));
    fetchMashahirCelebrities();
}

function fetchMashahirCelebrities() {
    fetch('api/mashahir_celebrities_api.php')
        .then(r => r.json())
        .then(res => {
            const tbody = document.getElementById('mcelTableBody');
            if (!tbody) return;
            if (!res.success || !res.data.length) {
                tbody.innerHTML = `<tr><td class="empty-row" colspan="8">موردی یافت نشد</td></tr>`;
                return;
            }
            tbody.innerHTML = res.data.map(c => `
                <tr>
                    <td>${c.id}</td>
                    <td>${c.image ? `<img src="../mashahir/public/img/${c.image}" class="thumb" onerror="this.style.display='none'">` : '-'}</td>
                    <td>${c.name_fa}</td>
                    <td>${c.category_name_fa || '-'}</td>
                    <td>${c.job_fa || '-'}</td>
                    <td>${c.is_featured == 1 ? '<span class="badge badge-active">بله</span>' : '<span class="badge badge-inactive">خیر</span>'}</td>
                    <td>${c.is_active == 1 ? '<span class="badge badge-active">بله</span>' : '<span class="badge badge-inactive">خیر</span>'}</td>
                    <td class="row-actions">
                        <button class="btn btn-edit" onclick='openMashahirCelebrityModal(${JSON.stringify(c).replace(/'/g, "&apos;")})'>ویرایش</button>
                        <button class="btn btn-danger" onclick="deleteMashahirCelebrity(${c.id})">حذف</button>
                    </td>
                </tr>
            `).join('');
        });
}

function deleteMashahirCelebrity(id) {
    if (!confirm('این مورد حذف شود؟')) return;
    const fd = new FormData(); fd.append('action', 'delete'); fd.append('id', id);
    fetch('api/mashahir_celebrities_api.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(() => { showMessage('حذف شد', 'success'); fetchMashahirCelebrities(); });
}

const MCEL_FIELDS = [
    ['name_fa', 'نام (فارسی)', 'text', true],
    ['name_en', 'نام (انگلیسی)', 'text'],
    ['full_name_fa', 'نام کامل (فارسی)', 'text'],
    ['slug', 'اسلاگ (یکتا، لاتین)', 'text', true],
    ['birth_year', 'سال تولد', 'text'],
    ['birth_place_fa', 'محل تولد', 'text'],
    ['death_year', 'سال وفات', 'text'],
    ['death_place_fa', 'محل وفات', 'text'],
    ['job_fa', 'شغل/جایگاه', 'text'],
    ['era_fa', 'دوره تاریخی', 'text'],
    ['short_story_fa', 'خلاصه داستان', 'textarea'],
    ['biography_fa', 'زندگی‌نامه کامل', 'textarea'],
    ['quote_fa', 'نقل‌قول', 'textarea'],
    ['tags_fa', 'برچسب‌ها (با کاما جدا شود)', 'text'],
    ['image', 'نام فایل تصویر', 'text'],
    ['sort_order', 'ترتیب نمایش', 'number'],
];

function openMashahirCelebrityModal(existing) {
    editingId = existing ? existing.id : null;
    modalTitle.textContent = existing ? 'ویرایش فرد' : 'افزودن فرد جدید';

    loadMashahirCategoryOptions((cats) => {
        const catOptions = cats.map(([id, name]) =>
            `<option value="${id}" ${existing && String(existing.category_id) === String(id) ? 'selected' : ''}>${name}</option>`).join('');

        const fieldsHtml = MCEL_FIELDS.map(([key, label, type, required]) => {
            const val = existing ? (existing[key] ?? '') : '';
            if (type === 'textarea') {
                return `<div class="form-group"><label>${label}</label><textarea name="${key}">${val}</textarea></div>`;
            }
            return `<div class="form-group"><label>${label}</label><input type="${type}" name="${key}" value="${val}" ${required ? 'required' : ''}></div>`;
        }).join('');

        modalForm.innerHTML = `
            <div class="form-group"><label>دسته‌بندی اصلی</label>
                <select name="category_id"><option value="">— انتخاب نشده —</option>${catOptions}</select>
            </div>
            ${fieldsHtml}
            <div class="form-group"><label>ویژه؟</label>
                <select name="is_featured">
                    <option value="0" ${!existing || existing.is_featured == 0 ? 'selected' : ''}>خیر</option>
                    <option value="1" ${existing && existing.is_featured == 1 ? 'selected' : ''}>بله</option>
                </select>
            </div>
            <div class="form-group"><label>فعال؟</label>
                <select name="is_active">
                    <option value="1" ${!existing || existing.is_active == 1 ? 'selected' : ''}>بله</option>
                    <option value="0" ${existing && existing.is_active == 0 ? 'selected' : ''}>خیر</option>
                </select>
            </div>
            <div class="modal-actions">
                <button type="submit" class="btn btn-primary">ذخیره</button>
                <button type="button" class="btn btn-outline" id="modalCancelBtn">انصراف</button>
            </div>
        `;
        document.getElementById('modalCancelBtn').addEventListener('click', closeModal);
        modalForm.onsubmit = (e) => {
            e.preventDefault();
            const fd = new FormData(modalForm);
            fd.append('action', editingId ? 'update' : 'create');
            if (editingId) fd.append('id', editingId);
            fetch('api/mashahir_celebrities_api.php', { method: 'POST', body: fd })
                .then(r => r.json())
                .then(res => {
                    if (res.success) { showMessage('ذخیره شد', 'success'); closeModal(); fetchMashahirCelebrities(); }
                    else showMessage(res.message || 'خطا', 'error');
                });
        };
        modalOverlay.classList.add('show');
    });
}

function loadMashahirProductsPanel() {
    panelTitle.textContent = 'محصولات مشاهیر (کتاب‌ها)';
    panelBody.innerHTML = `
        <div class="panel-toolbar">
            <h2>محصولات</h2>
            <button class="btn btn-primary" id="addMpBtn">+ ثبت محصول جدید</button>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>#</th><th>تصویر</th><th>عنوان</th><th>فرد مرتبط</th><th>نوع</th><th>قیمت چاپی</th><th>قیمت PDF</th><th>موجودی</th><th>فعال</th><th>ثبت‌کننده</th><th>عملیات</th></tr></thead>
                <tbody id="mpTableBody"><tr><td class="loading-row" colspan="11">در حال بارگذاری...</td></tr></tbody>
            </table>
        </div>
    `;
    document.getElementById('addMpBtn').addEventListener('click', () => loadMashahirProductRegisterPanel());
    fetchMashahirProducts();
}

function mpFormatPrice(v) {
    return (v === null || v === undefined || v === '') ? '-' : Number(v).toLocaleString('fa-IR');
}

function fetchMashahirProducts() {
    fetch('api/mashahir_products_api.php')
        .then(r => r.json())
        .then(res => {
            const tbody = document.getElementById('mpTableBody');
            if (!tbody) return;
            if (!res.success || !res.data.length) {
                tbody.innerHTML = `<tr><td class="empty-row" colspan="11">موردی یافت نشد</td></tr>`;
                return;
            }
            tbody.innerHTML = res.data.map(p => {
                const isForSale = !!(p.price_print || p.sale_price_print || p.price_pdf || p.sale_price_pdf);
                const stock = p.stock ?? 0;
                return `
                <tr>
                    <td>${p.id}</td>
                    <td>${p.image ? `<img src="../mashahir/public/img/${p.image}" class="thumb" onerror="this.style.display='none'">` : '-'}</td>
                    <td>${p.title_fa}</td>
                    <td>${p.celebrity_name_fa || '-'}</td>
                    <td>${isForSale ? '<span class="badge badge-active">فروش</span>' : '<span class="badge badge-pending">معرفی</span>'}</td>
                    <td>${mpFormatPrice(p.sale_price_print || p.price_print)}</td>
                    <td>${mpFormatPrice(p.sale_price_pdf || p.price_pdf)}</td>
                    <td>
                        <div class="stock-cell">
                            <span id="mpStock${p.id}">${stock}</span>
                            <button type="button" class="btn btn-edit btn-stock-add" title="افزودن موجودی" onclick="addMashahirStock(${p.id})">+</button>
                        </div>
                    </td>
                    <td>${p.is_active == 1 ? '<span class="badge badge-active">بله</span>' : '<span class="badge badge-inactive">خیر</span>'}</td>
                    <td>${p.created_by || '-'}</td>
                    <td><div class="row-actions">
                        <button class="btn btn-edit" onclick='loadMashahirProductRegisterPanel(${JSON.stringify(p).replace(/'/g, "&apos;")})'>ویرایش</button>
                        <button class="btn btn-danger" onclick="deleteMashahirProduct(${p.id})">حذف</button>
                    </div></td>
                </tr>
            `;
            }).join('');
        });
}

function addMashahirStock(id) {
    const input = prompt('چند عدد به موجودی اضافه شود؟ (برای کم کردن، عدد منفی وارد کنید)', '1');
    if (input === null) return;
    const delta = parseInt(input, 10);
    if (!delta) { showMessage('عدد وارد شده نامعتبر است', 'error'); return; }
    const fd = new FormData();
    fd.append('action', 'adjust_stock');
    fd.append('id', id);
    fd.append('delta', delta);
    fetch('api/mashahir_products_api.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(res => {
            if (!res.success) { showMessage(res.message || 'خطا در بروزرسانی موجودی', 'error'); return; }
            const cell = document.getElementById('mpStock' + id);
            if (cell) cell.textContent = res.stock;
            showMessage('موجودی بروزرسانی شد', 'success');
        });
}

function deleteMashahirProduct(id) {
    if (!confirm('این محصول حذف شود؟')) return;
    const fd = new FormData(); fd.append('action', 'delete'); fd.append('id', id);
    fetch('api/mashahir_products_api.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(() => { showMessage('حذف شد', 'success'); fetchMashahirProducts(); });
}

const MP_TEXT_FIELDS = [
    ['title_fa', 'عنوان (فارسی)', 'text', true],
    ['title_en', 'عنوان (انگلیسی)', 'text'],
    ['full_title_fa', 'عنوان کامل (فارسی)', 'text'],
    ['full_title_en', 'عنوان کامل (انگلیسی)', 'text'],
    ['slug', 'اسلاگ (یکتا، لاتین)', 'text', true],
    ['image', 'نام فایل تصویر (داخل mashahir/public/img)', 'text'],
    ['author_fa', 'نویسنده (فارسی)', 'text'],
    ['author_en', 'نویسنده (انگلیسی)', 'text'],
    ['translator_fa', 'مترجم (فارسی)', 'text'],
    ['translator_en', 'مترجم (انگلیسی)', 'text'],
    ['publisher_fa', 'ناشر (فارسی)', 'text'],
    ['publisher_en', 'ناشر (انگلیسی)', 'text'],
    ['publish_year', 'سال انتشار', 'text'],
    ['pages', 'تعداد صفحات', 'number'],
    ['language_fa', 'زبان اثر (فارسی)', 'text'],
    ['language_en', 'زبان اثر (انگلیسی)', 'text'],
    ['genre_fa', 'ژانر (فارسی)', 'text'],
    ['genre_en', 'ژانر (انگلیسی)', 'text'],
    ['era_fa', 'دوره تاریخی (فارسی)', 'text'],
    ['era_en', 'دوره تاریخی (انگلیسی)', 'text'],
    ['tags_fa', 'برچسب‌ها (فارسی، با کاما جدا شود)', 'text'],
    ['tags_en', 'برچسب‌ها (انگلیسی، با کاما جدا شود)', 'text'],
    ['sort_order', 'ترتیب نمایش', 'number'],
];

const MP_TEXTAREA_FIELDS = [
    ['short_desc_fa', 'توضیح کوتاه (فارسی)'],
    ['short_desc_en', 'توضیح کوتاه (انگلیسی)'],
    ['description_fa', 'توضیحات کامل (فارسی)'],
    ['description_en', 'توضیحات کامل (انگلیسی)'],
    ['quote_fa', 'نقل‌قول (فارسی)'],
    ['quote_en', 'نقل‌قول (انگلیسی)'],
];

const MP_SALE_FIELDS = [
    ['price_print', 'قیمت نسخه چاپی (تومان)', 'number'],
    ['sale_price_print', 'قیمت با تخفیف - چاپی', 'number'],
    ['price_pdf', 'قیمت نسخه PDF (تومان)', 'number'],
    ['sale_price_pdf', 'قیمت با تخفیف - PDF', 'number'],
    ['stock', 'موجودی (تعداد نسخه چاپی)', 'number'],
];

let mpCurrentMode = 'intro';
let mpEditions = [];
let mpEditingProduct = null;

function loadMashahirProductRegisterPanel(existing) {
    currentSpecial = 'mashahir_product_register';
    mpEditingProduct = existing || null;
    editingId = existing ? existing.id : null;

    const hasSaleData = !!(existing && (existing.price_print || existing.sale_price_print || existing.price_pdf || existing.sale_price_pdf));
    mpCurrentMode = hasSaleData ? 'sale' : 'intro';
    mpEditions = [];

    panelTitle.textContent = existing ? 'ویرایش محصول' : 'ثبت محصول جدید';
    panelBody.innerHTML = `
        <div class="panel-toolbar">
            <h2>${existing ? 'ویرایش محصول: ' + (existing.title_fa || '') : 'ثبت محصول جدید'}</h2>
            <button class="btn btn-outline" id="mpBackBtn">بازگشت به لیست محصولات</button>
        </div>

        <div class="mp-mode-tabs" id="mpModeTabs">
            <button type="button" class="mp-mode-tab ${mpCurrentMode === 'intro' ? 'active' : ''}" data-mode="intro">
                معرفی
            </button>
            <button type="button" class="mp-mode-tab ${mpCurrentMode === 'sale' ? 'active' : ''}" data-mode="sale">
                فروش
            </button>
        </div>
        <p class="mp-mode-hint" id="mpModeHint"></p>

        <form id="mpRegisterForm" class="mp-register-form"></form>
    `;

    document.getElementById('mpBackBtn').addEventListener('click', () => loadMashahirProductsPanel());
    document.querySelectorAll('#mpModeTabs .mp-mode-tab').forEach(btn => {
        btn.addEventListener('click', () => {
            mpCurrentMode = btn.dataset.mode;
            document.querySelectorAll('#mpModeTabs .mp-mode-tab').forEach(b => b.classList.toggle('active', b === btn));
            renderMashahirProductForm();
        });
    });

    fetch('api/mashahir_celebrities_api.php')
        .then(r => r.json())
        .then(res => {
            mpCelebrityOptions = (res.data || []).map(c => [c.id, c.name_fa]);
            if (existing && existing.id) {
                fetch('api/mashahir_products_api.php?editions=1&product_id=' + existing.id)
                    .then(r2 => r2.json())
                    .then(res2 => {
                        mpEditions = (res2.data || []).map(e => ({
                            edition_type: e.edition_type, lang: e.lang,
                            label_fa: e.label_fa, label_en: e.label_en,
                            file_path: e.file_path, file_size_kb: e.file_size_kb,
                            pages: e.pages, is_active: e.is_active,
                        }));
                        renderMashahirProductForm();
                    });
            } else {
                renderMashahirProductForm();
            }
        });
}

let mpCelebrityOptions = [];

function renderMashahirProductForm() {
    const existing = mpEditingProduct;
    const hint = document.getElementById('mpModeHint');
    if (hint) {
        hint.textContent = mpCurrentMode === 'intro'
            ? 'در حالت «معرفی» فقط اطلاعات محصول ثبت می‌شود و امکان خرید (چاپی/PDF) برای آن فعال نیست.'
            : 'در حالت «فروش» علاوه بر اطلاعات محصول، قیمت‌گذاری و نسخه‌های قابل خرید (چاپی/PDF به زبان‌های مختلف) هم ثبت می‌شود.';
    }

    const celebOptions = mpCelebrityOptions.map(([id, name]) =>
        `<option value="${id}" ${existing && String(existing.celebrity_id) === String(id) ? 'selected' : ''}>${name}</option>`).join('');

    const textFieldsHtml = MP_TEXT_FIELDS.map(([key, label, type, required]) => {
        const val = existing ? (existing[key] ?? '') : '';
        return `<div class="form-group"><label>${label}</label><input type="${type}" name="${key}" value="${val}" ${required ? 'required' : ''}></div>`;
    }).join('');

    const textareaFieldsHtml = MP_TEXTAREA_FIELDS.map(([key, label]) => {
        const val = existing ? (existing[key] ?? '') : '';
        return `<div class="form-group"><label>${label}</label><textarea name="${key}">${val}</textarea></div>`;
    }).join('');

    const saleFieldsHtml = MP_SALE_FIELDS.map(([key, label, type]) => {
        const val = existing ? (existing[key] ?? '') : '';
        return `<div class="form-group"><label>${label}</label><input type="${type}" name="${key}" value="${val}"></div>`;
    }).join('');

    const form = document.getElementById('mpRegisterForm');
    form.innerHTML = `
        <div class="form-group"><label>فرد مرتبط</label>
            <select name="celebrity_id"><option value="">— انتخاب نشده —</option>${celebOptions}</select>
        </div>
        ${textFieldsHtml}
        ${textareaFieldsHtml}
        <div class="form-group"><label>ویژه؟</label>
            <select name="is_featured">
                <option value="0" ${!existing || existing.is_featured == 0 ? 'selected' : ''}>خیر</option>
                <option value="1" ${existing && existing.is_featured == 1 ? 'selected' : ''}>بله</option>
            </select>
        </div>
        <div class="form-group"><label>فعال (نمایش در سایت)؟</label>
            <select name="is_active">
                <option value="1" ${!existing || existing.is_active == 1 ? 'selected' : ''}>بله</option>
                <option value="0" ${existing && existing.is_active == 0 ? 'selected' : ''}>خیر</option>
            </select>
        </div>

        ${mpCurrentMode === 'sale' ? `
        <h3 class="mp-section-title">قیمت‌گذاری</h3>
        ${saleFieldsHtml}

        <h3 class="mp-section-title">نسخه‌های قابل خرید (چاپی / PDF به زبان‌های مختلف)</h3>
        <div id="mpEditionsList" class="mp-editions-list"></div>
        <button type="button" class="btn btn-outline" id="mpAddEditionBtn">+ افزودن نسخه جدید</button>
        ` : ''}

        <div class="modal-actions">
            <button type="submit" class="btn btn-primary">ذخیره محصول</button>
        </div>
    `;

    if (mpCurrentMode === 'sale') {
        renderMpEditionsList();
        document.getElementById('mpAddEditionBtn').addEventListener('click', () => {
            mpEditions.push({ edition_type: 'pdf', lang: 'fa', label_fa: '', label_en: '', file_path: '', pages: '', is_active: 1 });
            renderMpEditionsList();
        });
    }

    form.onsubmit = (e) => {
        e.preventDefault();
        saveMashahirProduct(form);
    };
}

function renderMpEditionsList() {
    const wrap = document.getElementById('mpEditionsList');
    if (!wrap) return;
    if (!mpEditions.length) {
        wrap.innerHTML = `<p class="mp-editions-empty">هنوز نسخه‌ای اضافه نشده است.</p>`;
        return;
    }
    wrap.innerHTML = mpEditions.map((ed, i) => `
        <div class="mp-edition-row">
            <select data-i="${i}" data-f="edition_type">
                <option value="print" ${ed.edition_type === 'print' ? 'selected' : ''}>چاپی</option>
                <option value="pdf" ${ed.edition_type === 'pdf' ? 'selected' : ''}>PDF</option>
            </select>
            <select data-i="${i}" data-f="lang">
                <option value="fa" ${ed.lang === 'fa' ? 'selected' : ''}>فارسی</option>
                <option value="en" ${ed.lang === 'en' ? 'selected' : ''}>انگلیسی</option>
            </select>
            <input type="text" data-i="${i}" data-f="label_fa" placeholder="برچسب فارسی (مثلاً نسخه فارسی)" value="${ed.label_fa || ''}">
            <input type="text" data-i="${i}" data-f="label_en" placeholder="برچسب انگلیسی" value="${ed.label_en || ''}">
            <input type="text" data-i="${i}" data-f="file_path" placeholder="مسیر فایل (برای PDF)" value="${ed.file_path || ''}">
            <input type="number" data-i="${i}" data-f="pages" placeholder="صفحات" value="${ed.pages || ''}">
            <button type="button" class="btn btn-danger" data-remove="${i}">حذف</button>
        </div>
    `).join('');

    wrap.querySelectorAll('select, input').forEach(el => {
        el.addEventListener('input', () => {
            const i = +el.dataset.i, f = el.dataset.f;
            mpEditions[i][f] = el.value;
        });
        el.addEventListener('change', () => {
            const i = +el.dataset.i, f = el.dataset.f;
            mpEditions[i][f] = el.value;
        });
    });
    wrap.querySelectorAll('[data-remove]').forEach(btn => {
        btn.addEventListener('click', () => {
            mpEditions.splice(+btn.dataset.remove, 1);
            renderMpEditionsList();
        });
    });
}

function saveMashahirProduct(form) {
    const fd = new FormData(form);
    fd.append('action', editingId ? 'update' : 'create');
    if (editingId) fd.append('id', editingId);

    if (mpCurrentMode === 'intro') {
        ['price_print', 'sale_price_print', 'price_pdf', 'sale_price_pdf'].forEach(k => fd.set(k, ''));
    }

    fetch('api/mashahir_products_api.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(res => {
            if (!res.success) { showMessage(res.message || 'خطا', 'error'); return; }
            const productId = editingId || res.id;

            if (mpCurrentMode === 'sale') {
                const efd = new FormData();
                efd.append('action', 'save_editions');
                efd.append('product_id', productId);
                efd.append('editions_json', JSON.stringify(mpEditions));
                fetch('api/mashahir_products_api.php', { method: 'POST', body: efd })
                    .then(r2 => r2.json())
                    .then(() => {
                        showMessage('محصول ذخیره شد', 'success');
                        loadMashahirProductsPanel();
                    });
            } else {
                showMessage('محصول ذخیره شد', 'success');
                loadMashahirProductsPanel();
            }
        });
}

function loadMashahirReviewsPanel() {
    panelTitle.textContent = 'نظرات مشاهیر';
    panelBody.innerHTML = `
        <div class="panel-toolbar">
            <h2>نظرات</h2>
            <button class="btn btn-primary" id="addMrBtn">+ افزودن نظر جدید</button>
        </div>
        <div class="mr-filter-tabs" id="mrFilterTabs">
            <button type="button" class="mr-filter-tab active" data-status="">همه</button>
            <button type="button" class="mr-filter-tab" data-status="pending">در انتظار تایید</button>
            <button type="button" class="mr-filter-tab" data-status="approved">تایید‌شده</button>
            <button type="button" class="mr-filter-tab" data-status="rejected">رد‌شده</button>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>#</th><th>نویسنده</th><th>فرد مرتبط</th><th>محصول</th><th>امتیاز</th><th>متن</th><th>وضعیت</th><th>عملیات</th></tr></thead>
                <tbody id="mrTableBody"><tr><td class="loading-row" colspan="8">در حال بارگذاری...</td></tr></tbody>
            </table>
        </div>
    `;
    document.getElementById('addMrBtn').addEventListener('click', () => openMashahirReviewModal(null));
    document.querySelectorAll('#mrFilterTabs .mr-filter-tab').forEach(btn => {
        btn.addEventListener('click', () => {
            mrCurrentFilter = btn.dataset.status;
            document.querySelectorAll('#mrFilterTabs .mr-filter-tab').forEach(b => b.classList.toggle('active', b === btn));
            fetchMashahirReviews();
        });
    });
    fetchMashahirReviews();
}

let mrCurrentFilter = '';

function fetchMashahirReviews() {
    const qs = mrCurrentFilter ? ('?status=' + encodeURIComponent(mrCurrentFilter)) : '';
    fetch('api/mashahir_reviews_api.php' + qs)
        .then(r => r.json())
        .then(res => {
            const tbody = document.getElementById('mrTableBody');
            if (!tbody) return;
            if (!res.success || !res.data.length) {
                tbody.innerHTML = `<tr><td class="empty-row" colspan="8">نظری یافت نشد</td></tr>`;
                return;
            }
            tbody.innerHTML = res.data.map(r => {
                const status = r.status || (r.is_active == 1 ? 'approved' : 'rejected');
                return `
                <tr>
                    <td>${r.id}</td>
                    <td>${r.author_name || '-'}</td>
                    <td>${r.celebrity_name_fa || '-'}</td>
                    <td>${r.product_title_fa || '-'}</td>
                    <td>${r.rating} ⭐</td>
                    <td>${(r.content_fa || r.content_en || '').slice(0, 50)}${(r.content_fa || r.content_en || '').length > 50 ? '…' : ''}</td>
                    <td>${statusBadge(status)}</td>
                    <td><div class="row-actions">
                        ${status !== 'approved' ? `<button class="btn btn-edit" onclick="moderateMashahirReview(${r.id}, 'approved')">تایید</button>` : ''}
                        ${status !== 'rejected' ? `<button class="btn btn-danger" onclick="moderateMashahirReview(${r.id}, 'rejected')">رد</button>` : ''}
                        <button class="btn btn-outline" onclick='openMashahirReviewModal(${JSON.stringify(r).replace(/'/g, "&apos;")})'>ویرایش</button>
                        <button class="btn btn-danger" onclick="deleteMashahirReview(${r.id})">حذف</button>
                    </div></td>
                </tr>
            `;
            }).join('');
        });
}

function moderateMashahirReview(id, decision) {
    const fd = new FormData();
    fd.append('action', 'moderate');
    fd.append('id', id);
    fd.append('decision', decision);
    fetch('api/mashahir_reviews_api.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(res => {
            if (res.success) { showMessage(decision === 'approved' ? 'نظر تایید شد' : 'نظر رد شد', 'success'); fetchMashahirReviews(); }
            else showMessage(res.message || 'خطا', 'error');
        });
}

function deleteMashahirReview(id) {
    if (!confirm('این نظر حذف شود؟')) return;
    const fd = new FormData(); fd.append('action', 'delete'); fd.append('id', id);
    fetch('api/mashahir_reviews_api.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(() => { showMessage('حذف شد', 'success'); fetchMashahirReviews(); });
}

function openMashahirReviewModal(existing) {
    editingId = existing ? existing.id : null;
    modalTitle.textContent = existing ? 'ویرایش نظر' : 'افزودن نظر جدید';
    const status = existing ? (existing.status || (existing.is_active == 1 ? 'approved' : 'rejected')) : 'approved';

    fetch('api/mashahir_celebrities_api.php')
        .then(r => r.json())
        .then(res => {
            const celebs = (res.data || []).map(c => [c.id, c.name_fa]);
            const celebOptions = celebs.map(([id, name]) =>
                `<option value="${id}" ${existing && String(existing.celebrity_id) === String(id) ? 'selected' : ''}>${name}</option>`).join('');

            modalForm.innerHTML = `
                <div class="form-group"><label>فرد مرتبط</label>
                    <select name="celebrity_id"><option value="">— انتخاب نشده —</option>${celebOptions}</select>
                </div>
                <div class="form-group"><label>شناسه محصول مرتبط (اختیاری)</label>
                    <input type="number" name="product_id" value="${existing ? (existing.product_id || '') : ''}">
                </div>
                <div class="form-group"><label>نام نویسنده</label>
                    <input type="text" name="author_name" value="${existing ? (existing.author_name || '') : ''}" required>
                </div>
                <div class="form-group"><label>امتیاز (۱ تا ۵)</label>
                    <input type="number" name="rating" min="1" max="5" value="${existing ? existing.rating : 5}">
                </div>
                <div class="form-group"><label>متن نظر</label>
                    <textarea name="content_fa" required>${existing ? (existing.content_fa || '') : ''}</textarea>
                </div>
                <div class="form-group"><label>وضعیت تایید</label>
                    <select name="status">
                        <option value="pending" ${status === 'pending' ? 'selected' : ''}>در انتظار تایید</option>
                        <option value="approved" ${status === 'approved' ? 'selected' : ''}>تایید‌شده</option>
                        <option value="rejected" ${status === 'rejected' ? 'selected' : ''}>رد‌شده</option>
                    </select>
                </div>
                <div class="modal-actions">
                    <button type="submit" class="btn btn-primary">ذخیره</button>
                    <button type="button" class="btn btn-outline" id="modalCancelBtn">انصراف</button>
                </div>
            `;
            document.getElementById('modalCancelBtn').addEventListener('click', closeModal);
            modalForm.onsubmit = (e) => {
                e.preventDefault();
                const fd = new FormData(modalForm);
                fd.append('action', editingId ? 'update' : 'create');
                if (editingId) fd.append('id', editingId);
                fetch('api/mashahir_reviews_api.php', { method: 'POST', body: fd })
                    .then(r => r.json())
                    .then(res2 => {
                        if (res2.success) { showMessage('ذخیره شد', 'success'); closeModal(); fetchMashahirReviews(); }
                        else showMessage(res2.message || 'خطا', 'error');
                    });
            };
            modalOverlay.classList.add('show');
        });
}

// ═══════════════════════════════════════════════════════════
//  مدیریت نقش‌ها
// ═══════════════════════════════════════════════════════════

function loadRolesPanel() {
    if (!hasPermission('special:roles')) return;
    panelTitle.textContent = 'مدیریت نقش‌ها';
    panelBody.innerHTML = `
        <div class="panel-toolbar">
            <h2>مدیریت نقش‌ها</h2>
            <button class="btn btn-primary" id="addRoleBtn">+ ساخت نقش جدید</button>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>نام</th>
                        <th>عنوان</th>
                        <th>توضیحات</th>
                        <th>مجوزها</th>
                        <th>کاربران</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody id="rolesTableBody">
                    <tr><td class="loading-row" colspan="6">در حال بارگذاری...</td></tr>
                </tbody>
            </table>
        </div>
    `;

    const userSection = document.getElementById('userDashboardSection');
    if (userSection) userSection.style.display = 'none';
    panelBody.style.display = 'block';

    document.getElementById('addRoleBtn').addEventListener('click', () => openRoleModal(null));
    fetchRoles();
}

function fetchRoles() {
    fetch('api/roles_api.php')
        .then(r => r.json())
        .then(res => {
            const tbody = document.getElementById('rolesTableBody');
            if (!tbody) return;
            if (!res.success || !res.data || !res.data.length) {
                tbody.innerHTML = `<tr><td class="empty-row" colspan="6">نقشی تعریف نشده</td></tr>`;
                return;
            }

            // cache رو رفرش کن (برای fetchUsers)
            customRolesCache = res.data;

            tbody.innerHTML = res.data.map(role => `
                    <tr>
                        <td><code style="background:#f5f0e8;padding:2px 6px;border-radius:4px;font-size:0.85em;">${role.name}</code></td>
                        <td><strong>${role.label}</strong></td>
                        <td style="font-size:0.88em;color:#888;">${role.description || '—'}</td>
                        <td><span class="badge badge-active">${role.perm_count} مجوز</span></td>
                        <td>${role.user_count > 0 ? `<span class="badge badge-pending">${role.user_count} کاربر</span>` : '—'}</td>
                        <td class="row-actions">
                            <button class="btn btn-edit" onclick="editRole(${role.id})">ویرایش</button>
                            <button class="btn btn-danger" onclick="deleteRole(${role.id}, '${role.label.replace(/'/g, "\\'")}', ${role.user_count})">حذف</button>
                        </td>
                    </tr>
                `).join('');
        })
        .catch(() => showMessage('خطا در دریافت نقش‌ها', 'error'));
}

function editRole(id) {
    fetch(`api/roles_api.php?action=get&id=${id}`)
        .then(r => r.json())
        .then(res => {
            if (res.success) openRoleModal(res.data);
            else showMessage('خطا در دریافت اطلاعات نقش', 'error');
        });
}

function deleteRole(id, label, userCount) {
    if (userCount > 0) {
        showMessage(`نقش "${label}" به ${userCount} کاربر اختصاص دارد. ابتدا نقش آن کاربران را تغییر دهید.`, 'error');
        return;
    }
    if (!confirm(`نقش "${label}" حذف شود؟`)) return;
    const fd = new FormData();
    fd.append('action', 'delete');
    fd.append('id', id);
    fetch('api/roles_api.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                showMessage('نقش حذف شد', 'success');
                fetchRoles();
            } else {
                showMessage(res.message || 'خطا در حذف', 'error');
            }
        });
}

let rolesPermissionsSchema = null;

function openRoleModal(existing) {
    // schema رو هر بار fresh بگیر تا تغییرات جدید منعکس بشه
    rolesPermissionsSchema = null;
    const loadSchema = fetch('api/roles_api.php?action=permissions_schema')
        .then(r => r.json())
        .then(res => {
            if (!res.success || !res.data) throw new Error(res.message || 'خطا');
            rolesPermissionsSchema = res.data;
            return res.data;
        });

    loadSchema.then(schema => {
        editingId = existing ? existing.id : null;
        modalTitle.textContent = existing ? `ویرایش نقش: ${existing.label}` : 'ساخت نقش جدید';

        const activePerms = new Set(existing ? (existing.permissions || []) : []);

        // گروه‌بندی مجوزها
        const groups = {};
        for (const [permKey, permMeta] of Object.entries(schema)) {
            const g = permMeta.group;
            if (!groups[g]) groups[g] = [];
            groups[g].push({ key: permKey, label: permMeta.label });
        }

        let groupsHtml = '';
        for (const [groupName, items] of Object.entries(groups)) {
            const allKeys = items.map(i => i.key);
            let checkboxesHtml = '';
            for (const item of items) {
                const checked = activePerms.has(item.key) ? 'checked' : '';
                checkboxesHtml += `<label class="roles-perm-item">
                    <input type="checkbox" name="permissions[]" value="${item.key}" ${checked}>
                    <span>${item.label}</span>
                </label>`;
            }
            groupsHtml += `<div class="roles-perm-group">
                <div class="roles-perm-group-header">
                    <strong>${groupName}</strong>
                    <button type="button" class="btn btn-outline roles-select-all-btn"
                        style="font-size:0.75em;padding:2px 8px;"
                        data-keys='${JSON.stringify(allKeys)}'>انتخاب همه</button>
                </div>
                <div class="roles-perm-items">${checkboxesHtml}</div>
            </div>`;
        }

        modalForm.innerHTML = `
            ${!existing ? `
            <div class="form-group">
                <label>نام (slug) <small style="color:#999;">— فقط حروف کوچک انگلیسی، عدد، خط‌تیره</small></label>
                <input type="text" name="name" placeholder="مثال: admin-mashhir" required
                    pattern="[a-z0-9\\-_]+" style="direction:ltr;">
            </div>
            ` : `
            <div class="form-group">
                <label>نام (slug)</label>
                <input type="text" value="${existing.name}" disabled
                    style="direction:ltr;background:#f5f5f5;color:#999;cursor:not-allowed;">
                <small style="color:#aaa;">نام نقش پس از ساخت قابل تغییر نیست</small>
            </div>
            `}
            <div class="form-group">
                <label>عنوان فارسی <span style="color:#e74c3c;">*</span></label>
                <input type="text" name="label" value="${existing ? existing.label : ''}"
                    placeholder="مثال: ادمین مشاهیر" required>
            </div>
            <div class="form-group">
                <label>توضیحات (اختیاری)</label>
                <textarea name="description" rows="2">${existing ? (existing.description || '') : ''}</textarea>
            </div>
            <div class="form-group">
                <label style="margin-bottom:10px;display:block;">
                    <strong>مجوزهای دسترسی</strong>
                    <small style="color:#999;display:block;margin-top:2px;">
                        تیک بزنید تا آن بخش در پنل این نقش نمایش داده شود
                    </small>
                </label>
                <div class="roles-perm-groups-wrap">${groupsHtml}</div>
            </div>
            <div class="modal-actions">
                <button type="submit" class="btn btn-primary">ذخیره</button>
                <button type="button" class="btn btn-outline" id="modalCancelBtn">انصراف</button>
            </div>
        `;

        modalForm.querySelectorAll('.roles-select-all-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const keys = JSON.parse(btn.dataset.keys);
                const cbs = [...modalForm.querySelectorAll('input[type="checkbox"][name="permissions[]"]')]
                    .filter(cb => keys.includes(cb.value));
                const allChecked = cbs.every(cb => cb.checked);
                cbs.forEach(cb => { cb.checked = !allChecked; });
                btn.textContent = allChecked ? 'انتخاب همه' : 'حذف انتخاب';
            });
        });

        document.getElementById('modalCancelBtn').addEventListener('click', closeModal);
        modalForm.onsubmit = (e) => { e.preventDefault(); saveRole(existing); };
        modalOverlay.classList.add('show');
    }).catch(() => showMessage('خطا در دریافت لیست مجوزها', 'error'));
}

function saveRole(existing) {
    const fd = new FormData(modalForm);

    if (existing) {
        fd.append('action', 'update');
        fd.append('id', existing.id);
    } else {
        fd.append('action', 'create');
    }

    fetch('api/roles_api.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                showMessage(existing ? 'نقش به‌روزرسانی شد' : 'نقش با موفقیت ساخته شد', 'success');
                closeModal();
                fetchRoles();
            } else {
                showMessage(res.message || 'خطا در ذخیره‌سازی', 'error');
            }
        })
        .catch(() => showMessage('خطا در ارتباط با سرور', 'error'));
}

// ===== صفحه در دست توسعه =====
function loadWipPanel(title) {
    panelTitle.textContent = title || 'در دست توسعه';
    panelBody.innerHTML = `
        <div class="welcome-box wip-box">
            <div class="wip-icon">🚧</div>
            <h2>این صفحه در دست توسعه است</h2>
            <p>بخش <strong>${title}</strong> در حال طراحی و پیاده‌سازی است و به زودی در دسترس قرار خواهد گرفت.</p>
        </div>
    `;
    // نمایش panelBody و مخفی کردن داشبورد کاربر
    const userSection = document.getElementById('userDashboardSection');
    if (userSection) userSection.style.display = 'none';
    panelBody.style.display = 'block';
}

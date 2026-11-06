(function() {
    'use strict';

    function switchLanguage(lang) {
        var currentPath = window.location.pathname;
        var pathParts = currentPath.split('/');
        var fileName = pathParts[pathParts.length - 1] || 'index.php';
        
        if (fileName.indexOf('?') !== -1) {
            fileName = fileName.split('?')[0];
        }
        
        var queryString = window.location.search;
        var basePath = '';
        var pathSegments = currentPath.split('/');
        var mashahirIndex = -1;
        for (var i = 0; i < pathSegments.length; i++) {
            if (pathSegments[i] === 'mashahir') {
                mashahirIndex = i;
                break;
            }
        }
        
        if (mashahirIndex !== -1) {
            var baseParts = pathSegments.slice(0, mashahirIndex + 1);
            basePath = baseParts.join('/') + '/';
        } else {            
            var pathWithoutFile = currentPath.substring(0, currentPath.lastIndexOf('/') + 1);
            basePath = pathWithoutFile;
        }
        
        var targetPath;
        if (lang === 'fa') {
            targetPath = basePath + 'persian/' + fileName + queryString;
        } else {
            targetPath = basePath + 'english/' + fileName + queryString;
        }
        
        localStorage.setItem('preferred_language', lang);
        sessionStorage.setItem('current_lang', lang);
        
        window.location.href = targetPath;
    }

    function getCurrentLanguage() {
        var path = window.location.pathname;
        
        if (path.indexOf('/persian/') !== -1) {
            return 'fa';
        } else if (path.indexOf('/english/') !== -1) {
            return 'en';
        }
        
        var saved = localStorage.getItem('preferred_language');
        if (saved) {
            return saved;
        }
        
        return 'en';
    }

    function createLanguageSwitcher() {
        var currentLang = getCurrentLanguage();
        var container = document.getElementById('langSwitcher');
        var isRTL = document.documentElement.getAttribute('dir') === 'rtl';
        
        if (!container) {
            var navbar = document.querySelector('.navbar-nav');
            if (navbar) {
                var li = document.createElement('li');
                li.className = 'nav-item';
                li.id = 'langSwitcher';
                navbar.appendChild(li);
                container = li;
            }
        }

        if (container) {
            var isFa = currentLang === 'fa';
            
            container.innerHTML = `
                <div class="lang-switcher-wrapper">
                    <div class="lang-toggle">
                        <input type="checkbox" id="langToggle" ${isFa ? 'checked' : ''} 
                               onchange="window.switchLanguage(this.checked ? 'fa' : 'en')">
                        <label for="langToggle" class="lang-toggle-label">
                            <span class="lang-option en">
                                <span class="flag">🇬🇧</span>
                                <span class="lang-name">EN</span>
                            </span>
                            <span class="lang-option fa">
                                <span class="flag">🇮🇷</span>
                                <span class="lang-name">FA</span>
                            </span>
                            <span class="lang-toggle-slider"></span>
                        </label>
                    </div>
                </div>
            `;

            var style = document.createElement('style');
            style.textContent = `
                .lang-switcher-wrapper {
                    display: flex;
                    align-items: center;
                    margin: 0 5px;
                }
                
                .lang-toggle {
                    position: relative;
                    display: inline-block;
                }
                
                .lang-toggle input {
                    display: none;
                }
                
                .lang-toggle-label {
                    display: flex;
                    align-items: center;
                    background: rgba(255, 255, 255, 0.1);
                    border-radius: 30px;
                    padding: 2px;
                    cursor: pointer;
                    position: relative;
                    min-width: 80px;
                    height: 28px;
                    border: 1px solid rgba(255, 255, 255, 0.1);
                    transition: all 0.3s ease;
                }
                
                .lang-toggle-label:hover {
                    background: rgba(255, 255, 255, 0.2);
                }
                
                .lang-option {
                    display: flex;
                    align-items: center;
                    gap: 3px;
                    padding: 2px 8px;
                    border-radius: 25px;
                    font-size: 0.6rem;
                    font-weight: 600;
                    z-index: 2;
                    transition: all 0.3s ease;
                    flex: 1;
                    justify-content: center;
                    color: rgba(255, 255, 255, 0.5);
                }
                
                .lang-option .flag {
                    font-size: 0.7rem;
                }
                
                .lang-option .lang-name {
                    font-size: 0.55rem;
                    letter-spacing: 0.3px;
                }
                
                .lang-toggle input:checked ~ .lang-toggle-label .lang-option.en {
                    color: rgba(255, 255, 255, 0.5);
                }
                
                .lang-toggle input:not(:checked) ~ .lang-toggle-label .lang-option.en {
                    color: #fff;
                }
                
                .lang-toggle input:checked ~ .lang-toggle-label .lang-option.fa {
                    color: #fff;
                }
                
                .lang-toggle input:not(:checked) ~ .lang-toggle-label .lang-option.fa {
                    color: rgba(255, 255, 255, 0.5);
                }
                
                .lang-toggle-slider {
                    position: absolute;
                    top: 2px;
                    width: calc(50% - 3px);
                    height: calc(100% - 4px);
                    background: #d4baa5;
                    border-radius: 25px;
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                    z-index: 1;
                    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
                }
                
                .lang-toggle-slider {
                    left: 2px;
                    right: auto;
                }
                
                .lang-toggle input:checked ~ .lang-toggle-label .lang-toggle-slider {
                    left: calc(50% + 1px);
                    right: auto;
                }
                
                body.rtl-lang .lang-toggle-slider {
                    left: auto;
                    right: 2px;
                }
                
                body.rtl-lang .lang-toggle input:checked ~ .lang-toggle-label .lang-toggle-slider {
                    left: auto;
                    right: calc(50% + 1px);
                }
                
                @media (max-width: 768px) {
                    .lang-toggle-label {
                        min-width: 65px;
                        height: 24px;
                    }
                    .lang-option {
                        padding: 1px 5px;
                        font-size: 0.5rem;
                    }
                    .lang-option .flag {
                        font-size: 0.6rem;
                    }
                    .lang-option .lang-name {
                        font-size: 0.45rem;
                    }
                    .lang-toggle-slider {
                        top: 2px;
                        height: calc(100% - 4px);
                        width: calc(50% - 3px);
                    }
                    .lang-toggle-slider {
                        left: 2px;
                        right: auto;
                    }
                    .lang-toggle input:checked ~ .lang-toggle-label .lang-toggle-slider {
                        left: calc(50% + 1px);
                        right: auto;
                    }
                    body.rtl-lang .lang-toggle-slider {
                        left: auto;
                        right: 2px;
                    }
                    body.rtl-lang .lang-toggle input:checked ~ .lang-toggle-label .lang-toggle-slider {
                        left: auto;
                        right: calc(50% + 1px);
                    }
                }

                @media (max-width: 576px) {
                    .lang-toggle-label {
                        min-width: 55px;
                        height: 22px;
                    }
                    .lang-option {
                        padding: 1px 3px;
                        font-size: 0.45rem;
                    }
                    .lang-option .flag {
                        font-size: 0.5rem;
                    }
                    .lang-option .lang-name {
                        font-size: 0.4rem;
                    }
                    .lang-toggle-slider {
                        top: 1px;
                        height: calc(100% - 2px);
                        width: calc(50% - 2px);
                    }
                    .lang-toggle-slider {
                        left: 1px;
                        right: auto;
                    }
                    .lang-toggle input:checked ~ .lang-toggle-label .lang-toggle-slider {
                        left: calc(50% + 1px);
                        right: auto;
                    }
                    body.rtl-lang .lang-toggle-slider {
                        left: auto;
                        right: 1px;
                    }
                    body.rtl-lang .lang-toggle input:checked ~ .lang-toggle-label .lang-toggle-slider {
                        left: auto;
                        right: calc(50% + 1px);
                    }
                }
            `;
            document.head.appendChild(style);

            if (document.documentElement.getAttribute('dir') === 'rtl') {
                document.body.classList.add('rtl-lang');
            }
        }
    }

    window.switchLanguage = switchLanguage;
    window.getCurrentLanguage = getCurrentLanguage;

    document.addEventListener('DOMContentLoaded', function() {
        createLanguageSwitcher();
        console.log('Language switcher initialized. Current language:', getCurrentLanguage());
    });

})();
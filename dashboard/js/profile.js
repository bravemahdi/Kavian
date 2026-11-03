// مدیریت داشبورد کاربر

// URL ثابت برای update API — مسیر مطلق از root سرور
const UPDATE_URL = '/Kavian/app/profile/update.php';

document.addEventListener('DOMContentLoaded', function() {
    setupTabNavigation();
    setupProfileForm();
    setupPasswordForm();
    setupOrdersFilter();
});

// مدیریت تب‌های ناوبری
function setupTabNavigation() {
    const navTabs = document.querySelectorAll('.dashboard-tab');
    const tabs = document.querySelectorAll('.dashboard-tab-content');
    
    navTabs.forEach(tab => {
        if (tab.classList.contains('logout')) return;
        
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            
            navTabs.forEach(t => t.classList.remove('active'));
            tabs.forEach(t => t.classList.remove('active'));
            
            this.classList.add('active');
            
            const tabId = this.dataset.tab;
            const targetTab = document.getElementById(tabId);
            if (targetTab) {
                targetTab.classList.add('active');
                window.scrollTo({ top: targetTab.offsetTop - 100, behavior: 'smooth' });
            }
        });
    });
}

// تابع کمکی برای ارسال fetch و parse JSON
async function postForm(formData) {
    const response = await fetch(UPDATE_URL, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    
    const responseText = await response.text();
    console.log('Response status:', response.status);
    console.log('Response text:', responseText);
    
    let result;
    try {
        result = JSON.parse(responseText);
    } catch (e) {
        console.error('JSON parse failed. Raw response:', responseText);
        throw new Error('پاسخ سرور نامعتبر است (کد ' + response.status + ')');
    }
    return result;
}

// مدیریت فرم ویرایش پروفایل
function setupProfileForm() {
    const form = document.getElementById('editProfileForm');
    if (!form) return;
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (!validateProfileForm(this)) return;
        
        showLoading(this);
        
        try {
            const formData = new FormData(this);
            const result = await postForm(formData);
            
            if (result.success) {
                showNotification('تغییرات با موفقیت ذخیره شد', 'success');
                const avatarFile = formData.get('avatar');
                if (avatarFile && avatarFile.size > 0) {
                    setTimeout(() => window.location.reload(), 1500);
                }
            } else {
                showNotification(result.message || 'خطا در ذخیره تغییرات', 'error');
            }
        } catch (error) {
            showNotification('خطا در ارتباط با سرور: ' + error.message, 'error');
            console.error('Error:', error);
        } finally {
            hideLoading(this);
        }
    });
    
    // پیش‌نمایش عکس آپلود شده
    const avatarInput = document.getElementById('avatar');
    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            
            if (!validateImageFile(file)) {
                this.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const avatarPreview = document.querySelector('.avatar-preview-dashboard img');
                if (avatarPreview) {
                    avatarPreview.src = e.target.result;
                } else {
                    const previewContainer = document.querySelector('.avatar-preview-dashboard');
                    if (previewContainer) {
                        previewContainer.innerHTML = `<img src="${e.target.result}" alt="آواتار جدید">`;
                    }
                }
            };
            reader.readAsDataURL(file);
        });
    }
}

// اعتبارسنجی فرم ویرایش پروفایل
function validateProfileForm(form) {
    const name  = form.name.value;
    const email = form.email.value;
    const phone = form.phone.value;
    
    if (!name || name.length < 2) {
        showNotification('نام باید حداقل ۲ کاراکتر داشته باشد', 'error');
        return false;
    }
    if (!email || !isValidEmail(email)) {
        showNotification('لطفاً یک ایمیل معتبر وارد کنید', 'error');
        return false;
    }
    if (phone && !isValidPhone(phone)) {
        showNotification('لطفاً یک شماره تلفن معتبر وارد کنید', 'error');
        return false;
    }
    return true;
}

// مدیریت فرم تغییر رمز عبور
function setupPasswordForm() {
    const form = document.getElementById('changePasswordForm');
    if (!form) return;
    
    setupPasswordStrength();
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (!validatePasswordForm(this)) return;
        
        showLoading(this);
        
        try {
            const formData = new FormData(this);
            const result = await postForm(formData);
            
            if (result.success) {
                showNotification('رمز عبور با موفقیت تغییر کرد', 'success');
                this.reset();
                
                const strengthBar   = document.getElementById('passwordStrength');
                const strengthLabel = document.getElementById('strengthLabel');
                const passwordTips  = document.getElementById('passwordTips');
                
                if (strengthBar)   { strengthBar.style.width = '0%'; strengthBar.style.backgroundColor = '#F44336'; }
                if (strengthLabel) { strengthLabel.textContent = 'ضعیف'; strengthLabel.style.color = '#F44336'; }
                if (passwordTips)  { passwordTips.textContent = 'برای رمز عبور قوی از ترکیب حروف بزرگ و کوچک، اعداد و نمادها استفاده کنید'; passwordTips.style.color = '#5c483a'; }
            } else {
                showNotification(result.message || 'خطا در تغییر رمز عبور', 'error');
            }
        } catch (error) {
            showNotification('خطا در ارتباط با سرور: ' + error.message, 'error');
            console.error('Error:', error);
        } finally {
            hideLoading(this);
        }
    });
}

// تنظیم قدرت رمز عبور
function setupPasswordStrength() {
    const passwordInput = document.getElementById('new_password');
    const strengthBar   = document.getElementById('passwordStrength');
    const strengthLabel = document.getElementById('strengthLabel');
    const passwordTips  = document.getElementById('passwordTips');
    
    if (!passwordInput || !strengthBar) return;
    
    passwordInput.addEventListener('input', function() {
        const strength = calculatePasswordStrength(this.value);
        strengthBar.style.width = strength.percentage + '%';
        strengthBar.style.backgroundColor = strength.color;
        if (strengthLabel) { strengthLabel.textContent = strength.label; strengthLabel.style.color = strength.color; }
        if (passwordTips)  { passwordTips.textContent = strength.tips;   passwordTips.style.color  = strength.color; }
    });
}

// محاسبه قدرت رمز عبور
function calculatePasswordStrength(password) {
    if (!password) return { percentage: 0, color: '#F44336', label: 'ضعیف', tips: 'لطفاً رمز عبور را وارد کنید' };
    
    let score = 0;
    if (password.length >= 6)  score += 25;
    if (password.length >= 8)  score += 15;
    if (password.length >= 12) score += 10;
    if (/[a-z]/.test(password))        score += 10;
    if (/[A-Z]/.test(password))        score += 15;
    if (/[0-9]/.test(password))        score += 15;
    if (/[^a-zA-Z0-9]/.test(password)) score += 20;
    score = Math.min(score, 100);
    
    if (score < 40)  return { percentage: score, color: '#F44336', label: 'ضعیف',      tips: 'رمز عبور ضعیف است. از حروف، اعداد و نمادها استفاده کنید' };
    if (score < 70)  return { percentage: score, color: '#FF9800', label: 'متوسط',     tips: 'رمز عبور متوسط است. از حروف بزرگ و کوچک استفاده کنید' };
    if (score < 90)  return { percentage: score, color: '#4CAF50', label: 'قوی',       tips: 'رمز عبور قوی است' };
    return             { percentage: score, color: '#00796B', label: 'بسیار قوی', tips: 'رمز عبور بسیار قوی است' };
}

// اعتبارسنجی فرم تغییر رمز عبور
function validatePasswordForm(form) {
    const currentPassword = form.current_password.value;
    const newPassword     = form.new_password.value;
    const confirmPassword = form.confirm_password.value;
    
    if (!currentPassword || currentPassword.length < 6) {
        showNotification('لطفاً رمز عبور فعلی را وارد کنید (حداقل ۶ کاراکتر)', 'error');
        return false;
    }
    if (!newPassword || newPassword.length < 6) {
        showNotification('رمز عبور جدید باید حداقل ۶ کاراکتر داشته باشد', 'error');
        return false;
    }
    if (newPassword !== confirmPassword) {
        showNotification('رمز عبور جدید و تکرار آن مطابقت ندارند', 'error');
        return false;
    }
    if (currentPassword === newPassword) {
        showNotification('رمز عبور جدید نباید با رمز عبور فعلی یکسان باشد', 'error');
        return false;
    }
    return true;
}

// مدیریت فیلتر سفارش‌ها
function setupOrdersFilter() {
    const statusFilter = document.getElementById('orderStatusFilter');
    const dateFilter   = document.getElementById('orderDateFilter');
    
    if (statusFilter) statusFilter.addEventListener('change', function() { filterOrders(this.value, dateFilter ? dateFilter.value : 'all'); });
    if (dateFilter)   dateFilter.addEventListener('change',   function() { filterOrders(statusFilter ? statusFilter.value : 'all', this.value); });
}

function filterOrders(status, date) {
    console.log(`Filtering orders - Status: ${status}, Date: ${date}`);
    if (status !== 'all' || date !== 'all') {
        showNotification(`فیلترها اعمال شدند`, 'info');
    }
}

// اعتبارسنجی فایل عکس
function validateImageFile(file) {
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!allowedTypes.includes(file.type)) {
        showNotification('فرمت فایل مجاز نیست. فقط JPG, PNG, GIF, WebP قابل قبول است', 'error');
        return false;
    }
    if (file.size > 2 * 1024 * 1024) {
        showNotification('حجم فایل نباید بیشتر از ۲ مگابایت باشد', 'error');
        return false;
    }
    return true;
}

function isValidEmail(email) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email); }
function isValidPhone(phone)  { return /^09[0-9]{9}$/.test(phone); }

// نمایش نوتیفیکیشن
function showNotification(message, type = 'info') {
    // حذف همه پیام‌های قبلی قبل از نمایش پیام جدید
    document.querySelectorAll('.dashboard-notification').forEach(n => n.remove());

    const colors = { success: '#4CAF50', error: '#F44336', warning: '#FF9800', info: '#2196F3' };
    const icons  = { success: 'check-circle', error: 'exclamation-circle', warning: 'exclamation-triangle', info: 'info-circle' };
    
    const notification = document.createElement('div');
    notification.className = 'dashboard-notification';
    notification.setAttribute('role', 'alert');
    notification.innerHTML = `
        <i class="fas fa-${icons[type] || icons.info}"></i>
        <span>${message}</span>
        <button class="notification-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
    `;
    notification.style.cssText = `
        position:fixed; top:20px; right:20px; background:${colors[type] || colors.info};
        color:white; padding:15px 20px; border-radius:10px; display:flex; align-items:center;
        gap:12px; z-index:9999; box-shadow:0 4px 20px rgba(0,0,0,0.2);
        animation:dashboard-notification-slideIn 0.3s ease forwards;
        max-width:400px; min-width:300px; font-family:'Vazirmatn',system-ui,sans-serif;
    `;
    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.style.cssText = 'background:none;border:none;color:white;cursor:pointer;opacity:0.8;margin-right:auto;padding:4px;border-radius:4px;';
    
    document.body.appendChild(notification);
    setTimeout(() => {
        if (notification.parentNode) {
            notification.style.animation = 'dashboard-notification-slideOut 0.3s ease forwards';
            setTimeout(() => notification.parentNode && notification.parentNode.removeChild(notification), 300);
        }
    }, 5000);
}

function showLoading(form) {
    const btn = form.querySelector('button[type="submit"]');
    if (!btn) return;
    btn.dataset.originalText    = btn.innerHTML;
    btn.dataset.originalClasses = btn.className;
    btn.innerHTML  = '<i class="fas fa-spinner fa-spin"></i> در حال پردازش...';
    btn.className += ' loading';
    btn.disabled   = true;
}

function hideLoading(form) {
    const btn = form.querySelector('button[type="submit"]');
    if (btn && btn.dataset.originalText) {
        btn.innerHTML = btn.dataset.originalText;
        btn.className = btn.dataset.originalClasses;
        btn.disabled  = false;
    }
}

// انیمیشن نوتیفیکیشن
const notificationStyles = document.createElement('style');
notificationStyles.textContent = `
    @keyframes dashboard-notification-slideIn  { from { transform:translateX(100%); opacity:0; } to { transform:translateX(0); opacity:1; } }
    @keyframes dashboard-notification-slideOut { from { transform:translateX(0);    opacity:1; } to { transform:translateX(100%); opacity:0; } }
`;
document.head.appendChild(notificationStyles);

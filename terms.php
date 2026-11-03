<?php
$pageTitle = 'شرایط و قوانین | میراث ایران';
include 'views/header.php';
?>

<section class="terms-section">
    <div class="container">
        <div class="terms-header">
            <h1><i class="fas fa-file-contract"></i> شرایط و قوانین استفاده</h1>
            <p>قبل از ثبت‌نام و استفاده از خدمات سایت، لطفاً شرایط و قوانین زیر را با دقت مطالعه کنید</p>
        </div>
        
        <div class="terms-content">
            <div class="terms-card">
                <h2><i class="fas fa-user-check"></i> شرایط عضویت</h2>
                <ul>
                    <li>عضویت در سایت برای تمامی افراد بالای ۱۳ سال آزاد است</li>
                    <li>کاربر موظف است اطلاعات صحیح و کامل خود را در فرم ثبت‌نام وارد کند</li>
                    <li>هر کاربر تنها می‌تواند یک حساب کاربری داشته باشد</li>
                    <li>حساب کاربری غیرقابل انتقال است و تنها برای استفاده شخصی صاحب حساب می‌باشد</li>
                </ul>
            </div>
            
            <div class="terms-card">
                <h2><i class="fas fa-lock"></i> حریم خصوصی و امنیت</h2>
                <ul>
                    <li>اطلاعات شخصی کاربران محرمانه تلقی شده و در اختیار اشخاص ثالث قرار نمی‌گیرد</li>
                    <li>کاربر مسئول حفظ محرمانه‌بودن رمز عبور خود می‌باشد</li>
                    <li>هرگونه فعالیت غیرمجاز با حساب کاربری، مسئولیت آن بر عهده صاحب حساب است</li>
                    <li>سایت از آخرین استانداردهای امنیتی برای حفاظت از اطلاعات کاربران استفاده می‌کند</li>
                </ul>
            </div>
            
            <div class="terms-card">
                <h2><i class="fas fa-shopping-cart"></i> قوانین خرید</h2>
                <ul>
                    <li>قیمت کالاها و خدمات به ریال اعلام شده است</li>
                    <li>پس از ثبت سفارش، امکان لغو آن در صورت عدم پرداخت وجود دارد</li>
                    <li>در صورت بروز هرگونه مشکل در سفارش، تیم پشتیبانی پیگیری خواهد کرد</li>
                    <li>حق تغییر قیمت‌ها با توجه به نوسانات بازار برای سایت محفوظ است</li>
                </ul>
            </div>
            
            <div class="terms-card">
                <h2><i class="fas fa-ban"></i> محدودیت‌ها و ممنوعیت‌ها</h2>
                <ul>
                    <li>استفاده از سایت برای اهداف غیرقانونی ممنوع است</li>
                    <li>انتشار محتوای توهین‌آمیز، نژادپرستانه یا غیراخلاقی ممنوع است</li>
                    <li>هرگونه تلاش برای هک یا نفوذ به سیستم سایت ممنوع است</li>
                    <li>استفاده از ربات یا نرم‌افزارهای خودکار برای فعالیت در سایت ممنوع است</li>
                </ul>
            </div>
            
            <div class="terms-card">
                <h2><i class="fas fa-balance-scale"></i> مسئولیت‌ها</h2>
                <ul>
                    <li>سایت مسئولیتی در قبال خسارات ناشی از استفاده نادرست از خدمات ندارد</li>
                    <li>مسئولیت حفظ و نگهداری حساب کاربری بر عهده کاربر است</li>
                    <li>سایت حق تغییر شرایط و قوانین را با اطلاع‌رسانی قبلی دارد</li>
                    <li>در صورت مشاهده تخلف، حساب کاربری متخلف مسدود خواهد شد</li>
                </ul>
            </div>
            
            <div class="terms-acceptance">
                <p>با ثبت‌نام در سایت، تمامی شرایط و قوانین فوق را می‌پذیرید</p>
                <a href="/Kavian/login.php?tab=signup" class="btn-primary">
                    <i class="fas fa-user-plus"></i> بازگشت به ثبت‌نام
                </a>
                <a href="/Kavian/" class="btn-secondary">
                    <i class="fas fa-home"></i> بازگشت به صفحه اصلی
                </a>
            </div>
        </div>
    </div>
</section>

<style>
:root {
    --primary-color: #00796B;
    --primary-dark: #004D40;
    --primary-light: #26A69A;
    --accent-light: #E8D3A8;
    --accent-lighter: #F6EAD0;
    --text-dark: #2d1f14;
    --text-medium: #3d2e22;
    --text-light: #5c483a;
    --bg-white: #ffffff;
    --shadow-md: 0 8px 30px rgba(0, 0, 0, 0.07);
    --shadow-lg: 0 12px 40px rgba(0, 0, 0, 0.1);
}

.terms-section {
    padding: 80px 0 60px;
    background: linear-gradient(135deg, #F6EAD0 0%, #E8D3A8 100%);
    min-height: calc(100vh - 150px);
}

.terms-section .container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 20px;
}

.terms-header {
    text-align: center;
    margin-bottom: 50px;
    color: var(--text-dark);
}

.terms-header h1 {
    font-size: 2.5rem;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
}

.terms-header p {
    font-size: 1.1rem;
    color: var(--text-light);
    max-width: 700px;
    margin: 0 auto;
    line-height: 1.6;
}

.terms-content {
    display: grid;
    gap: 20px;
}

.terms-card {
    background: var(--bg-white);
    border-radius: 15px;
    padding: 30px;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--accent-light);
    transition: all 0.3s ease;
}

.terms-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary-color);
}

.terms-card h2 {
    color: var(--primary-color);
    font-size: 1.4rem;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.terms-card ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.terms-card li {
    padding: 10px 0 10px 25px;
    position: relative;
    color: var(--text-medium);
    line-height: 1.6;
    font-size: 0.95rem;
}

.terms-card li::before {
    content: "✓";
    position: absolute;
    left: 0;
    color: var(--primary-color);
    font-weight: bold;
}

.terms-acceptance {
    text-align: center;
    background: var(--bg-white);
    border-radius: 15px;
    padding: 40px;
    margin-top: 40px;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--accent-light);
    grid-column: 1 / -1;
}

.terms-acceptance p {
    font-size: 1.2rem;
    color: var(--text-dark);
    margin-bottom: 30px;
    font-weight: 600;
}

.btn-primary, .btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 15px 30px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    margin: 0 10px;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
}

.btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0, 121, 107, 0.3);
}

.btn-secondary {
    background: var(--accent-lighter);
    color: var(--text-dark);
    border: 1px solid var(--accent-light);
}

.btn-secondary:hover {
    background: var(--accent-light);
    transform: translateY(-2px);
}

/* ============================================
   طراحی واکنش‌گرا - تبلت (768px تا 1024px)
   ============================================ */
@media (max-width: 1024px) {
    .terms-section {
        padding: 60px 0 40px;
    }
    
    .terms-header h1 {
        font-size: 2.2rem;
    }
    
    .terms-header p {
        font-size: 1rem;
    }
    
    .terms-card {
        padding: 25px;
    }
    
    .terms-card h2 {
        font-size: 1.3rem;
    }
    
    .terms-card li {
        font-size: 0.9rem;
    }
}

/* ============================================
   طراحی واکنش‌گرا - موبایل بزرگ (576px تا 768px)
   ============================================ */
@media (max-width: 768px) {
    .terms-section {
        padding: 40px 0 30px;
    }
    
    .terms-section .container {
        padding: 0 15px;
    }
    
    .terms-header {
        margin-bottom: 40px;
    }
    
    .terms-header h1 {
        font-size: 1.8rem;
        flex-direction: column;
        gap: 10px;
    }
    
    .terms-header p {
        font-size: 0.95rem;
        padding: 0 10px;
    }
    
    .terms-content {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .terms-card {
        padding: 20px;
        margin-bottom: 0;
    }
    
    .terms-card h2 {
        font-size: 1.2rem;
        margin-bottom: 15px;
    }
    
    .terms-card li {
        padding: 8px 0 8px 20px;
        font-size: 0.875rem;
    }
    
    .terms-acceptance {
        padding: 30px 20px;
        margin-top: 30px;
    }
    
    .terms-acceptance p {
        font-size: 1.1rem;
        margin-bottom: 25px;
    }
    
    .btn-primary, .btn-secondary {
        display: block;
        margin: 10px 0;
        text-align: center;
        justify-content: center;
        width: 100%;
        max-width: 300px;
        margin-left: auto;
        margin-right: auto;
    }
}

/* ============================================
   طراحی واکنش‌گرا - موبایل کوچک (تا 576px)
   ============================================ */
@media (max-width: 576px) {
    .terms-section {
        padding: 30px 0 20px;
        min-height: calc(100vh - 100px);
    }
    
    .terms-header {
        margin-bottom: 30px;
    }
    
    .terms-header h1 {
        font-size: 1.6rem;
    }
    
    .terms-header p {
        font-size: 0.9rem;
    }
    
    .terms-card {
        padding: 15px;
    }
    
    .terms-card h2 {
        font-size: 1.1rem;
        gap: 8px;
    }
    
    .terms-card h2 i {
        font-size: 1.2rem;
    }
    
    .terms-card li {
        font-size: 0.85rem;
        padding: 6px 0 6px 18px;
    }
    
    .terms-acceptance {
        padding: 25px 15px;
        margin-top: 25px;
    }
    
    .terms-acceptance p {
        font-size: 1rem;
        margin-bottom: 20px;
    }
    
    .btn-primary, .btn-secondary {
        padding: 12px 20px;
        font-size: 0.95rem;
    }
}

/* ============================================
   طراحی واکنش‌گرا - موبایل بسیار کوچک (تا 375px)
   ============================================ */
@media (max-width: 375px) {
    .terms-header h1 {
        font-size: 1.4rem;
    }
    
    .terms-header p {
        font-size: 0.85rem;
    }
    
    .terms-card {
        padding: 12px;
    }
    
    .terms-card h2 {
        font-size: 1rem;
    }
    
    .terms-card li {
        font-size: 0.8rem;
    }
    
    .terms-acceptance p {
        font-size: 0.95rem;
    }
    
    .btn-primary, .btn-secondary {
        padding: 10px 15px;
        font-size: 0.9rem;
    }
}

/* ============================================
   حالت لنداسکیپ موبایل
   ============================================ */
@media (max-height: 600px) and (orientation: landscape) {
    .terms-section {
        padding: 40px 0 30px;
        min-height: auto;
    }
    
    .terms-content {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    
    .terms-card {
        margin-bottom: 0;
        height: 100%;
        overflow-y: auto;
    }
    
    .terms-acceptance {
        grid-column: 1 / -1;
        margin-top: 20px;
        padding: 20px;
    }
    
    .terms-acceptance p {
        margin-bottom: 15px;
    }
}
</style>

<?php include 'views/footer.php'; ?>
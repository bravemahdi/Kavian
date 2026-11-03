// lantern animation برای کاربر لاگین — header.php وضعیت را از PHP session می‌خواند
document.addEventListener('DOMContentLoaded', function () {
    var lanternIcon = document.getElementById('lanternIcon');
    if (!lanternIcon) return;

    // اگر فیلتر از قبل توسط PHP ست شده (کاربر لاگین است)، انیمیشن را اضافه کن
    if (lanternIcon.style.filter && lanternIcon.style.filter !== 'none') {
        lanternIcon.style.animation = 'lanternGlow 1s ease-in-out infinite alternate';
        lanternIcon.classList.add('active');
    }
});

fetch('/Kavian/app/auth/check_login.php')
    .then(res => res.json())
    .then(data => {
        if (!data.logged_in) {
            window.location.href = 'login.php';
            return;
        }

        document.getElementById('profileName').textContent  = data.user.name;
        document.getElementById('profileEmail').textContent = data.user.email;
        document.getElementById('infoName').textContent     = data.user.name;
        document.getElementById('infoEmail').textContent    = data.user.email;

        const roleLabel = data.user.role === 'admin' ? 'مدیر' : 'کاربر عادی';
        document.getElementById('infoRole').textContent    = roleLabel;
        document.getElementById('profileRole').textContent = roleLabel;

        const lantern = document.getElementById('lanternIcon');
        if (lantern) {
            lantern.style.filter    = 'drop-shadow(0 0 30px #FFD700) brightness(1.5)';
            lantern.style.animation = 'lanternGlow 1s ease-in-out infinite alternate';
        }

        fetch('/Kavian/app/user/get_user.php')
            .then(res => res.json())
            .then(data => {
                if (data.success && data.user.created_at) {
                    const date = new Date(data.user.created_at);
                    document.getElementById('infoDate').textContent = date.toLocaleDateString('fa-IR');
                }
            });
    });

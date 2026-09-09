document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle
    const sidebar = document.querySelector('.sidebar');
    const sidebarOverlay = document.querySelector('.sidebar-overlay');
    const toggleBtn = document.querySelector('#sidebarToggle');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            if (sidebarOverlay) sidebarOverlay.classList.toggle('show');
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
        });
    }

    // Amount formatting
    const amountInputs = document.querySelectorAll('.amount-input');
    amountInputs.forEach(function(input) {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^0-9]/g, '');
            if (value) {
                e.target.value = parseInt(value).toLocaleString('id-ID');
            }
        });

        // On form submit, ensure raw number is sent
        const form = input.closest('form');
        if (form) {
            form.addEventListener('submit', function() {
                input.value = input.value.replace(/[^0-9]/g, '');
            });
        }
    });

    // PIN show/hide toggle
    document.querySelectorAll('.toggle-pin').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const target = document.querySelector(this.dataset.target);
            if (target) {
                const isPassword = target.type === 'password';
                target.type = isPassword ? 'text' : 'password';
                const icon = this.querySelector('i');
                if (icon) {
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                }
            }
        });
    });

    // Period filter active state
    const urlParams = new URLSearchParams(window.location.search);
    const currentPeriod = urlParams.get('period') || 'month';
    document.querySelectorAll('.period-filter .btn').forEach(function(btn) {
        const period = btn.dataset.period;
        if (period === currentPeriod) {
            btn.classList.add('active');
        }
    });

    // SweetAlert2 delete confirmation
    document.querySelectorAll('.btn-delete').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: 'Data yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#E55812',
                cancelButtonColor: '#002626',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // SweetAlert2 logout confirmation
    document.querySelectorAll('.btn-logout-confirm').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            Swal.fire({
                title: 'Keluar dari akun?',
                text: 'Anda akan keluar dari sesi ini.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0E4749',
                cancelButtonColor: '#002626',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Flash messages via SweetAlert2
    const successMsg = document.querySelector('meta[name="flash-success"]');
    const errorMsg = document.querySelector('meta[name="flash-error"]');
    const warningMsg = document.querySelector('meta[name="flash-warning"]');

    if (successMsg && successMsg.content) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: successMsg.content,
            timer: 3000,
            showConfirmButton: false
        });
    }
    if (errorMsg && errorMsg.content) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: errorMsg.content
        });
    }
    if (warningMsg && warningMsg.content) {
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: warningMsg.content
        });
    }
});

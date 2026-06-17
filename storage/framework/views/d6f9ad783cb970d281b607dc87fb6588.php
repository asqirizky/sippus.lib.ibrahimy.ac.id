<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Admin Absensi - Perpustakaan Ibrahimy</title>
  <link rel="shortcut icon" href="admin/assets/media/logos/logo perpus icon.png" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #d9efff, #eef7ff);
      overflow: hidden;
      position: relative;
    }

    /* ===== ABSTRAK BACKGROUND (LEBIH TAJAM, ANIMASI HALUS) ===== */
    .bg-shape {
      position: absolute;
      opacity: 0.85;
      filter: blur(3px); /* blur ringan saja */
      animation: drift 18s infinite ease-in-out;
    }

    .shape1 {
      width: 420px;
      height: 420px;
      background: linear-gradient(135deg, #284a68, #4fa3ff);
      border-radius: 42% 58% 63% 37% / 55% 45% 55% 45%;
      top: -80px;
      left: -80px;
    }

    .shape2 {
      width: 340px;
      height: 340px;
      background: linear-gradient(135deg, #b8e0ff, #8fc9ff);
      border-radius: 60% 40% 48% 52% / 45% 55% 45% 55%;
      bottom: -100px;
      right: -90px;
      animation-delay: 4s;
    }

    .shape3 {
      width: 260px;
      height: 260px;
      background: linear-gradient(135deg, #9fd3ff, #cfe9ff);
      border-radius: 68% 32% 40% 60% / 60% 40% 60% 40%;
      top: 18%;
      right: 12%;
      animation-delay: 2s;
    }

    .shape4 {
      width: 200px;
      height: 200px;
      background: linear-gradient(135deg, #5faeff, #9fd3ff);
      border-radius: 38% 62% 55% 45% / 55% 45% 62% 38%;
      bottom: 22%;
      left: 10%;
      animation-delay: 6s;
    }

    /* garis aksen abstrak */


    @keyframes drift {
      0%, 100% { transform: translate(0, 0); }
      50% { transform: translate(18px, 24px); }
    }

    @keyframes slide {
      0% { transform: translateX(-120px) rotate(-4deg); }
      100% { transform: translateX(120px) rotate(-4deg); }
    }

    /* ===== LOGIN CARD ===== */
    .login-card {
        position: relative;
        width: 370px;
        background: rgba(255, 255, 255, 0.92);
        border-radius: 28px;
        padding: 42px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        z-index: 2;

        /* efek glass */
        backdrop-filter: blur(30px);
        -webkit-backdrop-filter: blur(30px);

        /* soft shadow */
        box-shadow:
            0 20px 40px rgba(0, 0, 0, 0.35),
            inset 0 1px 0 rgba(255, 255, 255, 0.6);

        border: 1px solid rgba(255, 255, 255, 0.4);
    }

    .login-card h2 {
        text-align: center;
        margin-bottom: 24px;
        color: #2b6cb0;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        display: block;
        font-size: 14px;
        margin-bottom: 6px;
        color: #555;
    }

    .form-group input {
        width: 100%;
        padding: 12px 14px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        outline: none;
        transition: border 0.3s, box-shadow 0.3s;
    }

    .form-group input:focus {
        border-color: #63b3ed;
        box-shadow: 0 0 0 3px rgba(99, 179, 237, 0.25);
    }

    .btn-login {
        width: 100%;
        margin-top: 8px;
        padding: 12px;
        border: none;
        border-radius: 10px;
        background: linear-gradient(135deg, #63b3ed, #4299e1);
        color: #fff;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(66, 153, 225, 0.4);
    }

    .footer-text {
        margin-top: 20px;
        text-align: center;
        font-size: 13px;
        color: #777;
    }
  </style>
</head>
<body>

    <!-- ABSTRAK BACKGROUND -->
    <div class="bg-shape shape1"></div>
    <div class="bg-shape shape2"></div>
    <div class="bg-shape shape3"></div>
    <div class="bg-shape shape4"></div>

    <div class="login-card">
        <form action="<?php echo e(route('login.post')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <h2>Login</h2>
        <div class="form-group">
            <label>Username</label>
            <input type="text" placeholder="Username" name="username" class="form-control form-control-lg <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required autocomplete="username" autofocus />

            <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span class="invalid-feedback">
                    <strong><?php echo e($message); ?></strong>
                </span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" placeholder="Password" class="form-control form-control-lg <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password" id="password" required autocomplete="current-password" />

            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="invalid-feedback">
                <strong><?php echo e($message); ?></strong>
            </span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <button type="submit" class="btn-login">Masuk</button>
        </form>
        <div class="footer-text">© 2026 create by AsqiRizky</div>
    </div>
     <?php if(session('error')): ?>
        <script>
            Swal.fire({
                title: 'Astaghfirullah!',
                text: '<?php echo e(session('error')); ?>',
                icon: 'error',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });
        </script>
    <?php endif; ?>
</body>
</html>
<?php /**PATH /var/www/vhosts/sippus.lib.ibrahimy.ac.id/html/resources/views/auth/login.blade.php ENDPATH**/ ?>
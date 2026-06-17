<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Absensi Pustakawan Struktural - Perpustakaan Ibrahimy</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
    }

    /* ANIMASI MASUK CARD */
    @keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(40px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
    }

    /* ANIMASI INPUT */
    @keyframes pulseGlow {
    0% { box-shadow: 0 0 0px rgba(59,130,246,0.0); }
    50% { box-shadow: 0 0 12px rgba(59,130,246,0.5); }
    100% { box-shadow: 0 0 0px rgba(59,130,246,0.0); }
    }

    /* BACKGROUND GERAK HALUS */
    @keyframes bgMove {
    0% { background-position: center top; }
    100% { background-position: center bottom; }
    }

    .animate-card {
        animation: fadeInUp 0.8s ease;
    }

    body {
    animation: bgMove 10s ease-in-out infinite alternate;
    }
  </style>
</head>

<body class="relative flex items-center justify-center min-h-screen">

  <!-- Overlay warna biru elegan -->
  <div class="absolute inset-0 backdrop-blur-sm" style="background-color: #7239EA"></div>

  <!-- Form Card -->
  <div class="relative z-10 w-full max-w-md p-10 text-center shadow-2xl bg-white/10 backdrop-blur-md rounded-2xl animate-card">
    <!-- Logo -->
    <h1 class="flex items-center justify-center gap-2 mb-8 text-3xl font-bold text-white">
      <span class="px-2 py-1 text-blue-700 bg-white rounded">lib</span>
      <span>Ibrahimy</span>
    </h1>

    <!-- Form -->
    <form class="form" method="POST" action="{{ route('absen-viar-proses') }}">
      @csrf
      <!-- Input NIK -->
      <div>
        <input type="text" name="nik" placeholder="Absent Here" required
        class="w-full px-4 py-3 text-center rounded-lg bg-white/90 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:animate-[pulseGlow_0.6s_ease]" autofocus/>
      </div>

      <!-- Button -->
      <div class="flex justify-center mt-4">
        <button type="submit"
            class="px-6 py-2 font-semibold text-blue-700 transition duration-300 bg-white rounded-lg hover:bg-blue-100 hover:scale-105 active:scale-95">Submit
        </button>
      </div>
    </form>

    <!-- Footer -->
    <p class="mt-8 text-xs text-white/80">
      ©2026 AsqiRizky-Librarian Developer
    </p>
  </div>

  <!-- SweetAlert Notification -->
  @if (session('success'))
    <script>
      Swal.fire({
        title: 'Alhamdulillah!',
        text: '{{ session('success') }}',
        icon: 'success',
        confirmButtonText: 'OK',
        confirmButtonColor: '#2563eb'
      });
    </script>
  @endif

  @if (session('error'))
    <script>
      Swal.fire({
        title: 'Astaghfirullah!',
        text: '{{ session('error') }}',
        icon: 'error',
        confirmButtonText: 'OK',
        confirmButtonColor: '#dc2626'
      });
    </script>
  @endif

</body>
</html>

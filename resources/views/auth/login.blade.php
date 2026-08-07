<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Arsip Surat</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-900 via-indigo-900 to-slate-900 min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8">

    <!-- Card Container -->
    <div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row my-auto">
        
        <!-- Sisi Kiri: Branding & Ilustrasi -->
        <div class="md:w-1/2 bg-gradient-to-br from-blue-50 to-indigo-100 p-8 md:p-12 flex flex-col justify-between border-b md:border-b-0 md:border-r border-slate-100">
            <div>
                <!-- Brand / Logo Header -->
                <div class="flex items-center space-x-3 mb-6">
                    <div class="p-3 bg-blue-600 text-white rounded-xl shadow-md shadow-blue-500/30">
                        <i data-lucide="folder-archive" class="w-8 h-8"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Arsip Surat</h1>
                        <p class="text-xs text-slate-500 font-medium">Sistem Manajemen Dokumen</p>
                    </div>
                </div>
                
                <h2 class="text-xl font-semibold text-slate-700 mb-2">Kelola Dokumen Lebih Efisien</h2>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Masuk untuk mengelola, mengarsipkan, dan melacak surat masuk maupun keluar secara akurat dan profesional.
                </p>
            </div>

            <!-- Vector / Graphic Decoration -->
            <div class="my-8 flex justify-center items-center">
                <div class="relative w-48 h-48 bg-blue-500/10 rounded-full flex items-center justify-center">
                    <div class="w-36 h-36 bg-blue-500/20 rounded-full flex items-center justify-center animate-pulse">
                        <i data-lucide="file-check-2" class="w-20 h-20 text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="text-xs text-slate-400">
                &copy; 2026 Arsip Surat System. All rights reserved.
            </div>
        </div>

        <!-- Sisi Kanan: Form Login -->
        <div class="md:w-1/2 p-8 md:p-12 flex flex-col justify-center bg-white">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-slate-800">Selamat Datang</h2>
                <p class="text-sm text-slate-500 mt-1">Silakan masukkan akun Anda untuk melanjutkan</p>
            </div>

            <form action="/login" method="POST" class="space-y-5">
                <!-- TOKEN CSRF LARAVEL (Mencegah Error 419) -->
                @csrf

                <!-- Input Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                        </div>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            {{-- value="{{ old('email', 'admin@arsipsurat.test') }}" --}}
                            required
                            placeholder="nama@email.com"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white outline-none transition-all duration-200"
                        >
                    </div>
                </div>

                <!-- Input Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="lock" class="w-5 h-5"></i>
                        </div>
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            required
                            placeholder="••••••••"
                            class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white outline-none transition-all duration-200"
                        >
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
                            <i data-lucide="eye" id="eyeIcon" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <!-- Checkbox Remember Me -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                        <span class="text-sm text-slate-600">Ingat saya</span>
                    </label>
                    <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-700 hover:underline">Lupa sandi?</a>
                </div>

                <!-- Tombol Submit -->
                <button 
                    type="submit" 
                    class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md shadow-blue-500/20 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200"
                >
                    Masuk Sekarang
                </button>
            </form>

            <!-- Info Demo -->
            <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-400">
                    {{-- Akun Demo: <span class="font-mono bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded">admin@arsipsurat.test</span> / <span class="font-mono bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded">password</span> --}}
                </p>
            </div>
        </div>

    </div>

    <!-- Script Init Icon & Toggle Show/Hide Password -->
    <script>
        lucide.createIcons();

        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            if (type === 'text') {
                eyeIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                eyeIcon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        });
    </script>
</body>
</html>
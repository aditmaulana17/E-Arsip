<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Arsip Surat</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>

<!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-900 antialiased selection:bg-brand-500 selection:text-white">

    <div class="min-h-screen flex text-slate-800">
        
        <!-- SISI KIRI: Brand & Branding Panel -->
        <div class="hidden lg:flex lg:w-1/2 xl:w-7/12 relative bg-slate-900 flex-col justify-between p-12 overflow-hidden">
            <!-- Background Ornaments / Gradients -->
            <div class="absolute inset-0 bg-gradient-to-tr from-brand-900/80 via-slate-900 to-slate-900 z-0"></div>
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-brand-600/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Pattern Grid Overlay -->
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#1e293b_1px,transparent_1px),linear-gradient(to_bottom,#1e293b_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_50%,#000_70%,transparent_100%)] opacity-30 z-0"></div>

            <!-- Top Logo Header -->
            <div class="relative z-10 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center text-white shadow-lg shadow-brand-500/30">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span class="text-xl font-bold text-white tracking-wide">Arsip<span class="text-brand-400">Surat</span></span>
            </div>

            <!-- Middle Text Content -->
            <div class="relative z-10 max-w-lg space-y-4 my-auto">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-500/20 border border-blue-400/40 text-blue-300 text-xs font-semibold uppercase tracking-wider shadow-sm">
                <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                Sistem Informasi Persuratan
                </div>
                <h1 class="text-4xl xl:text-5xl font-extrabold text-white leading-tight">
                    Pengelolaan Dokumen & Surat Lebih Terstruktur.
                </h1>
                <p class="text-slate-400 text-base leading-relaxed">
                    Kelola surat masuk, surat keluar, disposisi, hingga pencetakan label secara terpusat, cepat, dan terorganisir.
                </p>
            </div>

            <!-- Bottom Footer Info -->
            <div class="relative z-10 pt-6 border-t border-slate-800 text-xs text-slate-500 flex items-center justify-between">
                <span>&copy; <?php echo e(date('Y')); ?> Arsip Surat. All rights reserved.</span>
                <span>v1.0.0</span>
            </div>
        </div>

        <!-- SISI KANAN: Form Login -->
        <div class="w-full lg:w-1/2 xl:w-5/12 bg-white flex flex-col justify-center px-6 sm:px-12 lg:px-16 xl:px-20 py-12">
            
            <div class="w-full max-w-md mx-auto space-y-8">
                
                <!-- Mobile Logo Header -->
                <div class="lg:hidden flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-brand-600 flex items-center justify-center text-white shadow-md">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-slate-800">Arsip Surat</span>
                </div>

                <!-- Form Header -->
                <div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Selamat Datang Kembali</h2>
                    <p class="text-sm text-slate-500 mt-2">Silakan masukkan kredensial Anda untuk mengakses dashboard.</p>
                </div>

                <!-- Alert Session Error -->
                <?php if($errors->any()): ?>
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm flex items-start gap-3">
                        <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <span class="font-semibold block">Gagal Masuk</span>
                            <span><?php echo e($errors->first()); ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Form Login -->
                <form method="POST" action="<?php echo e(route('login.attempt')); ?>" class="space-y-5">
                    <?php echo csrf_field(); ?>

                    <!-- Field Email -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Alamat Email <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </div>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="<?php echo e(old('email')); ?>" 
                                   required 
                                   autofocus 
                                   placeholder="nama@domain.com"
                                   class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                        </div>
                    </div>

                    <!-- Field Kata Sandi -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Kata Sandi <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   required 
                                   placeholder="••••••••"
                                   class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all">
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center cursor-pointer select-none">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500 transition">
                            <span class="ml-2.5 text-sm text-slate-600 font-medium">Ingat saya</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full py-3.5 px-4 bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white font-semibold text-sm rounded-xl transition-all shadow-md shadow-blue-600/20 flex items-center justify-center gap-2">
                        <span>Masuk ke Akun</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </form>

                <!-- LINK DAFTAR AKUN BARU -->
                <p class="text-center text-sm text-slate-500 pt-2">
                    Belum memiliki akun? 
                    <a href="<?php echo e(route('register')); ?>" class="font-semibold text-blue-600 hover:text-blue-700 hover:underline">
                        Daftar Akun Baru
                    </a>
                </p>

                <!-- Box Informasi Akun Demo -->
                <div class="pt-4 border-t border-slate-100">
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 text-xs text-slate-600 space-y-1">
                        <div class="font-semibold text-slate-700 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Akun Demo:
                        </div>
                        <div class="flex items-center justify-between font-mono text-slate-500 pt-1">
                            <span>Email: <strong class="text-slate-700">admin@arsipsurat.test</strong></span>
                            <span>Pass: <strong class="text-slate-700">password</strong></span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

</body>
</html><?php /**PATH C:\Users\user\Downloads\E-Arsip\resources\views/auth/login.blade.php ENDPATH**/ ?>
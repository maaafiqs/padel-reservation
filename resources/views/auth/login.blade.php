@extends('layouts.app')

@section('title', 'Masuk — Akun Demo Tersedia')

@section('content')
<div class="container flex justify-center items-center" style="min-height: calc(100vh - 80px); padding: 40px 1.5rem; position: relative;">
    <!-- Decorative Swinging Racket -->
    <div style="position: absolute; right: 5%; top: 50%; transform: translateY(-50%); z-index: -1; opacity: 0.25; pointer-events: none;" class="hidden md:block">
        <i class="fa-solid fa-table-tennis-paddle-ball" style="color: var(--primary); font-size: 26rem; animation: swing-paddle 4s ease-in-out infinite;"></i>
    </div>

    <div class="card" style="width: 100%; max-width: 460px; z-index: 10; background: rgba(255, 255, 255, 0.75); backdrop-filter: blur(18px); border: 1px solid rgba(255, 255, 255, 0.8); box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.08); border-radius: 1.25rem;">
        <div class="text-center mb-6">
            <div style="display: inline-flex; align-items: center; justify-content: center; width: 56px; height: 56px; border-radius: 1rem; background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: #fff; font-size: 1.5rem; margin-bottom: 1rem; box-shadow: 0 8px 16px -4px rgba(37, 99, 235, 0.4);">
                <i class="fa-solid fa-table-tennis-paddle-ball"></i>
            </div>
            <h1 class="text-3xl mb-1 font-bold text-main">Selamat Datang</h1>
            <p class="text-muted text-sm">Masuk ke akun Anda atau gunakan akun demo untuk mencoba</p>
        </div>

        <!-- Quick Demo Account Banner Trigger -->
        <div class="demo-quick-banner mb-6" onclick="openDemoModal()" title="Klik untuk membuka pilihan akun demo">
            <div class="flex items-center gap-3">
                <div class="demo-banner-icon">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </div>
                <div>
                    <div class="font-bold text-sm text-main flex items-center gap-2">
                        Akun Demo Tersedia
                        <span class="badge-role-tag badge-admin-tag">Admin</span>
                        <span class="badge-role-tag badge-user-tag">User</span>
                    </div>
                    <div class="text-xs text-muted">Klik untuk memilih peran & login instan 1-klik</div>
                </div>
            </div>
            <span class="demo-banner-action">
                Pilih <i class="fa-solid fa-chevron-right text-xs ml-1"></i>
            </span>
        </div>
        
        @if(session('error'))
            <div class="badge badge-danger mb-4 w-full justify-center" style="padding: 0.75rem; width: 100%; display: flex; border-radius: 0.75rem;">
                <i class="fa-solid fa-triangle-exclamation mr-2"></i> {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" id="login-form">
            @csrf
            <div class="form-group mb-4">
                <label for="email" class="form-label font-semibold text-sm">Alamat Email</label>
                <div style="position: relative;">
                    <input type="email" id="email" name="email" class="form-input" placeholder="nama@email.com" required autofocus style="border-radius: 0.75rem; padding: 0.75rem 1rem; transition: all 0.3s;">
                </div>
                @error('email') <span class="text-danger text-sm" style="color: #991b1b; display: block; margin-top: 0.25rem;">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group mb-4">
                <label for="password" class="form-label font-semibold text-sm">Kata Sandi</label>
                <div style="position: relative;">
                    <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required style="border-radius: 0.75rem; padding: 0.75rem 2.75rem 0.75rem 1rem; width: 100%; transition: all 0.3s;">
                    <button type="button" style="position: absolute; right: 0.85rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #64748b; outline: none;" onclick="togglePasswordVisibility('password', this)" title="Lihat password">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="flex justify-between items-center mb-6">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="remember" style="border-radius: 0.25rem; accent-color: var(--primary);">
                    <span class="text-sm text-muted">Ingat Saya</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-sm text-primary font-medium hover:underline">Lupa password?</a>
            </div>

            <button type="submit" id="btn-submit" class="btn btn-primary font-semibold" style="width: 100%; padding: 0.85rem; border-radius: 0.75rem; font-size: 1rem; box-shadow: 0 8px 16px -4px rgba(37, 99, 235, 0.35);">
                <i class="fa-solid fa-right-to-bracket mr-2"></i> Masuk Sekarang
            </button>
        </form>

        <div class="text-center mt-6 text-sm text-muted">
            Belum punya akun? <a href="{{ route('register') }}" class="text-primary font-semibold hover:underline">Daftar sekarang</a>
        </div>
    </div>
</div>

<!-- Floating Demo Accounts Button (Always Accessible) -->
<button type="button" id="btn-floating-demo" onclick="openDemoModal()" class="demo-floating-pill" title="Buka Akun Demo">
    <span class="demo-floating-ping"></span>
    <i class="fa-solid fa-key text-amber-300"></i>
    <span class="font-bold">Akun Demo</span>
</button>

<!-- Demo Accounts Modal Popup -->
<div id="demo-modal" class="demo-modal-backdrop" onclick="handleBackdropClick(event)">
    <div class="demo-modal-dialog" role="dialog" aria-labelledby="demo-modal-title" aria-modal="true">
        <!-- Header -->
        <div class="demo-modal-header">
            <div class="flex items-center gap-3">
                <div class="demo-modal-icon">
                    <i class="fa-solid fa-id-card-clip"></i>
                </div>
                <div>
                    <h3 id="demo-modal-title" class="text-xl font-bold text-main leading-tight">Akun Demo Aplikasi</h3>
                    <p class="text-xs text-muted mt-0.5">Pilih salah satu peran untuk mengisi formulir login otomatis</p>
                </div>
            </div>
            <button type="button" class="demo-modal-close" onclick="closeDemoModal()" title="Tutup Modal (Esc)">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Body: Account List -->
        <div class="demo-modal-body">
            <!-- 1. Super Admin Account -->
            <div class="demo-account-item demo-account-admin">
                <div class="demo-account-top">
                    <div class="flex items-center gap-2">
                        <span class="demo-role-badge badge-super-admin">
                            <i class="fa-solid fa-shield-halved mr-1"></i> Super Admin
                        </span>
                        <span class="demo-account-name font-bold">Admin Padel Arena</span>
                    </div>
                    <span class="text-xs text-muted font-mono bg-slate-100 px-2 py-0.5 rounded">Full Access</span>
                </div>
                <div class="demo-account-credentials">
                    <div class="demo-cred-line">
                        <i class="fa-regular fa-envelope text-muted mr-1.5"></i>
                        <code>admin@maaafiqspadel.com</code>
                    </div>
                    <div class="demo-cred-line">
                        <i class="fa-solid fa-lock text-muted mr-1.5"></i>
                        <code>password</code>
                    </div>
                </div>
                <p class="demo-account-desc">Akses dasbor analitik grafik, manajemen lapangan, pelatih, stok inventaris, voucher diskon, dan backup database.</p>
                <div class="demo-account-actions">
                    <button type="button" class="btn-select-account btn-admin" onclick="selectAccount('admin@maaafiqspadel.com', 'password', 'Super Admin')">
                        <i class="fa-solid fa-pencil mr-1.5"></i> Isi Formulir
                    </button>
                    <button type="button" class="btn-select-account btn-admin-direct" onclick="selectAccount('admin@maaafiqspadel.com', 'password', 'Super Admin', true)">
                        <i class="fa-solid fa-arrow-right-to-bracket mr-1.5"></i> Masuk Langsung
                    </button>
                </div>
            </div>

            <!-- 2. Staff Operator Account -->
            <div class="demo-account-item demo-account-staff">
                <div class="demo-account-top">
                    <div class="flex items-center gap-2">
                        <span class="demo-role-badge badge-staff-admin">
                            <i class="fa-solid fa-user-gear mr-1"></i> Staff Kasir
                        </span>
                        <span class="demo-account-name font-bold">Staff Operator & Kasir</span>
                    </div>
                    <span class="text-xs text-muted font-mono bg-slate-100 px-2 py-0.5 rounded">Admin Role</span>
                </div>
                <div class="demo-account-credentials">
                    <div class="demo-cred-line">
                        <i class="fa-regular fa-envelope text-muted mr-1.5"></i>
                        <code>staff@maaafiqspadel.com</code>
                    </div>
                    <div class="demo-cred-line">
                        <i class="fa-solid fa-lock text-muted mr-1.5"></i>
                        <code>password</code>
                    </div>
                </div>
                <p class="demo-account-desc">Akses verifikasi pembayaran reservasi, input jadwal offline, cek ketersediaan lapangan & peralatan sewa.</p>
                <div class="demo-account-actions">
                    <button type="button" class="btn-select-account btn-staff" onclick="selectAccount('staff@maaafiqspadel.com', 'password', 'Staff Kasir')">
                        <i class="fa-solid fa-pencil mr-1.5"></i> Isi Formulir
                    </button>
                    <button type="button" class="btn-select-account btn-staff-direct" onclick="selectAccount('staff@maaafiqspadel.com', 'password', 'Staff Kasir', true)">
                        <i class="fa-solid fa-arrow-right-to-bracket mr-1.5"></i> Masuk Langsung
                    </button>
                </div>
            </div>

            <!-- 3. Customer / Member Demo Account -->
            <div class="demo-account-item demo-account-user">
                <div class="demo-account-top">
                    <div class="flex items-center gap-2">
                        <span class="demo-role-badge badge-member-user">
                            <i class="fa-solid fa-user-check mr-1"></i> Customer Member
                        </span>
                        <span class="demo-account-name font-bold">Pengguna Demo</span>
                    </div>
                    <span class="text-xs text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded font-semibold">User Role</span>
                </div>
                <div class="demo-account-credentials">
                    <div class="demo-cred-line">
                        <i class="fa-regular fa-envelope text-muted mr-1.5"></i>
                        <code>user@maaafiqspadel.com</code>
                    </div>
                    <div class="demo-cred-line">
                        <i class="fa-solid fa-lock text-muted mr-1.5"></i>
                        <code>password</code>
                    </div>
                </div>
                <p class="demo-account-desc">Booking lapangan interaktif, booking pelatih, sewa raket & beli bola, klaim voucher diskon, upload bukti transfer, dan cetak tiket digital.</p>
                <div class="demo-account-actions">
                    <button type="button" class="btn-select-account btn-user" onclick="selectAccount('user@maaafiqspadel.com', 'password', 'Customer Member')">
                        <i class="fa-solid fa-pencil mr-1.5"></i> Isi Formulir
                    </button>
                    <button type="button" class="btn-select-account btn-user-direct" onclick="selectAccount('user@maaafiqspadel.com', 'password', 'Customer Member', true)">
                        <i class="fa-solid fa-arrow-right-to-bracket mr-1.5"></i> Masuk Langsung
                    </button>
                </div>
            </div>

            <!-- 4. Alternate Member Account -->
            <div class="demo-account-item demo-account-member">
                <div class="demo-account-top">
                    <div class="flex items-center gap-2">
                        <span class="demo-role-badge badge-regular-member">
                            <i class="fa-solid fa-user mr-1"></i> Regular Member
                        </span>
                        <span class="demo-account-name font-bold">Budi Santoso</span>
                    </div>
                    <span class="text-xs text-muted font-mono bg-slate-100 px-2 py-0.5 rounded">User Role</span>
                </div>
                <div class="demo-account-credentials">
                    <div class="demo-cred-line">
                        <i class="fa-regular fa-envelope text-muted mr-1.5"></i>
                        <code>budi.santoso@example.com</code>
                    </div>
                    <div class="demo-cred-line">
                        <i class="fa-solid fa-lock text-muted mr-1.5"></i>
                        <code>password</code>
                    </div>
                </div>
                <p class="demo-account-desc">Akun member aktif dengan riwayat pemesanan lapangan yang telah terkonfirmasi.</p>
                <div class="demo-account-actions">
                    <button type="button" class="btn-select-account btn-member" onclick="selectAccount('budi.santoso@example.com', 'password', 'Budi Santoso')">
                        <i class="fa-solid fa-pencil mr-1.5"></i> Isi Formulir
                    </button>
                    <button type="button" class="btn-select-account btn-member-direct" onclick="selectAccount('budi.santoso@example.com', 'password', 'Budi Santoso', true)">
                        <i class="fa-solid fa-arrow-right-to-bracket mr-1.5"></i> Masuk Langsung
                    </button>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="demo-modal-footer">
            <div class="flex items-center gap-2 text-xs text-muted">
                <i class="fa-solid fa-circle-info text-primary" style="font-size: 0.95rem;"></i>
                <span>Semua akun menggunakan password bawaan: <strong class="text-main font-mono">password</strong></span>
            </div>
            <button type="button" class="btn btn-outline" style="padding: 0.45rem 1rem; font-size: 0.85rem; border-radius: 0.5rem;" onclick="closeDemoModal()">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Floating Success Toast Notification -->
<div id="demo-toast" class="demo-toast">
    <div class="flex items-center gap-2">
        <i class="fa-solid fa-circle-check text-emerald-400 text-lg"></i>
        <span id="demo-toast-msg" class="font-medium text-sm">Akun berhasil dimuat ke form!</span>
    </div>
</div>

<style>
    /* Quick Demo Banner in Card */
    .demo-quick-banner {
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.08) 0%, rgba(59, 130, 246, 0.12) 100%);
        border: 1px dashed rgba(37, 99, 235, 0.35);
        border-radius: 0.875rem;
        padding: 0.75rem 0.95rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: all 0.25s ease;
    }
    .demo-quick-banner:hover {
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.14) 0%, rgba(59, 130, 246, 0.2) 100%);
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
    }
    .demo-banner-icon {
        width: 36px;
        height: 36px;
        border-radius: 0.6rem;
        background: var(--primary);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        box-shadow: 0 4px 8px rgba(37, 99, 235, 0.3);
    }
    .badge-role-tag {
        font-size: 0.65rem;
        padding: 0.15rem 0.45rem;
        border-radius: 9999px;
        font-weight: 700;
        letter-spacing: 0.02em;
        text-transform: uppercase;
    }
    .badge-admin-tag {
        background: #ede9fe;
        color: #6d28d9;
    }
    .badge-user-tag {
        background: #dcfce7;
        color: #15803d;
    }
    .demo-banner-action {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--primary);
        background: #ffffff;
        padding: 0.35rem 0.65rem;
        border-radius: 0.5rem;
        border: 1px solid rgba(37, 99, 235, 0.2);
        display: inline-flex;
        align-items: center;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    }

    /* Floating Pill Button */
    .demo-floating-pill {
        position: fixed;
        bottom: 1.75rem;
        right: 1.75rem;
        z-index: 99;
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 9999px;
        padding: 0.75rem 1.25rem;
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 0.9rem;
        cursor: pointer;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.05);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .demo-floating-pill:hover {
        transform: translateY(-3px) scale(1.02);
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        box-shadow: 0 12px 28px -5px rgba(37, 99, 235, 0.5);
    }
    .demo-floating-ping {
        width: 8px;
        height: 8px;
        border-radius: 9999px;
        background-color: #10b981;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulse-ring 1.8s infinite;
    }
    @keyframes pulse-ring {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    /* Modal Backdrop */
    .demo-modal-backdrop {
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.25s ease, visibility 0.25s ease;
    }
    .demo-modal-backdrop.active {
        opacity: 1;
        visibility: visible;
    }

    /* Modal Dialog */
    .demo-modal-dialog {
        background: #ffffff;
        border-radius: 1.25rem;
        width: 100%;
        max-width: 620px;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(0, 0, 0, 0.05);
        transform: scale(0.95) translateY(10px);
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        overflow: hidden;
    }
    .demo-modal-backdrop.active .demo-modal-dialog {
        transform: scale(1) translateY(0);
    }

    /* Modal Header */
    .demo-modal-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fafafa;
    }
    .demo-modal-icon {
        width: 44px;
        height: 44px;
        border-radius: 0.85rem;
        background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        box-shadow: 0 6px 14px -3px rgba(37, 99, 235, 0.4);
    }
    .demo-modal-close {
        width: 36px;
        height: 36px;
        border-radius: 0.5rem;
        border: none;
        background: #f1f5f9;
        color: #64748b;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        transition: all 0.2s;
    }
    .demo-modal-close:hover {
        background: #fee2e2;
        color: #ef4444;
        transform: rotate(90deg);
    }

    /* Modal Body */
    .demo-modal-body {
        padding: 1.25rem 1.5rem;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        max-height: calc(90vh - 150px);
    }

    /* Account Item Cards */
    .demo-account-item {
        border-radius: 1rem;
        border: 1px solid var(--border);
        padding: 1.15rem;
        background: #ffffff;
        transition: all 0.25s ease;
        position: relative;
    }
    .demo-account-item:hover {
        border-color: #94a3b8;
        box-shadow: 0 8px 20px -6px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }
    .demo-account-admin {
        border-left: 4px solid #7c3aed;
        background: linear-gradient(to right, #faf5ff 0%, #ffffff 40%);
    }
    .demo-account-staff {
        border-left: 4px solid #4f46e5;
        background: linear-gradient(to right, #eef2ff 0%, #ffffff 40%);
    }
    .demo-account-user {
        border-left: 4px solid #059669;
        background: linear-gradient(to right, #ecfdf5 0%, #ffffff 40%);
    }
    .demo-account-member {
        border-left: 4px solid #0284c7;
        background: linear-gradient(to right, #f0f9ff 0%, #ffffff 40%);
    }

    .demo-account-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.6rem;
    }
    .demo-role-badge {
        font-size: 0.7rem;
        padding: 0.2rem 0.55rem;
        border-radius: 9999px;
        font-weight: 700;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
    }
    .badge-super-admin {
        background: #7c3aed;
        color: #ffffff;
    }
    .badge-staff-admin {
        background: #4f46e5;
        color: #ffffff;
    }
    .badge-member-user {
        background: #059669;
        color: #ffffff;
    }
    .badge-regular-member {
        background: #0284c7;
        color: #ffffff;
    }

    .demo-account-credentials {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem 1.25rem;
        background: rgba(241, 245, 249, 0.7);
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.8rem;
        margin-bottom: 0.6rem;
    }
    .demo-cred-line code {
        font-family: monospace;
        font-weight: 600;
        color: #1e293b;
    }
    .demo-account-desc {
        font-size: 0.8rem;
        color: var(--text-muted);
        line-height: 1.45;
        margin-bottom: 0.85rem;
    }

    .demo-account-actions {
        display: flex;
        gap: 0.5rem;
    }
    .btn-select-account {
        padding: 0.45rem 0.85rem;
        border-radius: 0.5rem;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        border: 1px solid transparent;
        flex: 1;
    }
    /* Button Variations */
    .btn-admin {
        background: #ede9fe;
        color: #6d28d9;
        border-color: #ddd6fe;
    }
    .btn-admin:hover {
        background: #7c3aed;
        color: #ffffff;
    }
    .btn-admin-direct {
        background: #7c3aed;
        color: #ffffff;
    }
    .btn-admin-direct:hover {
        background: #6d28d9;
    }

    .btn-staff {
        background: #e0e7ff;
        color: #4338ca;
        border-color: #c7d2fe;
    }
    .btn-staff:hover {
        background: #4f46e5;
        color: #ffffff;
    }
    .btn-staff-direct {
        background: #4f46e5;
        color: #ffffff;
    }
    .btn-staff-direct:hover {
        background: #4338ca;
    }

    .btn-user {
        background: #d1fae5;
        color: #047857;
        border-color: #a7f3d0;
    }
    .btn-user:hover {
        background: #059669;
        color: #ffffff;
    }
    .btn-user-direct {
        background: #059669;
        color: #ffffff;
    }
    .btn-user-direct:hover {
        background: #047857;
    }

    .btn-member {
        background: #e0f2fe;
        color: #0369a1;
        border-color: #bae6fd;
    }
    .btn-member:hover {
        background: #0284c7;
        color: #ffffff;
    }
    .btn-member-direct {
        background: #0284c7;
        color: #ffffff;
    }
    .btn-member-direct:hover {
        background: #0369a1;
    }

    /* Modal Footer */
    .demo-modal-footer {
        padding: 0.95rem 1.5rem;
        border-top: 1px solid var(--border);
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Success Toast */
    .demo-toast {
        position: fixed;
        top: 2rem;
        right: 2rem;
        z-index: 10000;
        background: #0f172a;
        color: #ffffff;
        padding: 0.85rem 1.25rem;
        border-radius: 0.75rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4);
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .demo-toast.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    /* Input Highlight Flash */
    .input-flash {
        animation: highlightGlow 1.4s ease;
    }
    @keyframes highlightGlow {
        0% { border-color: #10b981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.35); background-color: #f0fdf4; }
        50% { border-color: #10b981; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.45); background-color: #f0fdf4; }
        100% { border-color: var(--border); box-shadow: none; background-color: #ffffff; }
    }

    @media (max-width: 640px) {
        .demo-floating-pill {
            bottom: 1rem;
            right: 1rem;
            padding: 0.6rem 1rem;
            font-size: 0.8rem;
        }
        .demo-modal-dialog {
            max-height: 95vh;
        }
        .demo-account-actions {
            flex-direction: column;
        }
    }
</style>

<script>
    function openDemoModal() {
        const modal = document.getElementById('demo-modal');
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeDemoModal() {
        const modal = document.getElementById('demo-modal');
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    function handleBackdropClick(event) {
        if (event.target.id === 'demo-modal') {
            closeDemoModal();
        }
    }

    // Keyboard ESC key handler
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDemoModal();
        }
    });

    function selectAccount(email, password, roleName, autoSubmit = false) {
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const loginForm = document.getElementById('login-form');

        if (emailInput && passwordInput) {
            emailInput.value = email;
            passwordInput.value = password;

            // Trigger change event
            emailInput.dispatchEvent(new Event('input', { bubbles: true }));
            passwordInput.dispatchEvent(new Event('input', { bubbles: true }));

            // Add highlight glow
            emailInput.classList.remove('input-flash');
            passwordInput.classList.remove('input-flash');
            void emailInput.offsetWidth; // Force reflow
            emailInput.classList.add('input-flash');
            passwordInput.classList.add('input-flash');

            // Show Toast
            showDemoToast(`Akun ${roleName} siap digunakan!`);

            // Close Modal
            closeDemoModal();

            if (autoSubmit && loginForm) {
                const submitBtn = document.getElementById('btn-submit');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Sedang Masuk...';
                    submitBtn.disabled = true;
                }
                setTimeout(() => {
                    loginForm.submit();
                }, 350);
            } else {
                // Focus on submit button for easy Enter press
                const submitBtn = document.getElementById('btn-submit');
                if (submitBtn) submitBtn.focus();
            }
        }
    }

    function showDemoToast(message) {
        const toast = document.getElementById('demo-toast');
        const toastMsg = document.getElementById('demo-toast-msg');
        if (toast && toastMsg) {
            toastMsg.textContent = message;
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }
    }

    function togglePasswordVisibility(inputId, button) {
        const input = document.getElementById(inputId);
        const icon = button.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection

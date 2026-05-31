@extends('layouts.app')

@section('title', 'Daftar - GreensaInn')

@section('styles')
<style>
    .auth-wrapper {
        min-height: 80vh;
        display: flex;
        align-items: center;
        padding: 40px 0;
        background-color: var(--light-bg);
    }
    
    .auth-card {
        background: white;
        border: 1px solid rgba(15, 76, 92, 0.08);
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(15, 76, 92, 0.08);
        overflow: hidden;
        display: flex;
        flex-direction: row-reverse; /* Reverse for variety */
        max-width: 1000px;
        margin: 0 auto;
    }

    .auth-image {
        flex: 1;
        background: linear-gradient(135deg, rgba(251, 139, 36, 0.85) 0%, rgba(227, 100, 20, 0.9) 100%), url('https://images.unsplash.com/photo-1517502884422-41eaaced0168?q=80&w=1200&auto=format&fit=crop') center/cover no-repeat;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 60px;
        color: white;
        position: relative;
    }

    .auth-image::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: url('https://www.transparenttextures.com/patterns/cubes.png');
        opacity: 0.1;
        pointer-events: none;
    }

    .auth-image h2 {
        color: white;
        font-size: 2.5rem;
        margin-bottom: 20px;
        font-weight: 800;
        position: relative;
        z-index: 1;
    }

    .auth-image p {
        font-size: 1.1rem;
        opacity: 0.9;
        line-height: 1.6;
        position: relative;
        z-index: 1;
    }

    .auth-form-container {
        flex: 1;
        padding: 60px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: white;
    }

    .form-control {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 20px;
        font-size: 0.95rem;
        transition: var(--transition-smooth);
        background-color: #f8fafc;
    }

    .form-control:focus {
        background-color: white;
        border-color: var(--accent-color);
        box-shadow: 0 0 0 4px rgba(251, 139, 36, 0.1);
    }

    .form-label {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .btn-auth {
        background-color: var(--accent-color);
        color: white;
        font-weight: 700;
        padding: 14px;
        border-radius: 12px;
        width: 100%;
        border: none;
        transition: var(--transition-smooth);
        font-size: 1rem;
        margin-top: 10px;
    }

    .btn-auth:hover {
        background-color: var(--accent-hover);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(251, 139, 36, 0.2);
    }

    .auth-link {
        color: var(--accent-color);
        text-decoration: none;
        font-weight: 600;
        transition: var(--transition-smooth);
    }

    .auth-link:hover {
        color: var(--primary-color);
    }

    @media (max-width: 768px) {
        .auth-card {
            flex-direction: column;
            margin: 0 20px;
        }
        .auth-image {
            padding: 40px 30px;
        }
        .auth-form-container {
            padding: 40px 30px;
        }
    }
</style>
@endsection

@section('content')
<div class="auth-wrapper">
    <div class="container animate-fade-in">
        <div class="auth-card">
            <div class="auth-image">
                <h2>Mulai Perjalanan Anda</h2>
                <p>Bergabunglah bersama ribuan profesional lainnya. Temukan dan pesan ruang rapat yang sempurna untuk setiap kebutuhan bisnis Anda hanya dalam beberapa klik.</p>
                
                <div class="mt-4 d-flex align-items-center gap-3">
                    <div class="d-flex -space-x-2">
                        <img class="rounded-circle border border-2 border-white" src="https://i.pravatar.cc/100?img=1" width="40" height="40" alt="User">
                        <img class="rounded-circle border border-2 border-white" src="https://i.pravatar.cc/100?img=2" width="40" height="40" style="margin-left: -10px" alt="User">
                        <img class="rounded-circle border border-2 border-white" src="https://i.pravatar.cc/100?img=3" width="40" height="40" style="margin-left: -10px" alt="User">
                    </div>
                    <span class="small font-weight-bold" style="position: relative; z-index: 1;">500+ Pengguna Aktif</span>
                </div>
            </div>
            <div class="auth-form-container">
                <div class="mb-4 text-center text-md-start">
                    <h3 class="mb-2">Buat Akun Baru</h3>
                    <p class="text-muted">Isi data diri Anda untuk membuat akun.</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger" style="border-radius: 12px; border: none; background-color: #fef2f2; color: #b91c1c;">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <div class="position-relative">
                            <span class="position-absolute top-50 translate-middle-y ms-3 text-muted">
                                <i class="fa-regular fa-user"></i>
                            </span>
                            <input type="text" class="form-control ps-5" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="John Doe">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="position-relative">
                            <span class="position-absolute top-50 translate-middle-y ms-3 text-muted">
                                <i class="fa-regular fa-envelope"></i>
                            </span>
                            <input type="email" class="form-control ps-5" id="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="position-relative">
                            <span class="position-absolute top-50 translate-middle-y ms-3 text-muted">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            <input type="password" class="form-control ps-5" id="password" name="password" required placeholder="Minimal 8 karakter">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <div class="position-relative">
                            <span class="position-absolute top-50 translate-middle-y ms-3 text-muted">
                                <i class="fa-solid fa-lock-open"></i>
                            </span>
                            <input type="password" class="form-control ps-5" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password Anda">
                        </div>
                    </div>

                    <button type="submit" class="btn-auth">
                        Daftar Sekarang <i class="fa-solid fa-user-plus ms-2"></i>
                    </button>
                </form>

                <div class="mt-4 text-center">
                    <p class="text-muted" style="font-size: 0.95rem;">Sudah punya akun? <a href="{{ route('login') }}" class="auth-link">Masuk di sini</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

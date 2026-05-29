<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GreensaInn - Peminjaman Ruang Rapat Premium')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- FontAwesome 6.4.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Custom Premium Styles -->
    <style>
        :root {
            --primary-color: #0f4c5c;
            --primary-dark: #0a3641;
            --primary-light: #e6f0f2;
            --accent-color: #fb8b24;
            --accent-hover: #e36414;
            --dark-color: #111b21;
            --light-bg: #f8fafc;
            --card-shadow: 0 10px 30px rgba(15, 76, 92, 0.05);
            --hover-shadow: 0 20px 40px rgba(15, 76, 92, 0.12);
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--light-bg);
            color: #495057;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            color: var(--dark-color);
        }

        /* Navbar Styling */
        .navbar {
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            background-color: rgba(255, 255, 255, 0.85) !important;
            border-bottom: 1px solid rgba(15, 76, 92, 0.08);
            padding: 18px 0;
            transition: var(--transition-smooth);
        }
        
        .navbar.scrolled {
            padding: 10px 0;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.05);
        }

        .navbar-brand {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.6rem;
            color: var(--primary-color) !important;
            letter-spacing: -0.5px;
        }

        .navbar-brand span {
            color: var(--accent-color);
        }

        .nav-link {
            font-weight: 600;
            font-size: 0.95rem;
            color: #495057 !important;
            padding: 8px 16px !important;
            border-radius: 8px;
            transition: var(--transition-smooth);
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary-color) !important;
            background-color: var(--primary-light);
        }

        /* Button Styling */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 10px;
            transition: var(--transition-smooth);
        }

        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(15, 76, 92, 0.3);
        }

        .btn-accent {
            background-color: var(--accent-color);
            color: white;
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 10px;
            border: none;
            transition: var(--transition-smooth);
        }

        .btn-accent:hover {
            background-color: var(--accent-hover);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(251, 139, 36, 0.3);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 10px;
            transition: var(--transition-smooth);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        /* Cards and Elements Styling */
        .premium-card {
            background: white;
            border: 1px solid rgba(15, 76, 92, 0.05);
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            transition: var(--transition-smooth);
            overflow: hidden;
        }

        .premium-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--hover-shadow);
            border-color: rgba(15, 76, 92, 0.15);
        }

        /* Text Utilities */
        .text-gradient {
            background: linear-gradient(135deg, var(--primary-color) 0%, #2f80ed 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .text-primary-custom {
            color: var(--primary-color);
        }
        
        .text-accent-custom {
            color: var(--accent-color);
        }

        /* Footer */
        footer {
            background-color: var(--dark-color);
            color: rgba(255, 255, 255, 0.7);
            margin-top: auto;
            border-top: 5px solid var(--primary-color);
        }

        footer h5 {
            color: white;
            font-weight: 600;
            margin-bottom: 20px;
        }

        footer a {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            transition: var(--transition-smooth);
        }

        footer a:hover {
            color: var(--accent-color);
            padding-left: 5px;
        }

        .footer-social-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.05);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white !important;
            margin-right: 10px;
            transition: var(--transition-smooth);
        }

        .footer-social-icon:hover {
            background-color: var(--accent-color);
            color: white;
            transform: translateY(-3px);
            padding-left: 0 !important;
        }

        /* Micro-animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }
        
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Header / Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fa-solid fa-hotel me-2"></i>Greensa<span>Inn</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#rooms">Daftar Ruangan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#facilities">Fasilitas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#steps">Cara Pesan</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <a href="#" class="btn btn-outline-primary px-4 d-none d-lg-block">Masuk</a>
                    <a href="#rooms" class="btn btn-primary px-4">Pesan Sekarang</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="py-5">
        <div class="container">
            <div class="row g-4 justify-content-between">
                <div class="col-lg-4 col-md-6">
                    <a class="navbar-brand text-white mb-3 d-inline-block" href="#">
                        <i class="fa-solid fa-hotel me-2 text-primary-custom"></i>Greensa<span class="text-accent-custom">Inn</span>
                    </a>
                    <p class="small text-muted mb-4">
                        Penyedia layanan sewa ruang rapat premium dan profesional. Kami menghadirkan ruang kolaborasi terbaik dengan fasilitas modern untuk menunjang produktivitas bisnis Anda.
                    </p>
                    <div class="d-flex">
                        <a href="#" class="footer-social-icon"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="footer-social-icon"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="footer-social-icon"><i class="fa-brands fa-twitter"></i></a>
                        <a href="#" class="footer-social-icon"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-6">
                    <h5>Layanan</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#">Ruang Direksi</a></li>
                        <li class="mb-2"><a href="#">Ruang Kreatif</a></li>
                        <li class="mb-2"><a href="#">Aula Seminar</a></li>
                        <li class="mb-2"><a href="#">Huddle Room</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 col-6">
                    <h5>Navigasi</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ url('/') }}">Beranda</a></li>
                        <li class="mb-2"><a href="#rooms">Daftar Ruangan</a></li>
                        <li class="mb-2"><a href="#facilities">Fasilitas</a></li>
                        <li class="mb-2"><a href="#steps">Cara Pesan</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5>Kontak Kami</h5>
                    <ul class="list-unstyled text-muted small">
                        <li class="mb-3 d-flex align-items-start">
                            <i class="fa-solid fa-location-dot me-3 mt-1 text-accent-custom"></i>
                            <span>Jl. Raya Kampus Greensa No. 12, Surabaya, Jawa Timur</span>
                        </li>
                        <li class="mb-3 d-flex align-items-center">
                            <i class="fa-solid fa-phone me-3 text-accent-custom"></i>
                            <span>+62 31 8976 5432</span>
                        </li>
                        <li class="mb-3 d-flex align-items-center">
                            <i class="fa-solid fa-envelope me-3 text-accent-custom"></i>
                            <span>info@greensainn.com</span>
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 border-secondary opacity-25">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start small text-muted">
                    &copy; 2026 GreensaInn. All rights reserved.
                </div>
                <div class="col-md-6 text-center text-md-end small text-muted mt-2 mt-md-0">
                    <a href="#" class="me-3">Kebijakan Privasi</a>
                    <a href="#">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3.3 Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <!-- Navbar Scroll Script -->
    <script>
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
    @yield('scripts')
</body>
</html>

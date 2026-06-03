<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Booking GreensaInn - {{ date('d-m-Y') }}</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome 6.4.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        :root {
            --primary-color: #0f4c5c;
            --primary-dark: #0a3641;
            --dark-color: #111b21;
            --accent-color: #fb8b24;
            --light-bg: #f8f9fa;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #333;
            background-color: #fff;
            margin: 0;
            padding: 40px;
            font-size: 13px;
            line-height: 1.5;
        }

        /* Kop Surat */
        .kop-surat {
            display: flex;
            align-items: center;
            border-bottom: 3px double var(--primary-color);
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .kop-logo {
            font-family: 'Outfit', sans-serif;
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--primary-color);
            text-decoration: none;
            margin-right: 25px;
            border: 2.5px solid var(--primary-color);
            padding: 5px 15px;
            border-radius: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kop-logo span {
            color: var(--accent-color);
        }

        .kop-info {
            flex-grow: 1;
        }

        .kop-info h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            margin: 0 0 5px 0;
            color: var(--dark-color);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kop-info p {
            margin: 0 0 3px 0;
            color: #666;
            font-size: 0.85rem;
        }

        /* Document Title */
        .doc-title {
            text-align: center;
            margin-bottom: 25px;
        }

        .doc-title h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.3rem;
            font-weight: 700;
            margin: 0 0 8px 0;
            color: var(--primary-color);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .doc-title p {
            margin: 0;
            color: #555;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Metadata & Filter badge list */
        .filter-info-box {
            background-color: var(--light-bg);
            border-radius: 8px;
            padding: 12px 18px;
            margin-bottom: 25px;
            font-size: 0.85rem;
            border: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .filter-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .filter-label {
            font-weight: 600;
            color: #666;
        }

        .filter-value {
            font-weight: 700;
            color: var(--dark-color);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-card {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            background-color: #fff;
            text-align: center;
        }

        .stat-card-title {
            font-size: 0.75rem;
            font-weight: 700;
            color: #888;
            text-transform: uppercase;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }

        .stat-card-value {
            font-family: 'Outfit', sans-serif;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
        }

        /* Data Table */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        .report-table th, .report-table td {
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
            font-size: 0.82rem;
        }

        .report-table th {
            background-color: var(--primary-color);
            color: #fff;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .report-table tr:nth-child(even) {
            background-color: rgba(15, 76, 92, 0.02);
        }

        .report-table td {
            color: #444;
        }

        .fw-bold {
            font-weight: 700;
        }

        .text-right {
            text-align: right;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            display: inline-block;
        }

        .badge-success {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .badge-warning {
            background-color: #fff3e0;
            color: #ef6c00;
        }

        .badge-danger {
            background-color: #ffebee;
            color: #c62828;
        }

        .badge-info {
            background-color: #e3f2fd;
            color: #1e88e5;
        }

        /* Signature block */
        .signature-container {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
            page-break-inside: avoid;
        }

        .signature-box {
            text-align: center;
            width: 200px;
        }

        .signature-date {
            margin-bottom: 60px;
            font-size: 0.85rem;
        }

        .signature-line {
            border-bottom: 1.5px solid #333;
            font-weight: 700;
            padding-bottom: 5px;
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        .signature-title {
            color: #666;
            font-size: 0.8rem;
        }

        /* Action bar for screen view only */
        .action-bar {
            background-color: #fff;
            padding: 15px 30px;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border-radius: 8px;
        }

        .btn {
            padding: 10px 20px;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            transition: all 0.2s;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
            text-decoration: none;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        /* Print Media CSS overrides */
        @media print {
            body {
                padding: 0;
                font-size: 11px;
            }

            .action-bar {
                display: none;
            }

            .kop-logo {
                border-color: #000;
                color: #000;
            }

            .report-table th {
                background-color: #000 !important;
                color: #fff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .report-table tr:nth-child(even) {
                background-color: #f9f9f9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .badge {
                border: 1px solid #ccc;
                color: #000 !important;
                background-color: transparent !important;
            }

            @page {
                size: A4 portrait;
                margin: 20mm;
            }
        }
    </style>
</head>
<body>

    <!-- Screen view action bar -->
    <div class="action-bar">
        <a href="{{ url('/admin/reviews') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Admin
        </a>
        <div>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fa-solid fa-print"></i> Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Kop Surat (Letterhead) -->
    <div class="kop-surat">
        <div class="kop-logo">
            Greensa<span>Inn</span>
        </div>
        <div class="kop-info">
            <h1>GreensaInn Meeting Hub & Coworking Space</h1>
            <p><i class="fa-solid fa-location-dot"></i> Universitas Islam Negeri Sunan Ampel (UINSA) Surabaya</p>
            <p><i class="fa-solid fa-envelope"></i> contact@greensainn.com | <i class="fa-solid fa-phone"></i> +62 812-3456-7890</p>
            <p>Jalan A. Yani No. 117, Jemur Wonosari, Kec. Wonocolo, Kota Surabaya, Jawa Timur 60237</p>
        </div>
    </div>

    <!-- Document Title -->
    <div class="doc-title">
        <h2>Laporan Peninjauan & Evaluasi Reservasi Ruangan</h2>
        <p>Sistem Management Reservasi GreensaInn Admin</p>
    </div>

    <!-- Filter Information Box -->
    <div class="filter-info-box">
        <div class="filter-item">
            <span class="filter-label">Periode Tanggal:</span>
            <span class="filter-value">
                @if($startDate && $endDate)
                    {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} s.d. {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}
                @elseif($startDate)
                    Mulai {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }}
                @elseif($endDate)
                    Hingga {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}
                @else
                    Semua Periode
                @endif
            </span>
        </div>
        <div class="filter-item">
            <span class="filter-label">Status:</span>
            <span class="filter-value">
                @if($status === 'all')
                    Semua Status
                @else
                    {{ ucfirst($status) }}
                @endif
            </span>
        </div>
        <div class="filter-item">
            <span class="filter-label">Tipe Pemohon:</span>
            <span class="filter-value">
                @if($applicantType === 'all')
                    Semua Pemohon
                @elseif($applicantType === 'internal')
                    Internal UINSA
                @else
                    Eksternal
                @endif
            </span>
        </div>
        <div class="filter-item">
            <span class="filter-label">Tanggal Cetak:</span>
            <span class="filter-value">{{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }} WIB</span>
        </div>
    </div>

    <!-- Stats Summary Block -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-title">Total Reservasi</div>
            <p class="stat-card-value">{{ $totalBookings }}</p>
        </div>
        <div class="stat-card">
            <div class="stat-card-title">Total Pendapatan</div>
            <p class="stat-card-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="stat-card">
            <div class="stat-card-title">Pemohon Internal</div>
            <p class="stat-card-value">{{ $totalInternal }}</p>
        </div>
        <div class="stat-card">
            <div class="stat-card-title">Pemohon Eksternal</div>
            <p class="stat-card-value">{{ $totalExternal }}</p>
        </div>
    </div>

    <!-- Data Table -->
    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 80px;">ID</th>
                <th>Pemohon (Instansi)</th>
                <th>Ruangan</th>
                <th>Tanggal & Waktu Sewa</th>
                <th style="width: 100px;">Tipe</th>
                <th style="width: 90px; text-align: center;">Status</th>
                <th class="text-right" style="width: 110px;">Total Tarif</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
                @php
                    $isInternal = ($booking->user->instansi ?? 'umum') === 'internal';
                    $totalHarga = 0;
                    
                    if ($booking->waktu_mulai && $booking->waktu_selesai) {
                        $start = \Carbon\Carbon::parse($booking->waktu_mulai);
                        $end = \Carbon\Carbon::parse($booking->waktu_selesai);
                        $durasiJam = max(1, $start->diffInHours($end));
                    } else {
                        $durasiJam = 0;
                    }

                    if (!$isInternal) {
                        $mockRooms = getMockRooms();
                        $roomPrice = $mockRooms[$booking->ruangan_id]['price'] ?? 0;
                        $totalHarga = $roomPrice * $durasiJam;
                    }
                @endphp
                <tr>
                    <td class="fw-bold">BK-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>
                        <span class="fw-bold">{{ $booking->user->nama_lengkap ?? 'Unknown' }}</span>
                        <div style="font-size: 0.75rem; color: #666;">Role: {{ $booking->user->role ?? '-' }}</div>
                    </td>
                    <td>{{ $booking->ruangan->nama_ruangan ?? 'Unknown' }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($booking->tanggal_mulai)->translatedFormat('d M Y') }}
                        <div style="font-size: 0.75rem; color: #666;">
                            <i class="fa-regular fa-clock"></i> {{ Carbon\Carbon::parse($booking->waktu_mulai)->format('H:i') }} - {{ Carbon\Carbon::parse($booking->waktu_selesai)->format('H:i') }} ({{ $durasiJam }} Jam)
                        </div>
                    </td>
                    <td>
                        @if($isInternal)
                            Internal UINSA
                        @else
                            Eksternal
                        @endif
                    </td>
                    <td style="text-align: center;">
                        @if($booking->status === 'approved')
                            <span class="badge badge-success">Disetujui</span>
                        @elseif($booking->status === 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @elseif($booking->status === 'completed')
                            <span class="badge badge-info">Selesai</span>
                        @else
                            <span class="badge badge-danger">Ditolak</span>
                        @endif
                    </td>
                    <td class="text-right fw-bold">
                        @if($isInternal)
                            Rp 0
                        @else
                            Rp {{ number_format($totalHarga, 0, ',', '.') }}
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #888; padding: 30px;">Tidak ada data reservasi pemesanan yang sesuai dengan filter.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Signature block -->
    <div class="signature-container">
        <div class="signature-box">
            <!-- Left side empty or for verifier -->
        </div>
        <div class="signature-box">
            <div class="signature-date">Surabaya, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
            <div class="signature-line">{{ Auth::user()->nama_lengkap ?? 'Admin GreensaInn' }}</div>
            <div class="signature-title">Staff Penanggung Jawab Admin</div>
        </div>
    </div>

    <!-- Auto Print Script -->
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            // Auto open system print dialogue when loaded
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>

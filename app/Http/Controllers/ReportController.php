<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Export booking report to CSV file.
     */
    public function index(Request $request)
    {
        $bookings = $this->getFilteredBookings($request);

        $filename = "Laporan_Booking_GreensaInn_" . date('Y-m-d_H-i-s') . ".csv";
        
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'ID Booking', 
            'Tanggal Pengajuan', 
            'Nama Pemohon', 
            'Role', 
            'Tipe Pemohon', 
            'Ruangan', 
            'Tanggal Peminjaman', 
            'Waktu Mulai', 
            'Waktu Selesai', 
            'Durasi (Jam)', 
            'Tujuan Rapat',
            'Status', 
            'Total Tarif (Rp)', 
            'Minimal DP (Rp)'
        ];

        $callback = function() use($bookings, $columns) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Use comma or semicolon. Excel in Indonesia defaults to semicolon due to regional settings.
            // Let's use comma for universal CSV compatibility or standard commas.
            fputcsv($file, $columns, ',');

            foreach ($bookings as $booking) {
                $isInternal = ($booking->user->instansi ?? 'umum') === 'internal';
                
                $totalHarga = 0;
                $dp = 0;
                $durasiJam = 0;

                if ($booking->waktu_mulai && $booking->waktu_selesai) {
                    $start = Carbon::parse($booking->waktu_mulai);
                    $end = Carbon::parse($booking->waktu_selesai);
                    $durasiJam = max(1, $start->diffInHours($end));
                }

                if (!$isInternal && $booking->ruangan) {
                    $roomPrice = $booking->ruangan->harga_per_jam ?? 0;
                    $totalHarga = $roomPrice * $durasiJam;
                    $dp = $totalHarga * 0.5;
                }

                fputcsv($file, [
                    'BK-' . str_pad($booking->id, 4, '0', STR_PAD_LEFT),
                    $booking->created_at ? $booking->created_at->format('Y-m-d H:i') : '-',
                    $booking->user->nama_lengkap ?? 'Unknown',
                    $booking->user->role ?? '-',
                    $isInternal ? 'Internal UINSA' : 'Eksternal',
                    $booking->ruangan->nama_ruangan ?? 'Unknown',
                    Carbon::parse($booking->tanggal_mulai)->format('Y-m-d'),
                    Carbon::parse($booking->waktu_mulai)->format('H:i'),
                    Carbon::parse($booking->waktu_selesai)->format('H:i'),
                    $durasiJam,
                    $booking->tujuan_rapat,
                    ucfirst($booking->status),
                    $totalHarga,
                    $dp
                ], ',');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Render the report for printing.
     */
    public function print(Request $request)
    {
        $bookings = $this->getFilteredBookings($request);
        
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $status = $request->query('status', 'all');
        $applicantType = $request->query('applicant_type', 'all');

        // Stats calculation
        $totalBookings = $bookings->count();
        $totalRevenue = 0;
        $totalInternal = 0;
        $totalExternal = 0;
        
        foreach ($bookings as $booking) {
            $isInternal = ($booking->user->instansi ?? 'umum') === 'internal';
            if ($isInternal) {
                $totalInternal++;
            } else {
                $totalExternal++;
                
                $durasiJam = 0;
                if ($booking->waktu_mulai && $booking->waktu_selesai) {
                    $start = Carbon::parse($booking->waktu_mulai);
                    $end = Carbon::parse($booking->waktu_selesai);
                    $durasiJam = max(1, $start->diffInHours($end));
                }
                
                $roomPrice = $booking->ruangan->harga_per_jam ?? 0;
                
                if (in_array($booking->status, ['approved', 'completed'])) {
                    $totalRevenue += ($roomPrice * $durasiJam);
                }
            }
        }

        return view('admin.reports.print', compact(
            'bookings', 
            'startDate', 
            'endDate', 
            'status', 
            'applicantType',
            'totalBookings',
            'totalRevenue',
            'totalInternal',
            'totalExternal'
        ));
    }

    /**
     * Shared filter query logic.
     */
    private function getFilteredBookings(Request $request)
    {
        $query = Peminjaman::with(['user', 'ruangan']);

        // Filter by Date Range
        if ($request->filled('start_date')) {
            $query->where('tanggal_mulai', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('tanggal_mulai', '<=', $request->end_date);
        }

        // Filter by Status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by Applicant Type
        if ($request->filled('applicant_type') && $request->applicant_type !== 'all') {
            if ($request->applicant_type === 'internal') {
                $query->whereHas('user', function ($q) {
                    $q->where('instansi', 'internal');
                });
            } else {
                $query->whereHas('user', function ($q) {
                    $q->where('instansi', '!=', 'internal');
                });
            }
        }

        return $query->latest('tanggal_mulai')->get();
    }
}

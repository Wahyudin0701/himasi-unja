<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Keuangan - HIMASI</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            color: #333333;
            margin: 0;
            padding: 30px;
            font-size: 14px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #111;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 0 0 10px 0;
            color: #111;
        }
        .header p {
            font-size: 16px;
            margin: 0;
            color: #555;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            color: #111;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #cccccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-size: 12px;
            text-transform: uppercase;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .text-emerald {
            color: #047857; /* Emerald 700 */
        }
        .text-red {
            color: #b91c1c; /* Red 700 */
        }
        .font-bold {
            font-weight: bold;
        }
        .summary-box {
            border: 1px solid #cccccc;
            background-color: #f9fafb;
            padding: 20px;
            margin-bottom: 40px;
            width: 50%;
        }
        .summary-row {
            margin-bottom: 10px;
        }
        .summary-label {
            font-size: 13px;
            font-weight: bold;
            color: #555;
            display: inline-block;
            width: 150px;
        }
        .summary-value {
            font-size: 14px;
            font-weight: bold;
        }
        .summary-divider {
            border-top: 1px solid #ccc;
            margin: 15px 0;
        }
        .signature-box {
            width: 100%;
            margin-top: 50px;
        }
        .signature-content {
            float: right;
            text-align: center;
            width: 250px;
        }
        .signature-date {
            font-size: 14px;
            margin-bottom: 70px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
        .signature-title {
            font-size: 12px;
            color: #555;
            margin-top: 5px;
        }
        /* clearfix */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>

    {{-- KOP SURAT --}}
    <div class="header">
        <h1>Laporan Keuangan Kas Himasi</h1>
        <p>Periode Kepengurusan {{ $activePeriod->name ?? 'Aktif' }}</p>
    </div>

    {{-- KONTEN --}}
    <div>
        <div class="section-title">Rekapitulasi Bulanan</div>
        
        <table>
            <thead>
                <tr>
                    <th>Bulan / Tahun</th>
                    <th class="text-right">Pemasukan</th>
                    <th class="text-right">Pengeluaran</th>
                    <th class="text-right">Saldo Bersih</th>
                </tr>
            </thead>
            <tbody>
                @forelse($report as $row)
                    <tr>
                        <td class="font-bold">{{ $row['month_name'] }}</td>
                        <td class="text-right text-emerald font-bold">Rp {{ number_format($row['pemasukan'], 0, ',', '.') }}</td>
                        <td class="text-right text-red font-bold">Rp {{ number_format($row['pengeluaran'], 0, ',', '.') }}</td>
                        <td class="text-right font-bold {{ $row['saldo'] < 0 ? 'text-red' : '' }}">
                            {{ $row['saldo'] < 0 ? '-' : '' }}Rp {{ number_format(abs($row['saldo']), 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center" style="color: #888; font-style: italic; padding: 30px;">Belum ada transaksi tercatat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- RINGKASAN --}}
    <div class="summary-box">
        <div class="section-title" style="margin-bottom: 20px;">Ringkasan Keseluruhan</div>
        
        <div class="summary-row">
            <span class="summary-label">Total Pemasukan:</span>
            <span class="summary-value text-emerald">Rp {{ number_format($totalPemasukanPeriode, 0, ',', '.') }}</span>
        </div>
        
        <div class="summary-row">
            <span class="summary-label">Total Pengeluaran:</span>
            <span class="summary-value text-red">Rp {{ number_format($totalPengeluaranPeriode, 0, ',', '.') }}</span>
        </div>
        
        <div class="summary-divider"></div>
        
        <div class="summary-row">
            <span class="summary-label" style="font-size: 16px; color: #111;">SALDO AKHIR:</span>
            <span class="summary-value" style="font-size: 16px; color: #111;">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- TANDA TANGAN --}}
    <div class="signature-box clearfix">
        <div class="signature-content">
            <div class="signature-date">Jambi, {{ now()->translatedFormat('d F Y') }}</div>
            <div class="signature-name">{{ Auth::user()->name }}</div>
            <div class="signature-title">Bendahara HIMASI</div>
        </div>
    </div>

</body>
</html>

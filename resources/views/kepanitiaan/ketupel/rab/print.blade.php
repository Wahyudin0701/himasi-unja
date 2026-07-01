<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Draft RAB - {{ $event->name }}</title>
    <!-- Use Tailwind CDN for simple print styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                background-color: white;
                color: black;
            }
            @page {
                size: A4 portrait;
                margin: 1.5cm;
            }
            .print-button {
                display: none;
            }
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f8fafc;
        }
        .container-print {
            max-width: 21cm;
            margin: 0 auto;
            background: white;
            padding: 2cm 1.5cm;
            min-height: 29.7cm;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }
        @media print {
            .container-print {
                box-shadow: none;
                padding: 0;
                margin: 0;
            }
        }
    </style>
</head>
<body class="py-8">
    
    <!-- Floating Print Button -->
    <div class="fixed top-4 right-4 print-button">
        <button onclick="window.print()" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-lg flex items-center gap-2 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Cetak / Download PDF
        </button>
    </div>

    <div class="container-print">
        <!-- Header -->
        <div class="text-center mb-8 border-b-2 border-black pb-4">
            <h1 class="text-2xl font-black uppercase mb-1">Rencana Anggaran Biaya (RAB)</h1>
            <h2 class="text-xl font-bold uppercase">{{ $event->name }}</h2>
        </div>

        @if($rabs->count() > 0)
            @foreach($rabs as $divisionId => $divisionRabs)
                @php
                    $divisionName = $divisionRabs->first()->division->name ?? 'Tim Inti';
                    $divTotal = $divisionRabs->sum('total_price');
                @endphp
                <div class="mb-8">
                    <h3 class="text-lg font-bold mb-3 border-l-4 border-black pl-3 uppercase">Divisi: {{ $divisionName }}</h3>
                    
                    <table class="w-full border-collapse border border-slate-300 text-sm mb-2">
                        <thead>
                            <tr class="bg-slate-100">
                                <th class="border border-slate-300 px-3 py-2 text-left w-12 text-center">No</th>
                                <th class="border border-slate-300 px-3 py-2 text-left">Nama Barang</th>
                                <th class="border border-slate-300 px-3 py-2 text-center w-24">Jumlah</th>
                                <th class="border border-slate-300 px-3 py-2 text-center w-24">Satuan</th>
                                <th class="border border-slate-300 px-3 py-2 text-right w-36">Harga Satuan</th>
                                <th class="border border-slate-300 px-3 py-2 text-right w-36">Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($divisionRabs as $idx => $rab)
                            <tr>
                                <td class="border border-slate-300 px-3 py-2 text-center">{{ $idx + 1 }}</td>
                                <td class="border border-slate-300 px-3 py-2">{{ $rab->item_name }}</td>
                                <td class="border border-slate-300 px-3 py-2 text-center">{{ $rab->quantity }}</td>
                                <td class="border border-slate-300 px-3 py-2 text-center">{{ $rab->unit }}</td>
                                <td class="border border-slate-300 px-3 py-2 text-right">Rp {{ number_format($rab->unit_price, 0, ',', '.') }}</td>
                                <td class="border border-slate-300 px-3 py-2 text-right">Rp {{ number_format($rab->total_price, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            <!-- Subtotal -->
                            <tr class="bg-slate-50 font-bold">
                                <td colspan="5" class="border border-slate-300 px-3 py-2 text-right">Subtotal {{ $divisionName }}</td>
                                <td class="border border-slate-300 px-3 py-2 text-right">Rp {{ number_format($divTotal, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endforeach

            <!-- Grand Total -->
            <div class="mt-8 pt-4 border-t-2 border-black flex justify-end">
                <div class="w-1/2">
                    <table class="w-full text-base font-black">
                        <tr>
                            <td class="text-right py-2 pr-4 uppercase">Grand Total Anggaran:</td>
                            <td class="text-right py-2 w-48 border-b-2 border-black">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        @else
            <div class="text-center py-12 text-slate-500 italic border-2 border-dashed border-slate-300">
                Belum ada draft RAB yang diajukan dari divisi manapun.
            </div>
        @endif
        
        <!-- Signatures (Optional / Placeholder) -->
        <div class="mt-20 pt-8 grid grid-cols-2 gap-8 text-center text-sm">
            <div>
                <p class="mb-20">Bendahara Pelaksana</p>
                <p class="font-bold underline">(.......................................)</p>
            </div>
            <div>
                <p class="mb-20">Ketua Pelaksana</p>
                <p class="font-bold underline">(.......................................)</p>
            </div>
        </div>
    </div>

</body>
</html>

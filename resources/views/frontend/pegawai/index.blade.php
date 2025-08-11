<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Keadaan Hakim/Pegawai Pengadilan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Tailwind (opsional). Kalau proyekmu sudah pakai Vite+Tailwind, gunakan @vite di bawah. --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @media print {
            .no-print { display: none !important; }
            table { page-break-inside: auto; }
            tr    { page-break-inside: avoid; page-break-after: auto; }
        }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold">Keadaan Hakim/Pegawai Pengadilan</h1>
                <p class="text-sm text-gray-600 mt-1">Terakhir diperbarui: {{ now()->format('d M Y H:i') }} WIB</p>
            </div>
        </div>

        <div class="mt-6 overflow-x-auto bg-white border rounded-xl">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left">No</th>
                        <th class="px-4 py-3 text-left">NIP</th>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">Pangkat/Gol</th>
                        <th class="px-4 py-3 text-left">Tempat/Tgl Lahir</th>
                        <th class="px-4 py-3 text-left">TMT Gol</th>
                        <th class="px-4 py-3 text-left">Jabatan</th>
                        <th class="px-4 py-3 text-left">TMT Pegawai</th>
                        <th class="px-4 py-3 text-left">Pendidikan (Thn)</th>
                        <th class="px-4 py-3 text-left">KGB YAD</th>
                        <th class="px-4 py-3 text-left">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                @forelse ($pegawais as $i => $p)
                    <tr>
                        <td class="px-4 py-3 align-top">{{ ($pegawais->firstItem() ?? 1) + $i }}</td>
                        <td class="px-4 py-3 align-top">{{ $p->nip ?? '-' }}</td>
                        <td class="px-4 py-3 align-top font-medium">{{ $p->user->name ?? '-' }}</td>
                        <td class="px-4 py-3 align-top">{{ $p->pangkat_golongan ?? '-' }}</td>
                        <td class="px-4 py-3 align-top">
                            {{ $p->tempat_lahir ?? '-' }}{{ $p->tempat_lahir && $p->tanggal_lahir ? ', ' : '' }}
                            {{ $p->tanggal_lahir ? \Illuminate\Support\Carbon::parse($p->tanggal_lahir)->translatedFormat('d M Y') : '-' }}
                        </td>
                        <td class="px-4 py-3 align-top">
                            {{ $p->tmt_golongan ? \Illuminate\Support\Carbon::parse($p->tmt_golongan)->translatedFormat('d M Y') : '-' }}
                        </td>
                        <td class="px-4 py-3 align-top">{{ $p->jabatan ?? '-' }}</td>
                        <td class="px-4 py-3 align-top">
                            {{ $p->tmt_pegawai ? \Illuminate\Support\Carbon::parse($p->tmt_pegawai)->translatedFormat('d M Y') : '-' }}
                        </td>
                        <td class="px-4 py-3 align-top">
                            {{ $p->pendidikan_terakhir ?? '-' }}
                            @if($p->tahun_pendidikan) ({{ $p->tahun_pendidikan }}) @endif
                        </td>
                        <td class="px-4 py-3 align-top">
                            {{ $p->kgb_yad ? \Illuminate\Support\Carbon::parse($p->kgb_yad)->translatedFormat('d M Y') : '-' }}
                        </td>
                        <td class="px-4 py-3 align-top">{{ $p->keterangan ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="px-4 py-6 text-center text-gray-500">Tidak ada data pegawai.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if ($pegawais->hasPages())
            <div class="mt-4">
                {{ $pegawais->links() }}
            </div>
        @endif
    </div>
</body>
</html>

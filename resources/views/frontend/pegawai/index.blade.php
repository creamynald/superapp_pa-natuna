<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Keadaan Hakim/Pegawai Pengadilan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Pakai Tailwind via Vite (hapus jika tidak pakai Vite) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @media print {
            .no-print { display: none !important; }
            .ttd-ketua { position: fixed; right: 40px; bottom: 40px; text-align: center; }
            .print-bg { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
        }
        @media screen {
            .print-only { display: none; }
        }
    </style>
</head>
<body class="bg-emerald-50/40 text-slate-900 antialiased">

    {{-- HEADER HIJAU (CENTER) --}}
    <header class="print-bg bg-gradient-to-r from-emerald-700 via-emerald-600 to-emerald-700 text-white">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <div class="flex flex-col items-center gap-3">
                {{-- Logo opsional --}}
                @isset($logoUrl)
                    <img src="{{ $logoUrl }}" alt="Logo" class="h-14 w-auto opacity-95">
                @endisset

                <h1 class="text-2xl md:text-3xl font-semibold tracking-wide text-center">
                    Keadaan Hakim/Pegawai Pengadilan
                </h1>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-8">
        {{-- KARTU TABEL --}}
        <div class="rounded-2xl bg-white shadow-lg ring-1 ring-emerald-100 overflow-hidden">
            {{-- TABEL --}}
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="print-bg bg-emerald-600/95 text-white sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">No</th>
                            <th class="px-4 py-3 text-left font-semibold">NIP</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama</th>
                            <th class="px-4 py-3 text-left font-semibold">Pangkat/Gol</th>
                            <th class="px-4 py-3 text-left font-semibold">Tempat/Tgl Lahir</th>
                            <th class="px-4 py-3 text-left font-semibold">TMT Gol</th>
                            <th class="px-4 py-3 text-left font-semibold">Jabatan</th>
                            <th class="px-4 py-3 text-left font-semibold">TMT Pegawai</th>
                            <th class="px-4 py-3 text-left font-semibold">Pendidikan (Thn)</th>
                            <th class="px-4 py-3 text-left font-semibold">KGB YAD</th>
                            <th class="px-4 py-3 text-left font-semibold">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-emerald-100">
                    @forelse ($pegawais as $i => $p)
                        <tr class="odd:bg-white even:bg-emerald-50/40 hover:bg-emerald-100/40 transition">
                            <td class="px-4 py-3 align-top text-slate-700">
                                {{ ($pegawais->firstItem() ?? 1) + $i }}
                            </td>
                            <td class="px-4 py-3 align-top tabular-nums">
                                {{ $p->nip ?? '-' }}
                            </td>
                            <td class="px-4 py-3 align-top font-medium text-slate-900">
                                {{ $p->user->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3 align-top">
                                @if($p->pangkat_golongan)
                                    <span class="inline-flex items-center rounded-full bg-emerald-100 text-emerald-800 ring-1 ring-emerald-300 px-2 py-0.5">
                                        {{ $p->pangkat_golongan }}
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 align-top">
                                {{ $p->tempat_lahir ?? '-' }}@if($p->tempat_lahir && $p->tanggal_lahir), @endif
                                {{ $p->tanggal_lahir ? \Illuminate\Support\Carbon::parse($p->tanggal_lahir)->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="px-4 py-3 align-top">
                                {{ $p->tmt_golongan ? \Illuminate\Support\Carbon::parse($p->tmt_golongan)->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="px-4 py-3 align-top">
                                @php
                                    $jab = strtolower($p->jabatan ?? '');
                                    $jabClass = 'bg-slate-100 text-slate-800 ring-slate-200';
                                    if (str_contains($jab,'ketua')) $jabClass = 'bg-emerald-100 text-emerald-800 ring-emerald-200';
                                    elseif (str_contains($jab,'wakil')) $jabClass = 'bg-lime-100 text-lime-800 ring-lime-200';
                                    elseif (str_contains($jab,'hakim')) $jabClass = 'bg-teal-100 text-teal-800 ring-teal-200';
                                    elseif (str_contains($jab,'panitera')) $jabClass = 'bg-cyan-100 text-cyan-800 ring-cyan-200';
                                    elseif (str_contains($jab,'sekretaris')) $jabClass = 'bg-emerald-50 text-emerald-900 ring-emerald-200';
                                @endphp
                                <span class="inline-flex items-center rounded-lg ring-1 px-2 py-0.5 {{ $jabClass }}">
                                    {{ $p->jabatan ?? '-' }}
                                </span>
                            </td>
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
                            <td class="px-4 py-3 align-top">
                                {{ $p->keterangan ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-6 py-10 text-center text-slate-500">
                                Tidak ada data pegawai.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if ($pegawais->hasPages())
                <div class="px-4 py-4 border-t bg-emerald-50/60">
                    {{ $pegawais->links() }}
                </div>
            @endif
        </div>

        {{-- TTD KETUA --}}
        <div class="mt-10 flex justify-end">
            <div class="ttd-ketua">
                <p class="text-slate-800">Ketua Pengadilan</p>
                <div class="h-20"></div> {{-- ruang tanda tangan --}}
                <p class="font-semibold underline">{{ $ketua->user->name ?? 'Nama Ketua' }}</p>
                <p class="text-slate-700">NIP. {{ $ketua->nip ?? '123456789' }}</p>
            </div>
        </div>

        <!-- footer -->
        <footer class="mt-10 text-center text-sm text-slate-500">
            <p>Hak Cipta &copy; {{ now()->year }} Pengadilan Agama Natuna</p>
            <p class="no-print">Terakhir diperbarui: {{ now()->format('d M Y H:i') }} WIB</p>
        </footer>
    </main>
</body>
</html>

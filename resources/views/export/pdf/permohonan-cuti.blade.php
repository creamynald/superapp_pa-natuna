<?php
\Carbon\Carbon::setLocale('id');
setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page {
            margin: 28.35pt 35.4pt 21.3pt 63.8pt; /* Sesuai WordSection1 */
            size: A4;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            margin: 0;
            padding: 0;
            line-height: 1.15;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6pt;
        }
        td {
            border: 0.75pt solid #000;
            padding: 0in 5.4pt; /* Padding seperti Word */
            vertical-align: top;
            font-size: 9pt;
        }
        .section-title {
            font-weight: bold;
            padding-left: 21.3pt;
            text-indent: -21.3pt;
            margin-bottom: 0pt;
        }
        .list-item {
            padding-left: 21.3pt;
            text-indent: -21.3pt;
        }
        .right { text-align: right; }
        .center { text-align: center; }
        .justify { text-align: justify; }
        .no-indent { text-indent: 0; }

        /* Lebar kolom sesuai Word */
        .w-69 { width: 13.7%; }   /* ~69.2pt */
        .w-205 { width: 40.9%; }  /* ~205.55pt */
        .w-70 { width: 14.0%; }   /* ~70.85pt */
        .w-156 { width: 31.1%; }  /* ~155.95pt */

        .w-161 { width: 32.1%; }  /* ~161.35pt */
        .w-71 { width: 14.1%; }   /* ~70.9pt */
        .w-205 { width: 40.9%; }  /* ~205.25pt */
        .w-64 { width: 12.8%; }   /* ~64.05pt */

        .w-48 { width: 9.6%; }    /* ~47.95pt */
        .w-28 { width: 5.6%; }    /* ~28.35pt */
        .w-92 { width: 18.3%; }   /* ~92.15pt */
        .w-106 { width: 21.1%; }  /* ~106.3pt */
        .w-120 { width: 24.0%; }  /* ~120.5pt */

        .w-41 { width: 8.2%; }    /* ~40.85pt */
        .w-43 { width: 8.5%; }    /* ~42.55pt */
        .w-78 { width: 15.5%; }   /* ~77.95pt */

        .w-303 { width: 60.4%; }  /* ~303.1pt */
        .w-57 { width: 11.3%; }   /* ~56.7pt */
        .w-142 { width: 28.3%; }  /* ~141.75pt */

        .w-76 { width: 15.2%; }   /* ~76.3pt */
        .w-106 { width: 21.1%; }  /* ~106.3pt */
        .w-121 { width: 24.1%; }  /* ~120.5pt */
        .w-198 { width: 39.5%; }  /* ~198.45pt */

        /* Jarak antara judul bagian dan tabel */
        .section-header {
            margin-bottom: 6pt;
        }
    </style>
</head>
<body>

<!-- Header -->
<p style="text-align:left; text-indent:269.35pt; line-height:115%;">Ranai, {{ $cuti->created_at->translatedFormat('d F Y') }}</p>
<p style="text-align:left; text-indent:269.35pt;">Kepada</p>
<p style="text-align:left; text-indent:269.35pt;">Yth. Ketua Pengadilan Agama Natuna</p>
<p style="text-align:left; text-indent:269.35pt;">Di-</p>
<p style="margin-left:18.65pt; text-align:left; text-indent:269.35pt;">Ranai</p>
<p>&nbsp;</p>
<div class="center">
    <p>FORMULIR PERMINTAAN DAN PEMBERIAN CUTI</p>
    <p>Nomor :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $cuti->nomor_surat ?? '/KPA.W32-A4/KP5.3/VI/' . now()->year }}</p>
</div>

<!-- I. DATA PEGAWAI -->
<table>
    <tr><td colspan="4" class="section-title section-header">I.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; DATA PEGAWAI</td></tr>
    <tr>
        <td class="w-69">Nama</td>
        <td class="w-205">{{ $cuti->employee_snapshot['name'] ?? 'Nama Masih Kosong' }}</td>
        <td class="w-70">NIP</td>
        <td class="w-156">{{ $cuti->employee_snapshot['nip'] ?? 'NIP Masih Kosong' }}</td>
    </tr>
    <tr>
        <td class="w-69">Jabatan</td>
        <td class="w-205">{{ $cuti->employee_snapshot['jabatan'] ?? 'Jabatan Masih Kosong' }}</td>
        <td class="w-70">Gol. Ruang</td>
        <td class="w-156">{{ $cuti->employee_snapshot['gol'] ?? 'Golongan Masih Kosong' }}</td>
    </tr>
    <tr>
        <td class="w-69">Unit Kerja</td>
        <td class="w-205">Pengadilan Agama Natuna</td>
        <td class="w-70">Masa Kerja</td>
        <td class="w-156">{{ $cuti->employee_snapshot['masa_kerja'] ?? 'Masa Kerja Masih Kosong' }}</td>
    </tr>
</table>

<!-- II. JENIS CUTI -->
<table>
    <tr><td colspan="4" class="section-title">II.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; JENIS CUTI YANG DIAMBIL **</td></tr>
    <tr>
        <td class="w-161 list-item">1.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Cuti Tahunan</td>
        <td class="w-78 center">{{ $cuti->isLeaveType('Cuti Tahunan') ? '√' : '-' }}</td>
        <td class="w-184 list-item">2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Cuti Besar</td>
        <td class="w-78 center">{{ $cuti->isLeaveType('Cuti Besar') ? '√' : '-' }}</td>
    </tr>
    <tr>
        <td class="w-161 list-item">3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Cuti Sakit</td>
        <td class="w-78 center">{!! $cuti->isLeaveType('Cuti Sakit') ? '<span style="font-family: DejaVu Sans, sans-serif;">✓</span>' : '-' !!}</td>
        <td class="w-184 list-item">4.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Cuti Melahirkan</td>
        <td class="w-78 center">{{ $cuti->isLeaveType('Cuti Melahirkan') ? '√' : '-' }}</td>
    </tr>
    <tr>
        <td class="w-161 list-item">5.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Cuti Karena Alasan Penting</td>
        <td class="w-78 center">{{ $cuti->isLeaveType('Cuti Karena Alasan Penting') ? '√' : '-' }}</td>
        <td class="w-184 list-item">6.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Cuti di Luar Tanggungan Negara</td>
        <td class="w-78 center">{{ $cuti->isLeaveType('Cuti di Luar Tanggungan Negara') ? '√' : '-' }}</td>
    </tr>
</table>

<!-- III. ALASAN CUTI -->
<table>
    <tr><td class="section-title">III.&nbsp;&nbsp;&nbsp;&nbsp; ALASAN CUTI</td></tr>
    <tr><td>{{ $cuti->reason ?? '-' }}</td></tr>
</table>

<!-- IV. LAMANYA CUTI -->
<table>
    <tr><td colspan="7" class="section-title">IV.&nbsp;&nbsp;&nbsp;&nbsp; LAMANYA CUTI</td></tr>
    <tr>
        <td class="w-48">Selama</td>
        <td class="w-28 right">{{ $cuti->duration_days }}</td>
        <td class="w-92 justify">(hari/<s>bulan/tahun</s>)*</td>
        <td class="w-78">Mulai tanggal</td>
        <td class="w-106 center">{{ $cuti->start_date->translatedFormat('d F Y') }}</td>
        <td class="w-28 center">s.d.</td>
        <td class="w-120 center">{{ $cuti->end_date->translatedFormat('d F Y') }}</td>
    </tr>
</table>

<!-- V. CATATAN CUTI -->
<table>
    <tr><td colspan="6" class="section-title">V.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; CATATAN CUTI***</td></tr>
    <tr>
        <td colspan="3" class="w-161 list-item">1.&nbsp;&nbsp;&nbsp;&nbsp; CUTI TAHUNAN</td>
        <td rowspan="2" class="w-71 center">Paraf Petugas Cuti</td>
        <td class="w-205 list-item">2.&nbsp;&nbsp;&nbsp;&nbsp; CUTI BESAR</td>
        <td class="w-64 justify">-&nbsp;&nbsp;&nbsp;&nbsp; Hari</td>
    </tr>
    <tr>
        <td class="w-41 center">Tahun</td>
        <td class="w-43 center">Sisa</td>
        <td class="w-78 center">Keterangan</td>
        <td class="w-205 list-item">3.&nbsp;&nbsp;&nbsp;&nbsp; CUTI SAKIT</td>
        <td class="w-64 justify">{{ $cuti->isLeaveType('Cuti Sakit') ? $cuti->duration_days : '-' }}&nbsp;&nbsp;&nbsp;&nbsp; Hari</td>
    </tr>
    <tr>
        <td class="w-41 center">2022</td>
        <td class="w-43 center">-</td>
        <td class="w-78 center">-</td>
        <td rowspan="3" class="w-71">&nbsp;</td>
        <td class="w-205 list-item">4.&nbsp;&nbsp;&nbsp;&nbsp; CUTI MELAHIRKAN</td>
        <td class="w-64 justify">-&nbsp;&nbsp;&nbsp;&nbsp; Hari</td>
    </tr>
    <tr>
        <td class="w-41 center">2023</td>
        <td class="w-43 center">-</td>
        <td class="w-78 center">-</td>
        <td class="w-205 list-item">5.&nbsp;&nbsp;&nbsp;&nbsp; CUTI KARENA ALASAN PENTING</td>
        <td class="w-64 justify">-&nbsp;&nbsp;&nbsp;&nbsp; Hari</td>
    </tr>
    <tr>
        <td class="w-41 center">2024</td>
        <td class="w-43 center">-</td>
        <td class="w-78 center">-</td>
        <td class="w-205 list-item">6.&nbsp;&nbsp;&nbsp;&nbsp; CUTI DI LUAR TANGGUNGAN NEGARA</td>
        <td class="w-64 justify">-&nbsp;&nbsp;&nbsp;&nbsp; Hari</td>
    </tr>
</table>

<!-- VI. ALAMAT -->
<table>
    <tr><td colspan="3" class="section-title">VI.&nbsp;&nbsp;&nbsp;&nbsp; ALAMAT SELAMA MENJALANKAN CUTI</td></tr>
    <tr>
        <td rowspan="2" class="w-303 justify">
            {{ $cuti->address_on_leave ?? 'Jl. Hang Tuah No. 93 RT. 05 / RW. 02 Kelurahan Ranai, Kecamatan Bunguran Timur, Kabupaten Natuna' }}
        </td>
        <td class="w-57">TELP</td>
        <td class="w-142">{{ $cuti->phone_on_leave ?? '0822 6851 5766' }}</td>
    </tr>
    <tr>
        <td colspan="2" class="w-199 justify">
            <p>Hormat Saya,</p>
            <p>&nbsp;</p>
            <p>{{ $cuti->employee_snapshot['name'] ?? 'Renaldi' }}</p>
            <p>NIP. {{ $cuti->employee_snapshot['nip'] ?? '199806202025061009' }}</p>
        </td>
    </tr>
</table>

<!-- VII. PERTIMBANGAN ATASAN -->
<table>
    <tr><td colspan="4" class="section-title">VII.&nbsp;&nbsp;&nbsp; &nbsp;PERTIMBANGAN ATASAN LANGSUNG**</td></tr>
    <tr>
        <td class="w-76">DISETUJUI</td>
        <td class="w-106">PERUBAHAN**</td>
        <td class="w-121">DITANGGUHKAN***</td>
        <td class="w-198">TIDAK DISETUJUI****</td>
    </tr>
    <tr>
        <td class="w-76">&nbsp;</td>
        <td class="w-106">&nbsp;</td>
        <td class="w-121">&nbsp;</td>
        <td class="w-198">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" class="w-303">Catatan :</td>
        <td class="w-198 justify">
            Panitera,<br><br><br>
            Edy Efrizal<br>
            NIP. 198404042009041012
        </td>
    </tr>
</table>

<!-- VIII. KEPUTUSAN PEJABAT -->
<table>
    <tr><td colspan="4" class="section-title">VIII.&nbsp;&nbsp; KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI**</td></tr>
    <tr>
        <td class="w-76">DISETUJUI</td>
        <td class="w-106">PERUBAHAN**</td>
        <td class="w-121">DITANGGUHKAN***</td>
        <td class="w-198">TIDAK DISETUJUI****</td>
    </tr>
    <tr>
        <td class="w-76">&nbsp;</td>
        <td class="w-106">&nbsp;</td>
        <td class="w-121">&nbsp;</td>
        <td class="w-198">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" class="w-303">Catatan :</td>
        <td class="w-198 justify">
            Ketua,<br><br><br>
            Sardianto<br>
            NIP. 198305122009041004
        </td>
    </tr>
</table>
</body>
</html>
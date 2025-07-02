<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Laporan Berkas Perkara' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 80px;
            float: left;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .header p {
            margin: 2px 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
    </style>
</head>
<body onload="window.print()">

<div class="header">
    <img src="{{ public_path('images/logo-pa.png') }}" alt="Logo Pengadilan">
    <h2>PENGADILAN AGAMA NATUNA</h2>
    <p>Jl. Karya Bakti No. 1, Natuna, Kepulauan Riau</p>
    <p>Email: pa_natuna@pn-natuna.go.id | Telp: (0773) 1234567</p>
    <h3>Laporan Berkas Perkara</h3>
    <p>Tanggal Cetak: {{ now()->format('d F Y H:i') }}</p>
</div>

{!! $dataTable !!}

<div class="footer">
    <p>Dicetak oleh: {{ auth()->user()->name ?? 'Sistem' }}</p>
</div>

</body>
</html>
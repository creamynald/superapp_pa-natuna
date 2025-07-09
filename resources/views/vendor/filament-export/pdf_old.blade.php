<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>{{ $fileName }}</title>
    <style type="text/css" media="all">
        * {
            font-family: DejaVu Sans, sans-serif !important;
        }

        body {
            margin: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header img {
            width: 80px;
            vertical-align: middle;
        }

        .header h2 {
            margin: 5px 0 0 0;
            font-size: 18px;
            font-weight: bold;
        }

        .header p {
            margin: 2px 0;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ededed;
            padding: 8px;
            font-size: 12px;
            word-break: break-word;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>

<div class="header">
    <img src="{{ public_path('images/logo-pa.png') }}" alt="Logo Pengadilan Agama">
    <h2>PENGADILAN AGAMA NATUNA</h2>
    <p>Jl. Karya Bakti No. 1, Natuna, Kepulauan Riau</p>
    <p>Email: pa_natuna@pn-natuna.go.id | Telp: (0773) 1234567</p>
</div>

<table>
    <thead>
        <tr>
            @foreach ($columns as $column)
                <th>{{ $column->getLabel() }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($rows as $row)
            <tr>
                @foreach ($columns as $column)
                    <td>{{ $row[$column->getName()] }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    <p>Dicetak oleh: {{ auth()->user()->name ?? 'Sistem' }}</p>
    <p>Pada tanggal: {{ now()->format('d F Y H:i') }}</p>
    <p>NB : {{ $fileName }}</p>
</div>

</body>
</html>
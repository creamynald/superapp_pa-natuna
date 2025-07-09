<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Saksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .center {
            text-align: center;
        }
        h2, h3 {
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <h2 class="center">DATA SAKSI</h2>
    <p class="center">PENGADILAN AGAMA NATUNA</p>

    @foreach ($grouped as $nomorPerkara => $saksis)
        <table>
            <tr>
                <td>No. Perkara :</td>
                <td>{{ $nomorPerkara }}</td>
                <td>No. Antrian Sidang :</td>
                <td>-</td>
            </tr>
        </table>

        @foreach ($saksis as $index => $saksi)
            <h3 class="center">SAKSI {{ $index + 1 }}</h3>

            <table>
                <tr>
                    <td>Nama Saksi {{ $index + 1 }}</td>
                    <td>{{ $saksi['nama_lengkap'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Bin/Binti:</td>
                    <td>{{ $saksi['bin_binti'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Tempat/Tgl Lahir</td>
                    <td>{{ $saksi['tempat_tanggal_lahir'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>No NIK KTP</td>
                    <td>{{ $saksi['nik'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>No Hp/Wa</td>
                    <td>{{ $saksi['no_hp'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>E-Mail</td>
                    <td>{{ $saksi['email'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>{{ $saksi['alamat'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>{{ $saksi['jenis_kelamin'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Agama</td>
                    <td>{{ $saksi['agama'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Pekerjaan</td>
                    <td>{{ $saksi['pekerjaan'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Pendidikan Terakhir</td>
                    <td>{{ $saksi['pendidikan'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Status Kawin</td>
                    <td>{{ $saksi['status_kawin'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Hubungan dgn P/T</td>
                    <td>{{ $saksi['hubungan_dengan_penggugat_tergugat'] ?? '-' }}</td>
                </tr>
            </table>
        @endforeach
    @endforeach

</body>
</html>
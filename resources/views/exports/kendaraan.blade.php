<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ $judul ?? 'Data Kendaraan' }}</title>

    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #333;
        }

        h2 {
            text-align: center;
            color: #05652B;
            margin-bottom: 5px;
        }

        p {
            text-align: center;
            margin-bottom: 20px;
            font-size: 9px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px 4px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #0B8F3A;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #F5F7F6;
        }

        .footer {
            margin-top: 15px;
            text-align: right;
            font-size: 8px;
        }
    </style>
</head>

<body>
    <h2>{{ $judul ?? 'Data Kendaraan' }}</h2>
    <p>PT Green Planet Indonesia</p>

    <table>
        <thead>
            <tr>
                @foreach ($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $row)
                <tr>
                    @foreach ($row as $value)
                        <td>{{ $value ?? '-' }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headers) }}" style="text-align: center;">
                        Tidak ada data untuk ditampilkan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ date('d-m-Y H:i') }}
    </div>
</body>

</html>
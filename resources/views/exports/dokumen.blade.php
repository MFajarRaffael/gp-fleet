<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ $judul }}</title>

    <style>
        @page {
            margin: 18px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 8px;
            color: #333;
        }

        h2 {
            text-align: center;
            color: #05652B;
            margin-bottom: 5px;
        }

        p {
            text-align: center;
            margin-bottom: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 5px 3px;
            text-align: left;
            vertical-align: top;
            overflow-wrap: break-word;
        }

        th {
            background-color: #0B8F3A;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #F5F7F6;
        }

        thead {
            display: table-header-group;
        }

        tr {
            page-break-inside: avoid;
        }

        .footer {
            margin-top: 12px;
            text-align: right;
            font-size: 7px;
        }
    </style>
</head>

<body>
    <h2>PT GREEN PLANET INDONESIA</h2>
    <p>{{ $judul }} - GP Fleet Management</p>

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
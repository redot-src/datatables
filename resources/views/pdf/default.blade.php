@php
    $locale = app()->getLocale();
    $direction = stripos($locale, 'ar') === 0 ? 'rtl' : 'ltr';
@endphp

<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $direction }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('datatables::datatable.export') {{ date('Y-m-d H:i:s') }}</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            line-height: 1.6;
            font-size: 14px;
            color: #333;
            background-color: #fff;
        }

        @page {
            margin: 1cm;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            color: #111;
            margin: 10px 0;
        }

        p {
            margin: 10px 0;
            text-align: justify;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: start;
        }

        table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        ul,
        ol {
            margin: 10px 0;
            padding-inline-start: 20px;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }


        .page-break {
            page-break-before: always;
        }
    </style>

    @stack('styles')
</head>

<body>
    <main>
        <table>
            <thead>
                <tr>
                    @foreach ($headings as $heading)
                        <th>{{ $heading }}</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach ($rows as $row)
                    <tr>
                        @foreach ($row as $cell)
                            <td>{!! $cell !!}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
</body>

</html>

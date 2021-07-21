<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Cetak Label</title>
    <style>


        a {
            color: #5D6975;
            text-decoration: underline;
        }

        body {
            position: relative;
            width: 21cm;
            height: 29.7cm;
            margin: 0 auto;
            color: #001028;
            background: #FFFFFF;
            font-family: Arial, sans-serif;
            font-size: 12px;
            font-family: Arial;
        }


        header {
            padding: 10px 0;
            margin-bottom: 30px;
        }

        main{
            margin-right: 2% !important;
        }


        table th,
        table td {
            text-align: center;
            font-size: 1.2em;
            width: 4.5cm !important;
            height: 3cm !important;
        }

        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        }


    </style>
</head>

<body>

    <main>
        <table class="">

            <tbody>
                {{-- <tr>
                    <td>aa</td>
                    <td>a</td>
                </tr> --}}

                @forelse ($cetak as $item)
                    @if ($item != [])
                    <tr>
                    @php
                        $data = 0
                    @endphp
                        @forelse ($item as $row)
                        <td>
                            <div style="font-size: 30px">
                                {{"Rp " . number_format($row['HargaJual'],0,',','.')}}
                            </div>
                            {{-- <br> --}}
                            {{$row['Nama']}}
                            <br>
                            {{$row['KodeBarang']}}
                            <br>
                            @if ($row['KodeBarcode'] != null)
                            <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($row['KodeBarcode'], 'C39+',3,33)}}" width="100" height="40" alt="" srcset="">
                            @endif
                        </td>

                        @empty

                        @endforelse
                        @if (count($item) == 1)
                        <td></td>
                        <td></td>
                        <td></td>
                        @elseif (count($item) == 2)
                        <td></td>
                        <td></td>
                        @elseif (count($item) == 3)
                        <td></td>

                        @endif
                    </tr>
                    @endif
                @empty

                @endforelse
            </tbody>
        </table>
    </main>

</body>

</html>

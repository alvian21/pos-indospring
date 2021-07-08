<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <style>
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #5D6975;
            text-decoration: underline;
        }

        body {
            position: relative;
            width: 80mm;

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

        #logo {
            text-align: center;
            margin-bottom: 10px;
        }

        #logo img {
            width: 90px;
        }

        h1 {
            border-top: 1px solid #5D6975;
            border-bottom: 1px solid #5D6975;
            color: #5D6975;
            font-size: 2.4em;
            line-height: 1.4em;
            font-weight: normal;
            text-align: center;
            margin: 0 0 20px 0;
            background: url("../images/dimension.png");
        }

        #project {
            float: left;
        }

        #project span {
            color: #5D6975;
            text-align: right;
            width: 52px;
            margin-right: 10px;
            display: inline-block;
            font-size: 0.8em;
        }

        #company {
            float: right;
            text-align: right;
        }

        #project div,
        #company div {
            white-space: nowrap;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;
        }

        table tr:nth-child(2n-1) td {
            background: #F5F5F5;
        }

        table th,
        table td {
            /* text-align: center; */
            font-size: 1.2em;
        }

        table th {
            padding: 5px 20px;
            color: #5D6975;
            border-bottom: 1px solid #C1CED9;
            white-space: nowrap;
            font-weight: normal;
        }

        table .service,
        table .desc {
            text-align: left;
        }

        table td {
            padding: 20px;
            /* text-align: center; */
        }

        table td.service,
        table td.desc {
            vertical-align: top;
        }


        table td.grand {
            border-top: 1px solid #5D6975;
            ;
        }

        #notices .notice {
            color: #5D6975;
            font-size: 1.2em;
        }

        footer {
            color: #5D6975;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            border-top: 1px solid #C1CED9;
            padding: 8px 0;
            text-align: center;
        }

        .center {
            text-align: center;
        }
    </style>
</head>

<body>
    <header class="clearfix">
        <h3 style="text-align: left">{{date('H:i d F, Y')}}</h3>

        <h3 style="color: #34abeb; text-align:center">Koperasi Karyawan</h3>



    </header>
    <main>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Qty</th>
                    <th>@harga</th>
                    <th>Total</th>

                </tr>

            </thead>
            <tbody>
                @php
                $total = 0;
                @endphp
                @forelse ($data as $item)
                <tr>
                    <td>{{$item->Nama}}</td>
                    <td>{{$item->Jumlah}}</td>
                    <td>{{$item->Harga}}</td>
                    <td>{{$item->Harga * $item->Jumlah}}</td>
                </tr>
                @php
                $total +=$item->Harga * $item->Jumlah;
                @endphp
                @empty

                @endforelse

            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-center">Total</td>
                    <td colspan="2" align="right">{{"Rp " . number_format($total,2,',','.')}}</td>

                </tr>
            </tfoot>
        </table>

    </main>
    <script>
        setTimeout(function () { window.print(); }, 2500);
        window.onafterprint = back;

        function back() {
            window.location.href="{{route('pos.kasir.index')}}";
        }
    </script>
</body>

</html>

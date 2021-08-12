<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan | Summary</title>
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
            width: 40cm;
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
        <div id="logo">
            <img src="http://31.220.50.154/toko/assets/img/logo.png">
        </div>
        <h1 style="color: #34abeb">Laporan Penjualan | Summary</h1>

        <h3 class="center" style="color: #eb5f34">{{$periode1}} - {{$periode2}}</h3>

    </header>
    <main>
        @if ($grup == 'Group By Customer')
        <table>
            <thead>
                <tr>

                    <th rowspan="2">Customer</th>
                    <th rowspan="2">Nama</th>
                    <th rowspan="2">Subdept</th>
                    <th rowspan="2">Pangkat</th>
                    <th rowspan="2">Total</th>
                    <th colspan="3">Pembayaran</th>

                </tr>
                <tr>
                    <th>eKop</th>
                    <th>Tunai</th>
                    <th>Kredit</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporan as $item)
                <tr>
                    <td>{{$item->KodeSuppCust}}</td>
                    <td>@if ($item->Nama == null)
                        UMUM
                        @else
                        {{$item->Nama}}
                        @endif</td>
                    <td>{{$item->SubDept}}</td>
                    <td>{{$item->Pangkat}}</td>
                    <td align="right">{{"Rp " . number_format($item->TotalHarga,2,',','.')}}</td>
                    <td align="right">{{"Rp " . number_format($item->PembayaranEkop,2,',','.')}}</td>
                    <td align="right">{{"Rp " . number_format($item->PembayaranTunai,2,',','.')}}</td>
                    <td align="right">{{"Rp " . number_format($item->PembayaranKredit,2,',','.')}}</td>

                </tr>
                @empty

                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="center">Total</td>
                    <td align="right">{{"Rp " . number_format($total,2,',','.')}}</td>
                    <td align="right">{{"Rp " . number_format($ekop,2,',','.')}}</td>
                    <td align="right">{{"Rp " . number_format($tunai,2,',','.')}}</td>
                    <td align="right">{{"Rp " . number_format($kredit,2,',','.')}}</td>

                </tr>
            </tfoot>
        </table>
        @else
        <table>
            <thead>
                <tr>
                    <th rowspan="2">Penjualan</th>
                    <th rowspan="2">Lokasi</th>
                    <th rowspan="2">Tanggal</th>
                    <th rowspan="2">Nomor</th>
                    <th rowspan="2">Customer</th>
                    <th rowspan="2">Diskon</th>
                    <th rowspan="2">Pajak</th>
                    <th rowspan="2">Total</th>
                    <th colspan="3">Pembayaran</th>
                    <th rowspan="2">DueDate</th>
                </tr>
                <tr>
                    <th>eKop</th>
                    <th>Tunai</th>
                    <th>Kredit</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporan as $item)
                <tr>
                    <td align="left">{{$item['Penjualan']}}</td>
                    <td align="left">{{$item['Lokasi']}}</td>
                    <td align="left">{{$item['Tanggal']}}</td>
                    <td align="left">{{$item['Nomor']}}</td>
                    <td align="left">{{$item['Customer']}}</td>
                    <td align="right">{{"Rp " . number_format($item['Diskon'],2,',','.')}}</td>
                    <td align="right">{{"Rp " . number_format($item['Pajak'],2,',','.')}}</td>
                    <td align="right">{{"Rp " . number_format($item['Total'],2,',','.')}}</td>
                    <td align="right">{{"Rp " . number_format($item['Ekop'],2,',','.')}}</td>
                    <td align="right">{{"Rp " . number_format($item['Tunai'],2,',','.')}}</td>
                    <td align="right">{{"Rp " . number_format($item['Kredit'],2,',','.')}}</td>
                    <td align="left">{{$item['DueDate']}}</td>

                </tr>


                @empty

                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-center">Total</td>
                    <td align="right">{{"Rp " . number_format($diskon,2,',','.')}}</td>
                    <td align="right">{{"Rp " . number_format($pajak,2,',','.')}}</td>
                    <td align="right">{{"Rp " . number_format($total,2,',','.')}}</td>
                    <td align="right">{{"Rp " . number_format($ekop,2,',','.')}}</td>
                    <td align="right">{{"Rp " . number_format($tunai,2,',','.')}}</td>
                    <td align="right">{{"Rp " . number_format($kredit,2,',','.')}}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        @endif

    </main>

</body>

</html>

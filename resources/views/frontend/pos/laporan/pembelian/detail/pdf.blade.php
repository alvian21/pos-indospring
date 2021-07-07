<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Laporan Pembelian | Detail</title>
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
            width: 30cm;
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

        table td.sub {
            border-top: 1px solid #C1CED9;
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

        .span {
            font-size: 5rem
        }

        table td.total {
            font-size: 1.2em;
        }

        table td.grand {
            border-top: 1px solid #5D6975;
        }

        table td.right {
            text-align: right;
        }

        table td.left {
            text-align: left;
        }
    </style>
</head>

<body>
    <header class="clearfix">
        <h3 style="text-align: left">{{date('H:i d F, Y')}}</h3>
        <div id="logo">
            <img src="http://31.220.50.154/toko/assets/img/logo.png">
        </div>
        <h1 style="color: #34abeb">Laporan Pembelian | Detail</h1>
        <h3 class="center" style="color: #eb5f34">{{$periode1}} - {{$periode2}}</h3>
    </header>
    @forelse ($data as $item)

    <header class="clearfix">
        <div id="project">
            <div><span style="font-size: 12px">Transaksi</span>{{$item['Transaksi']}}</div>
            <div><span style="font-size: 12px">Nomor</span>{{$item['Nomor']}}</div>
            <div><span style="font-size: 12px">Lokasi</span>{{$item['LokasiTujuan']}}</div>
            <div><span style="font-size: 12px">Supplier</span>{{$item['Kode']}} - {{$item['Nama']}}</div>
            <div><span style="font-size: 12px">Tanggal</span>{{$item['Tanggal']}}</div>
        </div>

    </header>
    <main>

        <table>
            <thead>
                <tr>
                    <th>Urut</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>@Harga</th>
                    <th>Diskon %</th>
                    <th>Diskon Rp</th>
                    <th>Sub Total</th>
                </tr>

            </thead>
            <tbody>

                @php
                $total = 0;
                @endphp

                @forelse ($item['datadetail'] as $row)
                <tr>
                    <td align="right">{{$row['Urut']}}</td>
                    <td align="left">{{$row['KodeBarang']}} | {{$row['Nama']}}</td>
                    <td align="right">{{$row['Jumlah']}}</td>
                    <td align="right">{{"Rp " . number_format($row['Harga'],2,',','.')}}</td>
                    <td align="right">{{$row['DiskonPersen']}}</td>
                    <td align="right">{{"Rp " . number_format($row['DiskonTunai'],2,',','.')}}</td>
                    @php
                    $subtotal = ($row['Harga'] * $row['Jumlah']) - (($row['Harga']*
                    $row['Jumlah'])*$row['DiskonPersen']/100) - $row['DiskonTunai']
                    @endphp
                    <td align="right">{{"Rp " . number_format($subtotal,2,',','.')}}</td>
                </tr>
                @php
                $total += $subtotal
                @endphp
                @empty

                @endforelse

                {{-- diskon dan pajak --}}
                @php
                $diskon = (($item['DiskonPersen']/100) * $total) + $item['DiskonTunai'];
                $pajak = ($total - $diskon)* $item['Pajak']/100;
                @endphp
                <tr>
                    <td colspan="6" class="right">Total</td>
                    <td class="sub total" align="right">{{"Rp " . number_format($total,2,',','.')}}</td>
                </tr>
                <tr>


                    <td colspan="6" class="right">Diskon Akhir</td>
                    <td class="sub total" align="right">{{"Rp " . number_format($diskon,2,',','.')}}</td>
                </tr>
                <tr>

                    <td colspan="6" class="right">Pajak</td>
                    <td class="sub total" align="right">{{"Rp " . number_format($pajak,2,',','.')}}</td>
                </tr>
                <tr>


                    <td colspan="6" class="right">Grand Total</td>
                    <td class="sub total" align="right">
                        {{"Rp " . number_format($item['TotalHargaSetelahPajak'],2,',','.')}}</td>
                </tr>

            </tbody>

        </table>

    </main>

    @empty

    @endforelse
</body>

</html>

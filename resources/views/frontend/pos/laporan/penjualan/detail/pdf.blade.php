<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Penjualan - Rincian</title>
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
            width: 35cm;
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
            text-align: center;
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
            text-align: center;
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
        <h1 style="color: #34abeb">Penjualan - Rincian</h1>
        <div id="company" class="clearfix">
            <div>Company Name</div>
            <div>455 Foggy Heights,<br /> AZ 85004, US</div>
            <div>(602) 519-0450</div>
            <div><a href="mailto:company@example.com">company@example.com</a></div>
        </div>
        <div id="project">
            <div><span>PROJECT</span> Website development</div>
            <div><span>CLIENT</span> John Doe</div>
            <div><span>ADDRESS</span> 796 Silver Harbour, TX 79273, US</div>
            <div><span>EMAIL</span> <a href="mailto:john@example.com">john@example.com</a></div>
            <div><span>DATE</span> August 17, 2015</div>
            <div><span>DUE DATE</span> September 17, 2015</div>
        </div>

    </header>
    <main>
        <table>
            <thead>
                <tr>
                    <th rowspan="2">Urut</th>
                    <th rowspan="2">Barang</th>
                    <th rowspan="2">Jumlah</th>
                    <th rowspan="2">@Harga</th>
                    <th rowspan="2">Diskon %</th>
                    <th rowspan="2">Diskon Rp</th>
                    <th rowspan="2">Sub Total</th>
                </tr>

            </thead>
            <tbody>
                {{-- @forelse ($laporan as $item)
                <tr>
                    <td>{{$item['Penjualan']}}</td>
                    <td>{{$item['Lokasi']}}</td>
                    <td>{{$item['Tanggal']}}</td>
                    <td>{{$item['Nomor']}}</td>
                    <td>{{$item['Customer']}}</td>
                    <td>{{"Rp " . number_format($item['Diskon'],2,',','.')}}</td>
                    <td>{{"Rp " . number_format($item['Pajak'],2,',','.')}}</td>
                    <td>{{"Rp " . number_format($item['Total'],2,',','.')}}</td>
                    <td>{{"Rp " . number_format($item['Ekop'],2,',','.')}}</td>
                    <td>{{"Rp " . number_format($item['Tunai'],2,',','.')}}</td>
                    <td>{{"Rp " . number_format($item['Kredit'],2,',','.')}}</td>
                    <td>{{$item['DueDate']}}</td>

                </tr>


                @empty

                @endforelse --}}
                {{-- <tr>
                    <td colspan="4" class="sub">SUBTOTAL</td>
                    <td class="sub total">$5,200.00</td>
                  </tr>
                  <tr>
                    <td colspan="4">TAX 25%</td>
                    <td class="total">$1,300.00</td>
                  </tr>
                  <tr>
                    <td colspan="4" class="grand total">GRAND TOTAL</td>
                    <td class="grand total">$6,500.00</td>
                  </tr> --}}
            </tbody>
            <tfoot>
                <tr>
                    {{-- <td colspan="5">Total</td>
                    <td>{{"Rp " . number_format($diskon,2,',','.')}}</td>
                    <td>{{"Rp " . number_format($pajak,2,',','.')}}</td>
                    <td>{{"Rp " . number_format($total,2,',','.')}}</td>
                    <td>{{"Rp " . number_format($ekop,2,',','.')}}</td>
                    <td>{{"Rp " . number_format($tunai,2,',','.')}}</td>
                    <td>{{"Rp " . number_format($kredit,2,',','.')}}</td>
                    <td></td> --}}
                </tr>
            </tfoot>
        </table>
        <div id="details" class="clearfix">
            <div id="project">
              <div class="arrow"><div class="inner-arrow"><span>PROJECT</span> Website development</div></div>
              <div class="arrow"><div class="inner-arrow"><span>CLIENT</span> John Doe</div></div>
              <div class="arrow"><div class="inner-arrow"><span>ADDRESS</span> 796 Silver Harbour, TX 79273, US</div></div>
              <div class="arrow"><div class="inner-arrow"><span>EMAIL</span> <a href="mailto:john@example.com">john@example.com</a></div></div>
            </div>
            <div id="company">
              <div class="arrow back"><div class="inner-arrow">Company Name <span>COMPANY</span></div></div>
              <div class="arrow back"><div class="inner-arrow">455 Foggy Heights, AZ 85004, US <span>ADDRESS</span></div></div>
              <div class="arrow back"><div class="inner-arrow">(602) 519-0450 <span>PHONE</span></div></div>
              <div class="arrow back"><div class="inner-arrow"><a href="mailto:company@example.com">company@example.com</a> <span>EMAIL</span></div></div>
            </div>
          </div>
    </main>

</body>

</html>

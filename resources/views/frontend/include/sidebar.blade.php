@php
$datarole = session('data_role');

function customSearch($keyword, $arrayToSearch){
    foreach($arrayToSearch as $key => $arrayItem){
        if( stristr( $arrayItem, $keyword ) ){
            return true;
        }
    }
}

@endphp
<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <a href="#">KopKar PT.ISP</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
        <a href="#">Ts</a>
    </div>
    <ul class="sidebar-menu">
        <li class="menu-header"></li>
        <li class="nav-item @yield('dashboard')">
            <a class="nav-link" href="{{ route('dashboard.index') }}">
                <i class="fas fa-columns"></i> <span>Dashboard</span>
            </a>
        </li>

        @forelse ($datarole as $item)
        @if ($item == 'Settings - Setting')
        <li class="menu-header">SETTINGS</li>

        <li class="nav-item @yield('setting') ">
            <a class="nav-link" href="{{route('settings.mssetting.index')}}">
                <i class="fas fa-file-alt"></i> <span>Setting</span>
            </a>
        </li>

        @elseif ($item == 'Master - User')

        <li class="menu-header">MASTER</li>
        <li class="nav-item @yield('user') ">
            <a class="nav-link" href="{{route('master.user.index')}}">
                <i class="fas fa-file-alt"></i> <span>User</span>
            </a>

        </li>
        <li class="nav-item @yield('wa')">
            <a class="nav-link" href="{{route('master.wa.index')}}">
                <i class="fas fa-file-alt"></i> <span>Test Wa</span>
            </a>
        </li>
        @elseif ($item == 'Saldo - Saldo Awal Hutang dan Simpanan')
        <li class="menu-header">Saldo</li>
        <li class="nav-item @yield('saldo-awal') ">
            <a class="nav-link" href="{{route('saldo.saldo_awal.index')}}">
                <i class="fas fa-file-alt"></i> <span>Saldo awal hutang dan simpanan</span>
            </a>
        </li>

        @endif
        @empty

        @endforelse

        @if (customSearch("Koperasi",$datarole))
        <li class="menu-header">KOPERASI</li>
        @endif



        <li class="nav-item dropdown @yield('koperasi')">
            @if (customSearch("Koperasi - Master",$datarole))
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                class="fas fa-columns"></i><span>Master</span></a>
            @endif
            <ul class="dropdown-menu">
                @forelse ($datarole as $item)
                @if ($item == 'Koperasi - Master - Anggota')
                <li><a class="nav-link" href="{{route('koperasi.anggota.index')}}">Anggota</a></li>
                @elseif ($item == 'Koperasi - Master - Transaksi')
                <li><a class="nav-link" href="{{route('koperasi.transaksi.index')}}">Transaksi</a></li>
                {{-- <li><a class="nav-link" href="#">Account Lain-lain</a></li> --}}
                @elseif ($item == 'Koperasi - Master - Cicilan')
                <li><a class="nav-link" href="{{route('koperasi.cicilan.index')}}">Cicilan</a></li>
                @endif
                @empty
                @endforelse
            </ul>
        </li>

        <li class="nav-item dropdown @yield('transaksi')">
            @if (customSearch("Koperasi - Transaksi",$datarole))
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                class="fas fa-columns"></i><span>Transaksi</span></a>
            @endif
            <ul class="dropdown-menu">
                @forelse ($datarole as $item)
                @if ($item == 'Koperasi - Transaksi - Cek Saldo')
                <li><a class="nav-link" href="{{route('koperasi.saldo.index')}}">Cek Saldo</a></li>
                @elseif ($item == 'Koperasi - Transaksi - Approval Pinjaman')
                <li><a class="nav-link" href="{{route('pinjaman.index')}}">Approval Pinjaman</a></li>
                @elseif ($item == 'Koperasi - Transaksi - TopUp e-kop')
                <li><a class="nav-link" href="{{route('koperasi.topup.index')}}">TopUp e-kop</a></li>
                @elseif ($item == 'Koperasi - Transaksi - Pembayaran')
                <li><a class="nav-link" href="{{route('koperasi.pembayaran.index')}}">Pembayaran</a></li>
                @elseif ($item == 'Koperasi - Transaksi - Aktivasi e-kop')
                <li><a class="nav-link" href="{{route('koperasi.aktivasi.index')}}">Aktivasi e-kop</a></li>
                @elseif ($item == 'Koperasi - Transaksi - Proses Bulanan')
                <li><a class="nav-link" href="{{route('koperasi.proses-bulanan.index')}}">Proses Bulanan</a></li>
                @endif
                @empty
                @endforelse
            </ul>
        </li>
        <li class="nav-item dropdown @yield('laporan')">
            @if (customSearch("Koperasi - Laporan",$datarole))
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                class="fas fa-columns"></i><span>Laporan</span></a>
            @endif
            <ul class="dropdown-menu">
                @forelse ($datarole as $item)
                @if ($item == 'Koperasi - Laporan - Tagihan Penjualan Kredit')
                <li><a class="nav-link" href="{{route('koperasi.tagihan-kredit.index')}}">Tagihan Penjualan Kredit</a>
                </li>
                @elseif ($item == 'Koperasi - Laporan - Simpan Pinjam')
                <li><a class="nav-link" href="{{route('koperasi.simpan-pinjam.index')}}">Simpan Pinjam</a></li>
                @endif
                @empty
                @endforelse
            </ul>
        </li>



        @if (customSearch("POS",$datarole))
        <li class="menu-header">POS | Point Of Sale</li>
        @endif


        <li class="nav-item dropdown  @yield('posmaster')">
            @if (customSearch("POS - Master",$datarole))
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                class="fas fa-columns"></i><span>Master</span></a>
            @endif
            <ul class="dropdown-menu">
                @forelse ($datarole as $item)
                @if ($item == 'POS - Master - Lokasi')
                <li><a class="nav-link" href="{{route('pos.master.lokasi.index')}}">Lokasi</a></li>
                @elseif ($item == 'POS - Master - Barang-Kategori')
                <li><a class="nav-link" href="{{route('pos.master.kategori.index')}}">Barang - Kategori</a></li>
                @elseif ($item == 'POS - Master - Barang')
                <li><a class="nav-link" href="{{route('master.barang.index')}}">Barang</a></li>
                @elseif ($item == 'POS - Master - Supplier')
                <li><a class="nav-link" href="{{route('pos.master.supplier.index')}}">Supplier</a></li>
                @endif
                @empty
                @endforelse
            </ul>
        </li>
        <li class="nav-item dropdown @yield('pos')">
            @if (customSearch("POS - Transaksi",$datarole))
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                class="fas fa-columns"></i><span>Transaksi</span></a>
            @endif
            <ul class="dropdown-menu">
                @forelse ($datarole as $item)
                @if ($item == 'POS - Transaksi - Pembelian')
                <li><a class="nav-link" href="{{route('pos.pembelianbaru.index')}}">Pembelian</a></li>
                @elseif ($item == 'POS - Transaksi - Penjualan')
                <li><a class="nav-link" href="{{route('pos.penjualan.index')}}">Penjualan</a></li>
                @elseif ($item == 'POS - Transaksi - Transfer Antar Toko')
                <li><a class="nav-link" href="{{route('pos.tfantartoko.index')}}">Transfer Antar Toko</a></li>
                @elseif ($item == 'POS - Transaksi - Stock Opname')
                <li><a class="nav-link" href="{{route('pos.stockopname.index')}}">Stock Opname</a></li>
                @elseif ($item == 'POS - Transaksi - Stock Hilang/Rusak')
                <li><a class="nav-link" href="{{route('pos.stockhilang.index')}}">Stock Hilang / Rusak</a></li>
                @elseif ($item == 'POS - Transaksi - Retur Pembelian')
                <li><a class="nav-link" href="{{route('pos.returpembelian.index')}}">Retur Pembelian</a></li>
                @elseif ($item == 'POS - Transaksi - List Promo')
                <li><a class="nav-link" href="{{route('pos.listpromo.index')}}">List Promo</a></li>
                @elseif ($item == 'POS - Transaksi - Kasir')
                <li><a class="nav-link" href="{{route('pos.kasir.index')}}">Kasir</a></li>
                @elseif ($item == 'POS - Transaksi - Cek Harga')
                <li><a class="nav-link" href="{{route('pos.harga.index')}}">Cek Harga</a></li>
                @endif
                @empty
                @endforelse
            </ul>
        </li>
        <li class="nav-item dropdown @yield('poslaporan')">
            @if (customSearch("POS - Laporan",$datarole))
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                class="fas fa-columns"></i><span>Laporan</span></a>
            @endif
            <ul class="dropdown-menu">
                @forelse ($datarole as $item)
                @if ($item == 'POS - Laporan - Realtime Stok')
                <li><a class="nav-link" href="{{route('poslaporan.realtimestok.index')}}">Realtime Stok</a></li>
                @elseif ($item == 'POS - Laporan - Penjualan')
                <li><a class="nav-link" href="{{route('poslaporan.penjualan.index')}}">Penjualan</a></li>
                @elseif ($item == 'POS - Laporan - Pembelian')
                <li><a class="nav-link" href="{{route('poslaporan.pembelian.index')}}">Pembelian</a></li>
                @elseif ($item == 'POS - Laporan - Minimum Stok')
                <li><a class="nav-link" href="{{route('poslaporan.minimumstok.index')}}">Minimum Stok</a></li>
                @elseif ($item == 'POS - Laporan - Opname | Hilang/Rusak')
                <li><a class="nav-link" href="{{route('poslaporan.opnamehilang.index')}}">Opname | Hilang/Rusak</a></li>
                @elseif ($item == 'POS - Laporan - Pareto Penjualan')
                <li><a class="nav-link" href="{{route('poslaporan.paretopenjualan.index')}}">Pareto Penjualan</a></li>
                @elseif ($item == 'POS - Laporan - Trace Stok')
                <li><a class="nav-link" href="{{route('poslaporan.tracestok.index')}}">Trace Stok</a></li>
                @elseif ($item == 'POS - Laporan - Cetak Label Harga')
                <li><a class="nav-link" href="{{route('poslaporan.trcetak.index')}}">Cetak Label Harga</a></li>
                @elseif ($item == 'POS - Laporan - Mutasi Bulanan')
                <li><a class="nav-link" href="{{route('poslaporan.mutasibulanan.index')}}">Mutasi Bulanan</a></li>
                @endif
                @empty
                @endforelse
            </ul>
        </li>
    </ul>

    <div class="mt-4 mb-4 p-3 hide-sidebar-mini">

    </div>
</aside>

@php
$datarole = session('data_role');
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
        @if ($item == 'Settings')
        <li class="menu-header">SETTINGS</li>

        <li class="nav-item @yield('setting') ">
            <a class="nav-link" href="{{route('settings.mssetting.index')}}">
                <i class="fas fa-file-alt"></i> <span>Setting</span>
            </a>
        </li>
        @endif
        @empty

        @endforelse

        <li class="menu-header">MASTER</li>
        <li class="nav-item @yield('user') ">
            <a class="nav-link" href="{{route('master.user.index')}}">
                <i class="fas fa-file-alt"></i> <span>User</span>
            </a>
        </li>
        <li class="menu-header">Saldo</li>
        <li class="nav-item @yield('saldo-awal') ">
            <a class="nav-link" href="{{route('saldo.saldo_awal.index')}}">
                <i class="fas fa-file-alt"></i> <span>Saldo awal hutang dan simpanan</span>
            </a>
        </li>


        @forelse ($datarole as $item)
        @if ($item == 'Koperasi')
        <li class="menu-header">KOPERASI</li>
        <li class="nav-item dropdown @yield('koperasi')">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                    class="fas fa-columns"></i><span>Master</span></a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{route('koperasi.anggota.index')}}">Anggota</a></li>
                <li><a class="nav-link" href="{{route('koperasi.transaksi.index')}}">Transaksi</a></li>
                {{-- <li><a class="nav-link" href="#">Account Lain-lain</a></li> --}}
                <li><a class="nav-link" href="{{route('koperasi.cicilan.index')}}">Cicilan</a></li>
            </ul>
        </li>

        <li class="nav-item dropdown @yield('transaksi')">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                    class="fas fa-columns"></i><span>Transaksi</span></a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{route('koperasi.saldo.index')}}">Cek Saldo</a></li>
                <li><a class="nav-link" href="#">Approval Pinjaman</a></li>
                <li><a class="nav-link" href="{{route('koperasi.topup.index')}}">TopUp e-kop</a></li>
                <li><a class="nav-link" href="#">Pembayaran</a></li>
                <li><a class="nav-link" href="{{route('koperasi.aktivasi.index')}}">Aktivasi e-kop</a></li>
                <li><a class="nav-link" href="{{route('koperasi.proses-bulanan.index')}}">Proses Bulanan</a></li>
            </ul>
        </li>
        <li class="nav-item dropdown @yield('laporan')">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                    class="fas fa-columns"></i><span>Laporan</span></a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{route('koperasi.tagihan-kredit.index')}}">Tagihan Penjualan Kredit</a>
                </li>
                <li><a class="nav-link" href="{{route('koperasi.simpan-pinjam.index')}}">Simpan Pinjam</a></li>
            </ul>
        </li>
        @endif
        @empty

        @endforelse

        <li class="menu-header">POS | Point Of Sale</li>
        <li class="nav-item dropdown  @yield('posmaster')">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                    class="fas fa-columns"></i><span>Master</span></a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="#">Lokasi</a></li>
                <li><a class="nav-link" href="#">Barang - Kategori</a></li>
                <li><a class="nav-link" href="{{route('master.barang.index')}}">Barang</a></li>
                <li><a class="nav-link" href="{{route('pos.master.supplier.index')}}">Supplier</a></li>
            </ul>
        </li>
        <li class="nav-item dropdown @yield('pos')">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                    class="fas fa-columns"></i><span>Transaksi</span></a>
            <ul class="dropdown-menu">

                <li><a class="nav-link" href="{{route('pos.pembelianbaru.index')}}">Pembelian</a></li>
                <li><a class="nav-link" href="{{route('pos.penjualan.index')}}">Penjualan</a></li>
                <li><a class="nav-link" href="{{route('pos.tfantartoko.index')}}">Transfer Antar Toko</a></li>
                <li><a class="nav-link" href="{{route('pos.stockopname.index')}}">Stock Opname</a></li>
                <li><a class="nav-link" href="{{route('pos.stockhilang.index')}}">Stock Hilang / Rusak</a></li>
                <li><a class="nav-link" href="{{route('pos.kasir.index')}}">Kasir</a></li>
                <li><a class="nav-link" href="{{route('pos.harga.index')}}">Cek Harga</a></li>
            </ul>
        </li>
        <li class="nav-item dropdown @yield('poslaporan')">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                    class="fas fa-columns"></i><span>Laporan</span></a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{route('poslaporan.realtimestok.index')}}">Realtime Stok</a></li>
                <li><a class="nav-link" href="{{route('poslaporan.penjualan.index')}}">Penjualan</a></li>
                <li><a class="nav-link" href="{{route('poslaporan.pembelian.index')}}">Pembelian</a></li>
                <li><a class="nav-link" href="{{route('poslaporan.minimumstok.index')}}">Minimum Stok</a></li>
                <li><a class="nav-link" href="{{route('poslaporan.opnamehilang.index')}}">Opname | Hilang/Rusak</a></li>
                <li><a class="nav-link" href="{{route('poslaporan.paretopenjualan.index')}}">Pareto Penjualan</a></li>
                <li><a class="nav-link" href="{{route('poslaporan.tracestok.index')}}">Trace Stok</a></li>
                <li><a class="nav-link" href="{{route('poslaporan.trcetak.index')}}">Cetak Label Harga</a></li>
            </ul>
        </li>
    </ul>

    <div class="mt-4 mb-4 p-3 hide-sidebar-mini">

    </div>
</aside>

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
        <li class="menu-header">SETTINGS</li>
        <li @yield('server cloud')>
            <a class="nav-link" href="#">
                <i class="fas fa-file-alt"></i> <span>Server Cloud</span>
            </a>
        </li>
        <li class="nav-item @yield('setting') ">
            <a class="nav-link" href="{{route('settings.mssetting.index')}}">
                <i class="fas fa-file-alt"></i> <span>Setting</span>
            </a>
        </li>
        <li class="menu-header">MASTER</li>
        <li @yield('menu')>
            <a class="nav-link" href="#">
                <i class="fas fa-file-alt"></i> <span>Menu</span>
            </a>
        </li>
        <li @yield('user')>
            <a class="nav-link" href="#">
                <i class="fas fa-file-alt"></i> <span>User</span>
            </a>
        </li>
        <li @yield('barang')>
            <a class="nav-link" href="#">
                <i class="fas fa-file-alt"></i> <span>Barang</span>
            </a>
        </li>
        <li class="menu-header">Saldo</li>
        <li class="nav-item @yield('saldo-awal') ">
            <a class="nav-link" href="{{route('saldo.saldo_awal.index')}}">
                <i class="fas fa-file-alt"></i> <span>Saldo awal hutang dan simpanan</span>
            </a>
        </li>

        <li class="menu-header">KOPERASI</li>
        <li class="nav-item dropdown @yield('koperasi')">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                    class="fas fa-columns"></i><span>Master</span></a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{route('koperasi.anggota.index')}}">List Anggota</a></li>
                <li><a class="nav-link" href="#">Transaksi</a></li>
                <li><a class="nav-link" href="#">Account Lain-lain</a></li>
                <li><a class="nav-link" href="#">Cicilan</a></li>
            </ul>
        </li>
        <li class="nav-item dropdown">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                    class="fas fa-columns"></i><span>Transaksi</span></a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="#">Cek Saldo</a></li>
                <li><a class="nav-link" href="#">Approval Pinjaman</a></li>
                <li><a class="nav-link" href="#">TopUp e-kop</a></li>
                <li><a class="nav-link" href="#">Pembayaran</a></li>
                <li><a class="nav-link" href="#">Aktivasi e-kop</a></li>
                <li><a class="nav-link" href="#">Proses Bulanan</a></li>
                <li><a class="nav-link" href="#">Web</a></li>
            </ul>
        </li>
        <li class="menu-header">POS | Point Of Sale</li>
        <li class="nav-item dropdown">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                    class="fas fa-columns"></i><span>Master</span></a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="#">Lokasi</a></li>
                <li><a class="nav-link" href="#">Barang - Kategori</a></li>
                <li><a class="nav-link" href="#">Barang</a></li>
                <li><a class="nav-link" href="#">Supplier</a></li>
            </ul>
        </li>
        <li class="nav-item dropdown @yield('pos')">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                    class="fas fa-columns"></i><span>Transaksi</span></a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{route('pos.pembelian.index')}}">Pembelian</a></li>
                <li><a class="nav-link" href="{{route('pos.penjualan.index')}}">Penjualan</a></li>
                <li><a class="nav-link" href="{{route('pos.tfantartoko.index')}}">Transfer Antar Toko</a></li>
                <li><a class="nav-link" href="{{route('pos.stockopname.index')}}">Stock Opname</a></li>
                <li><a class="nav-link" href="{{route('pos.stockhilang.index')}}">Stock Hilang / Rusak</a></li>
            </ul>
        </li>
        <li class="nav-item dropdown @yield('poslaporan')">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                    class="fas fa-columns"></i><span>Laporan</span></a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{route('poslaporan.realtimestok.index')}}">Realtime Stok</a></li>
                <li><a class="nav-link" href="{{route('poslaporan.penjualan.index')}}">Penjualan</a></li>
                <li><a class="nav-link" href="{{route('poslaporan.minimumstok.index')}}">Minimum Stok</a></li>
                <li><a class="nav-link" href="{{route('poslaporan.opnamehilang.index')}}">Opname | Hilang/Rusak</a></li>
            </ul>
        </li>
    </ul>

    <div class="mt-4 mb-4 p-3 hide-sidebar-mini">

    </div>
</aside>

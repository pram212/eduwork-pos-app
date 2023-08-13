<aside class="main-sidebar sidebar-dark-primary">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="{{asset('img/prams_brand.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{config('app.name')}}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="{{ route( 'users.edit', ['user' => Auth::user()->id ] ) }}" class="d-block">Login as {{ ucwords(Auth::user()->name) }}</a>
        </div>
      </div>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-header">Home</li>
            <li class="nav-item">
                <a href="{{ route('home') }}" class="nav-link @if( request()->is('home') ) active @endif">
                    <i class="nav-icon fas fa-home"></i>
                    <p>Beranda</p>
                </a>
            </li>
            @can('lihat dashboard')
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link @if( request()->is('dashboard') ) active @endif">
                    <i class="nav-icon fas fa-chart-pie"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            @endcan
            <li class="nav-header">Manajemen Transaksi</li>
            @can('lihat penjualan')
            <li class="nav-item">
                <a href="{{ route('sales.index') }}" class="nav-link @if(request()->is('sales')) active @endif">
                    <i class="nav-icon fas fa-cash-register"></i>
                    <p>Penjualan</p>
                </a>
            </li>
            @endcan
            @can('lihat pembelian')
            <li class="nav-item @if(request()->is('purchases') || request()->is('payments')) menu-open  @endif">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-shopping-cart"></i>
                    <p>
                        Pembelian<i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                    <a href="{{ route('purchases.index') }}" class="nav-link @if(request()->is('purchases')) active @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Data Pembelian</p>
                    </a>
                    </li>
                    <li class="nav-item">
                    <a href="{{ route('payments.index') }}" class="nav-link @if(request()->is('payments')) active @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Pembayaran</p>
                    </a>
                    </li>
                </ul>
            </li>
            @endcan
            <li class="nav-header">Manajemen Produk</li>
            @can('lihat produk')
            <li class="nav-item">
                <a href="{{ route('products.index') }}" class="nav-link @if(request()->is('products')) active @endif">
                    <i class="nav-icon fas fa-th-large"></i>
                    <p>Produk</p>
                </a>
            </li>
            @endcan
            @can('lihat kategori')
            <li class="nav-item">
                <a href="{{ route('categories.index') }}" class="nav-link @if(request()->is('categories')) active @endif">
                    <i class="nav-icon fas fa-layer-group"></i>
                    <p>Kategori</p>
                </a>
            </li>
            @endcan
            @can('lihat gudang')
            <li class="nav-item">
                <a href="{{ route('warehouses.index') }}" class="nav-link @if(request()->is('warehouses')) active @endif">
                    <i class="nav-icon fas fa-warehouse"></i>
                    <p>Gudang</p>
                </a>
            </li>
            @endcan
            @can('lihat supplier')
            <li class="nav-item">
                <a href="{{ route('suppliers.index') }}" class="nav-link @if(request()->is('suppliers')) active @endif">
                    <i class="nav-icon fas fa-store"></i>
                    <p>Supplier</p>
                </a>
            </li>
            @endcan
            <li class="nav-header">Manajemen User</li>
            @can('lihat user')
            <li class="nav-item">
                <a href="{{ route('users.index') }}" class="nav-link @if(request()->is('users')) active @endif">
                  <i class="nav-icon fas fa-id-card-alt"></i>
                  <p>
                    Data User
                  </p>
                </a>
            </li>
            @endcan
            @can('lihat aktifitas user')
            <li class="nav-item"> 
                <a href="{{ route('activities.index') }}" class="nav-link @if(request()->is('activities')) active @endif">
                  <i class="nav-icon fas fa-eye"></i>
                  <p>
                    Aktifitas User
                  </p>
                </a>
            </li>
            @endcan
            @can('lihat role')
            <li class="nav-item">
                <a href="{{ route('roles-permissions.index') }}" class="nav-link @if(request()->is('roles-permissions')) active @endif">
                  <i class="nav-icon fas fa-key"></i>
                  <p>Hak Akses</p>
                </a>
            </li>
            @endcan
            <li class="nav-header">Laporan</li>
            @can('lihat laporan penjualan')
            <li class="nav-item">
                <a href="{{ route('reports.sales.index') }}" class="nav-link @if(request()->is('report/sales')) active @endif">
                    <i class="nav-icon fas fa-file-alt"></i>
                    <p>
                        Penjualan
                    </p>
                </a>
            </li>
            @endcan
            @can('lihat laporan pembelian')
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-file-alt"></i>
                  <p>
                    Pembelian
                  </p>
                </a>
            </li>
            @endcan
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

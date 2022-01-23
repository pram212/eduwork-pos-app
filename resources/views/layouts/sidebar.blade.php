<aside class="main-sidebar sidebar-dark-primary">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="{{asset('adminLTE/dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">{{config('app.name')}}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{asset('adminLTE/dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="{{ route( 'users.edit', ['user' => Auth::user()->id ] ) }}" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <li class="nav-header">Home</li>
            <li class="nav-item">
                <a href="{{ route('home') }}" class="nav-link @if( request()->is('home') ) active @endif">
                    <i class="nav-icon fas fa-chart-pie"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-header">Manajemen Transaksi</li>
            <li class="nav-item">
                <a href="{{ route('sales.index') }}" class="nav-link @if(request()->is('sales')) active @endif">
                    <i class="nav-icon fas fa-cash-register"></i>
                    <p>Penjualan</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('purchases.index') }}" class="nav-link @if(request()->is('purchases')) active @endif">
                    <i class="fas fa-shopping-cart nav-icon"></i> <p>Pembelian (PO)</p>
                </a>
            </li>
            <li class="nav-header">Manajemen Produk</li>
            <li class="nav-item">
                <a href="{{ route('products.index') }}" class="nav-link @if(request()->is('products')) active @endif">
                    <i class="nav-icon fas fa-th-large"></i>
                    <p>Produk</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('categories.index') }}" class="nav-link @if(request()->is('categories')) active @endif">
                    <i class="nav-icon fas fa-layer-group"></i>
                    <p>Kategori</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('warehouses.index') }}" class="nav-link @if(request()->is('warehouses')) active @endif">
                    <i class="nav-icon fas fa-warehouse"></i>
                    <p>Gudang</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('suppliers.index') }}" class="nav-link @if(request()->is('suppliers')) active @endif">
                    <i class="nav-icon fas fa-store"></i>
                    <p>Supplier</p>
                </a>
            </li>
            <li class="nav-header">Manajemen User</li>
            <li class="nav-item">
                <a href="{{ route('users.index') }}" class="nav-link @if(request()->is('users')) active @endif">
                  <i class="nav-icon fas fa-id-card-alt"></i>
                  <p>
                    Data User
                  </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('activities.index') }}" class="nav-link @if(request()->is('activities')) active @endif">
                  <i class="nav-icon fas fa-eye"></i>
                  <p>
                    Log Aktivity
                  </p>
                </a>
            </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

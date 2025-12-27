<nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0">
    <div class="container-fluid d-flex flex-column p-0">

        <a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#">
            <div class="sidebar-brand-icon"><i class="fas fa-box"></i></div>
            <div class="text-start sidebar-brand-text mx-3 justify-content-center align-items-center">
                <span>PMS<br></span>
            </div>
        </a>

        <hr class="sidebar-divider my-0">

        <ul class="navbar-nav text-light" id="accordionSidebar">
            <li class="nav-item"><a class="nav-link" href="{{ route('index') }}">
                <i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>
            </li>
            @can('viewAny', App\Models\Category::class)
                <li class="nav-item"><a class="nav-link" href="{{ route('categories.index') }}">
                    <i class="fas fa-tags"></i><span>Categories</span></a>
                </li>
            @endcan
            @can('viewAny', App\Models\Product::class)
                <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">
                    <i class="fas fa-box"></i><span>Products</span></a>
                </li>
            @endcan
            @can('viewAny', App\Models\User::class)
                <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">
                    <i class="fas fa-users"></i><span>Users</span></a>
                </li>
            @endcan
        </ul>

        <div class="text-center d-none d-md-inline">
            <button class="btn rounded-circle border-0 text-white" id="sidebarToggle" type="button"></button>
        </div>
    </div>
</nav>

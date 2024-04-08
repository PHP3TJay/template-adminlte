<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/dashboard" class="brand-link text-center">
        <span class="brand-text font-weight-medium ">MySteps </span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="/dashboard" class="nav-link @if(Route::is('dashboard')) active @endif">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
            @if(in_array($currentRoleUser->role_id, [1, 2]))
                <li class="nav-item">
                    <a href="/user" class="nav-link @if(Route::is('user')) active @endif">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Users
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/team" class="nav-link @if(Route::is('team')) active @endif">
                        <i class="nav-icon fas fa-address-book"></i>
                        <p>
                            Teams
                        </p>
                    </a>
                </li>
            @endif
            </ul>
        </nav>
    </div>
</aside>
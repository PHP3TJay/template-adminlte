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
            @if(!$lowest)
                <li class="nav-item">
                    <a href="/coaching-log" class="nav-link @if(Route::is('coaching-log')) active @endif">
                        <i class="nav-icon fas fa-handshake"></i>
                        <p>
                            Coaching Creation
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/coaching-accepted" class="nav-link @if(Route::is('coaching-accepted')) active @endif">
                        <i class="nav-icon fas fa-handshake"></i>
                        <p>
                            Accepted Coaching
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/coaching-canceled" class="nav-link @if(Route::is('coaching-canceled')) active @endif">
                        <i class="nav-icon fas fa-handshake"></i>
                        <p>
                            Canceled Coaching
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/coaching-declined" class="nav-link @if(Route::is('coaching-declined')) active @endif">
                        <i class="nav-icon fas fa-handshake"></i>
                        <p>
                            Declined Coaching
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/coaching-completed" class="nav-link @if(Route::is('coaching-completed')) active @endif">
                        <i class="nav-icon fas fa-handshake"></i>
                        <p>
                            (Completed) Creation
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/coaching-follow-through" class="nav-link @if(Route::is('coaching-follow-through')) active @endif">
                        <i class="nav-icon fas fa-handshake"></i>
                        <p>
                            Follow Through 
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/coaching-due" class="nav-link @if(Route::is('coaching-due')) active @endif">
                        <i class="nav-icon fas fa-handshake"></i>
                        <p>
                            (Due) Accepted
                        </p>
                    </a>
                </li>
            @endif
            @if(!in_array($currentRoleUser->role_id, [1, 2]))
                <li class="nav-item">
                    <a href="/my-coaching" class="nav-link @if(Route::is('my-coaching')) active @endif">
                        <i class="nav-icon fas fa-handshake"></i>
                        <p>
                            My Coaching
                        </p>
                    </a>
                </li>
            @endif
            </ul>
        </nav>
    </div>
</aside>
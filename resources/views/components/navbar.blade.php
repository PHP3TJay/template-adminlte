<nav class="main-header navbar navbar-expand navbar-dark navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="dropdown user user-menu">
          <div class="dropdown">
            <button class="btn  dropdown-toggle text-light" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              {{auth()->user()->firstname . " " . auth()->user()->lastname}}
            </button>
            <div class="dropdown-menu text-center" aria-labelledby="dropdownMenuButton">
              {{-- <a class="dropdown-item" href="#">Profile</a> --}}
              <a class="dropdown-item" href="/logout">Logout</a>
            </div>
          </div>
        </li>
    </ul>
</nav>
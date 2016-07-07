<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="{{ action('HomeController@index') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>G</b>MT</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>GMT</b> Timesheet</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                @if (Auth::guest())
                  <li><a href="{{ action('Auth\AuthController@showLoginForm') }}">Login</a></li>
                @else
                  <!-- User Account Menu -->
                  <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                      <span>{{ Auth::user()->firstname . ' ' . Auth::user()->lastname}}</span>
                      {{--<span class="hidden-xs">{{ Auth::user()->firstname . ' ' . Auth::user()->lastname}}</span>--}}
                    </a>
                    <ul class="dropdown-menu">
                      <li class="user-header" style="height: auto;">
                        <p>
                          {{ Auth::user()->firstname . ' ' . Auth::user()->lastname}}<br>
                          {{ Auth::user()->code }}
                          <small><strong>{{ Auth::user()->department->name }}</strong></small>
                          <small>Username : {{ Auth::user()->username }}</small>
                        </p>
                      </li>
                      <li class="user-footer">
                        <div class="pull-left">
                          <a href="{{ action('ProfileController@index') }}" class="btn btn-default btn-flat">Profile</a>
                        </div>
                        <div class="pull-right">
                          <a href="{{ action('Auth\AuthController@logout') }}" class="btn btn-default btn-flat">Sign out</a>
                        </div>
                      </li>
                    </ul>
                  </li>
                @endif
            </ul>
        </div>
    </nav>
</header>

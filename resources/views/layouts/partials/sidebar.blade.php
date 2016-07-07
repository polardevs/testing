<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- search form (Optional) -->
        <!-- <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
        </form> -->
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
          <li class="header">GMT Timesheet Management</li>
          @if(Auth::user() && Auth::user()->permission)
            @if(Auth::user()->permission->role->isGeneral())
              <li @if(Request::segment(1) == '') class="active" @endif >
                <a href="{{ action('HomeController@index') }}"><i class='fa fa-circle-o-notch'></i> <span>Home</span></a>
              </li>
              <li @if(Request::segment(1) == 'listTimesheet') class="active" @endif >
                <a href="{{ action('HomeController@listTimesheet') }}"><i class='fa fa-th-list'></i> <span>Timesheets</span></a>
              </li>
            @endif

            @if(Auth::user()->permission->role->isProjectManager())
              <li @if(Request::segment(1) == 'project') class="active" @endif >
                <a href="{{ action('ProjectController@index') }}"><i class='fa fa-tasks'></i> <span>Project</span></a>
              </li>
              <li @if(Request::segment(1) == 'employee') class="active" @endif >
                <a href="{{ action('EmployeeController@index') }}"><i class='fa fa-users'></i> <span>Employee</span></a>
              </li>
              <li @if(Request::segment(1) == 'report') class="active" @endif >
                <a href="{{ action('ReportController@index') }}"><i class='fa fa-list-alt'></i> <span>Report</span></a>
              </li>
            @endif

            @if(Auth::user()->permission->role->isAdmin())
              <li @if(Request::segment(1) == 'department') class="active" @endif >
                <a href="{{ action('DepartmentController@index') }}"><i class='fa fa-sitemap'></i> <span>Department</span></a>
              </li>
              <li @if(Request::segment(1) == 'position') class="active" @endif >
                <a href="{{ action('PositionController@index') }}"><i class='fa fa-user'></i> <span>Position</span></a>
              </li>
              <li>
                <a href="{{ action('Auth\PasswordController@showResetForm') }}"><i class='fa fa-undo'></i> <span>Reset Password</span></a>
              </li>
              <li>
                <a href="{{ action('HomeController@backupToCSV') }}"><i class="fa fa-save"></i> <span>backup</span></a>
              </li>
            @endif

            @if(Auth::user()->permission->role->isDeveloper())
              <li @if(Request::segment(1) == 'role') class="active" @endif >
                <a href="{{ action('RoleController@index') }}"><i class='fa fa-legal'></i> <span>Role</span></a>
              </li>
              <li @if(Request::segment(1) == 'permission') class="active" @endif >
                <a href="{{ action('PermissionController@index') }}"><i class='fa fa-check-square-o'></i> <span>Permission</span></a>
              </li>
            @endif
          @endif

            <!-- <li class="treeview">
                <a href="#"><i class='fa fa-bar-chart'></i> <span>Report</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li>
                        <a href="#"><i class='fa fa-ellipsis-h'></i> <span>report A</span></a>
                    </li>
                    <li class="treeview">
                        <a href="#"><i class='fa fa-bar-chart'></i> <span>Report</span> <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li>
                                <a href="#"><i class='fa fa-ellipsis-h'></i> <span>report A</span></a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li> -->


            {{-- <li><a href="{{ url('/lockscreen') }}"><i class="fa fa-circle-o"></i> Lockscreen</a></li> --}}
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>

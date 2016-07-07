@extends('layouts.auth')

@section('htmlheader_title')
    Lockscreen
@endsection

@section('content')
<body class="hold-transition lockscreen">
  <div class="lockscreen-wrapper">
    <div class="lockscreen-logo">
      <b>Admin</b>LTE
    </div>
    <!-- User name -->
    <div class="lockscreen-name">John Doe</div>

    <!-- START LOCK SCREEN ITEM -->
    <div class="lockscreen-item">
      <!-- lockscreen credentials (contains the form) -->
      <form class="lockscreen-credentials">
        <div class="input-group">
          <input type="password" class="form-control" placeholder="Enter your password">

          <div class="input-group-btn">
            <button type="button" class="btn"><i class="fa fa-arrow-right text-muted"></i></button>
          </div>
        </div>
      </form>
      <!-- /.lockscreen credentials -->

    </div>
    <!-- /.lockscreen-item -->
    <div class="help-block text-center">
      Enter your password to retrieve your session
    </div>
    <div class="text-center">
      <a href="login.html">Or sign in as a different user</a>
    </div>
    <div class="lockscreen-footer text-center">
      Copyright &copy; 2014-2015 <b><a href="http://almsaeedstudio.com" class="text-black">Almsaeed Studio</a></b><br>
      All rights reserved
    </div>
  </div>

  @include('layouts.partials.scripts_auth')

  <script>
    // $(function () {
    //   $('input').iCheck({
    //     checkboxClass: 'icheckbox_square-blue',
    //     radioClass: 'iradio_square-blue',
    //     increaseArea: '20%' // optional
    //   });
    // });
  </script>
</body>

@endsection

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>SB Admin - Start Bootstrap Template</title>
  <link href="{{ asset('admin/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('admin/vendor/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet">
  <link href="{{ asset('admin/vendor/datatables/buttons.dataTables.min.css') }}" rel="stylesheet">
  <link href="{{ asset('admin/css/sb-admin.css') }}" rel="stylesheet">
  <link href="{{ asset('admin/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
  @stack('style')
</head>

<body class="fixed-nav sticky-footer bg-dark sidenav-toggled" id="page-top">
  <!-- Navigation-->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand" href="{{ route('/') }}">CMS ITCM</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">

      <!-- MENU -->
      <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Blog">
          <a class="nav-link" href="{{ url('posts') }}">
            <i class="fa fa-fw fa-dashboard"></i>
            <span class="nav-link-text">Create Blog</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="FAQ">
          <a class="nav-link" href="{{ route('faq') }}">
            <i class="fa fa-fw fa-area-chart"></i>
            <span class="nav-link-text">FAQ Create</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Kategori">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseMulti" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-sitemap"></i>
            <span class="nav-link-text">Master</span>
          </a>
          <ul class="sidenav-second-level collapse" id="collapseMulti">
            <li>
              <!-- <a href="{{ route('faq') }}">Kategori Blog</a> -->
            </li>
            <li>
              <a href="{{ route('category-faqs') }}">Kategori FAQ</a>
            </li>
            <li>
              <a href="{{ url('categories') }}">Kategori Blogs</a>
            </li>
            <!-- <li>
              <a href="{{ url('tags') }}">Tags</a>
            </li> -->
          </ul>
        </li>
      </ul>
      <ul class="navbar-nav sidenav-toggler">
        <li class="nav-item">
          <a class="nav-link text-center" id="sidenavToggler">
            <i class="fa fa-fw fa-angle-left"></i>
          </a>
        </li>
      </ul>
      <!-- ENDMENU -->

      <!-- HEAdER -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fa fa-fw fa-sign-out"></i>Logout</a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
          </form>
        </li>
      </ul>
       <!-- END HEADER -->
    </div>
  </nav>
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Icon Cards-->
      @yield('content')
    </div>
  </div>
   
    
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
    <footer class="sticky-footer">
      <div class="container">
        <div class="text-center">
          <small>Made with ♥ in fatmawati 2018</small>
        </div>
      </div>
    </footer>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="login.html">Logout</a>
          </div>
        </div>
      </div>
    </div>

    <script src="{{ asset('admin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Core plugin JavaScript-->
    <script src="{{ asset('admin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <!-- Page level plugin JavaScript-->
    <!-- <script src="admin/vendor/chart.js/Chart.min.js"></script> -->
    <script src="{{ asset('admin/vendor/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.js') }}"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{ asset('admin/js/sb-admin.min.js') }}"></script>
    <!-- Custom scripts for this page-->
    <script src="{{ asset('admin/js/sb-admin-datatables.min.js') }}"></script>
    <script src="{{ asset('admin/js/sb-admin-datatables.buttons.min.js') }}"></script>
    <!-- <script src="admin/js/sb-admin-charts.min.js"></script> -->
    <script src="{{ asset('admin/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('admin/vendor/bootstrap-select/dist/js/i18n/defaults-en_US.min.js') }}"></script>
    @stack('scripts')
  </div>
</body>

</html>

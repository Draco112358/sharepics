<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Pannello Amministrazione</title>
    <!-- Bootstrap core CSS-->
    <link href="{{url('/')}}/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom fonts for this template-->
    <link href="{{url('/')}}/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- Custom styles for this template-->
    <link href="{{url('/')}}/css/sb-admin.css" rel="stylesheet">
    <link href="{{url('/')}}/datatables/dataTables.bootstrap4.css" rel="stylesheet">
</head>

<body class="fixed-nav sticky-footer bg-secondary" id="page-top">
<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <div class="navbar-brand text-warning">SharePics</div>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
                <a class="nav-link" href="{{route('adminpanel')}}">
                    <i class="fa fa-fw fa-dashboard"></i>
                    <span class="nav-link-text">Dashboard</span>
                </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Utenti">
                <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseUsers" >
                    <i class="fa fa-fw fa-wrench"></i>
                    <span class="nav-link-text">Utenti</span>
                </a>
                <ul class="sidenav-second-level collapse" id="collapseUsers">
                    <li>
                        <a href="{{route('users.index')}}">Lista utenti</a>
                    </li>
                    <li>
                        <a href="{{route('users.create')}}">Aggiungi utente</a>
                    </li>
                </ul>
            </li>

           
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Categorie">
                <a class="nav-link" href="{{route('categories.index')}}">
                    <i class="fa fa-fw fa-file-image-o"></i>
                    <span class="nav-link-text">Categorie album</span>
                </a>
            </li>
   
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Albums">
                <a class="nav-link" href="{{route('albums.list')}}">
                    <i class="fa fa-fw fa-book"></i>
                    <span class="nav-link-text">Albums</span>
                </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Foto">
                <a class="nav-link" href="{{route('photos.list')}}">
                    <i class="fa fa-fw fa-picture-o"></i>
                    <span class="nav-link-text">Foto</span>
                </a>
            </li><li class="nav-item" data-toggle="tooltip" data-placement="right" title="Commenti">
                <a class="nav-link" href="{{route('comments.list')}}">
                    <i class="fa fa-fw fa-comments-o"></i>
                    <span class="nav-link-text">Commenti</span>
                </a>
            </li>
        </ul>
        <ul class="navbar-nav sidenav-toggler">
            <li class="nav-item">
                <a class="nav-link text-center" id="sidenavToggler">
                    <i class="fa fa-fw fa-angle-left"></i>
                </a>
            </li>
        </ul>
        <ul class="navbar-nav navbar-left mr-auto">
            <li class="nav-item">
                <a href="{{route('gallery')}}" class="nav-link text-left">
                    <i class="fa fa-fw fa-home">HOME</i>
                </a>
            </li>
          
        </ul>
        <ul class="navbar-nav navbar-right ml-auto">
           
            <li class="nav-item">
                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a class="nav-link"  onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" href="{{route('logout')}}">
                    <i class="fa fa-fw fa-sign-out"></i>Logout</a>
            </li>
        </ul>
    </div>
</nav>
<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('adminpanel')}}">Admin Dashboard</a>
            </li>
            <li class="breadcrumb-item active">{{Route::currentRouteName()}}</li>
        </ol>
        <div class="row">
            <div class="col-12">
              @yield('content')
            </div>
        </div>
    </div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->

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
    @section('footer')
    <!-- Bootstrap core JavaScript-->
    <script src="{{url('/')}}/jquery/jquery.min.js"></script>
    <script src="{{url('/')}}/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="{{url('/')}}/jquery-easing/jquery.easing.min.js"></script>
        <script src="{{url('/')}}/datatables/jquery.dataTables.js"></script>
        <script src="{{url('/')}}/datatables/dataTables.bootstrap4.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{url('/')}}/js/sb-admin.min.js"></script>
        <script src="{{url('/')}}/js/sb-admin-datatables.min.js"></script>
        @show
</div>
</body>

</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ ucfirst( last(Request::segments()) ) }}</title>

    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('img/favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('img/favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('img/favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('img/favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('img/favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="/{{ asset('img/favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('img/favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('img/favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('img/favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('img/favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('img/favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    @section('css')
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
        <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('vendor/bootstrap/bootstrap-extend.min.css') }}" rel="stylesheet">
        <link href="{{ asset('vendor/bootstrap-table/bootstrap-table.min.css') }}" rel="stylesheet">
        <link href="{{ asset('vendor/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet">
        <link href="{{ asset('vendor/bootstrap-toggle/bootstrap-toggle.min.css') }}" rel="stylesheet">

        <link href="{{ asset('css/vendor.css') }}" rel="stylesheet">
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/theme.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/skins/_all-skins.css') }}" rel="stylesheet">
        <link href="{{ asset('css/jGrowl.css') }}" rel="stylesheet">
        <link href="{{ asset('vendor/soon/soon.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/sweetalert2.css') }}" rel="stylesheet">
        <link href="{{ asset('vendor/switchery/switchery.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/personal.css') }}" rel="stylesheet">
        <link href="{{ asset('css/animate.css') }}" rel="stylesheet">
    @show
    <style>
        .bootstrap-select > .btn {
            height: 22px;
            padding: 0 20px;
        }
        .soon-wrapper, .soon {
            font-size: 1rem !important;
        }

        .table-responsive {
            border: none !important;
        }
    </style>

    <script src="{{ asset('js/jquery/jquery.min.js') }}"></script>

</head>
<body class="sidebar-mini skin-green">
<div class="loading"></div>
<script>
    $('.loading').show();
</script>
<section class="wrapper">
    <header class="main-header">
        <!-- Logo -->
        <a href="{{ URL::to('dashboard') }}" class="logo">

            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini">
                <img src="{{ asset('img/logo.png') }}" class="img-circle" alt="Logo"/>
            </span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg">Champi<img src="{{ asset('img/logo.png') }}" class="img-circle" alt="Logo"/>ns</span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <!--
            <div class="label label-outline label-primary label-sm"
                 style="width: auto; display: inline-block; margin-top: 5px; margin-left: 1%">
                <span>Session expire</span>
                <div class="soon">
                </div>
            </div>
            -->
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{ asset('img/avatar/footballer_2.png') }}" class="user-image"
                                 alt="User Image"/>
                            <span class="hidden-xs">{{ $player->user->name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="{{ asset('img/avatar/footballer_2.png') }}" class="img-circle"
                                     alt="User Image"/>
                                <p>
                                    {{ $player->user->name }}
                                    <small>Username ({{ ucwords($player->user->username) }})</small>
                                    <small>Member
                                        since: {{ substr_replace($player->created_at, '', 4, strlen($player->created_at)) }}</small>
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="{{ URL::to('profile') }}" class="btn btn-default btn-flat">
                                        <i class="fa fa-cog" aria-hidden="true"></i>
                                        Update profile
                                    </a>
                                </div>
                                <div class="pull-right">
                                    <a href="{{ URL::to('logout') }}" class="btn btn-default btn-flat">
                                        <i class="fa fa-sign-out" aria-hidden="true"></i>
                                        Sign out
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">

                <div class="pull-left image">
                    <img src="{{ asset('img/avatar/footballer_2.png') }}" class="img-circle" alt="User Image"/>
                </div>
                <div class="pull-left info">
                    <p>{{ $player->user->username }}</p>

                    <a href="profile"><i class="fa fa-circle text-success"></i> online</a>
                </div>
            </div>
        {{--<!-- search form -->--}}
        {{--<form action="#" method="get" class="sidebar-form">--}}
        {{--<div class="input-group">--}}
        {{--<input type="text" name="q" class="form-control" placeholder="Search..."/>--}}
        {{--<span class="input-group-btn">--}}
        {{--<button type='submit' name='search' id='search-btn' class="btn btn-flat"><i--}}
        {{--class="fa fa-search"></i></button>--}}
        {{--</span>--}}
        {{--</div>--}}
        {{--</form>--}}
        {{--<!-- /.search form -->--}}

        <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li class="header">MAIN NAVIGATION</li>
                <li class="{{ (Request::is('dashboard')) ? 'active' : '' }}">
                    <a href="{{ URL::to('dashboard') }}">
                        <i class="fa fa-dashboard"></i>
                        <span class="{{ (Request::is('dashboard')) ? 'text-bold' : '' }}">Dashboard</span>
                    </a>
                </li>
                <li class="{{ (Request::is('games')) ? 'active' : '' }}">
                    <a href="{{ URL::to('games') }}">
                        <i class="fa fa-futbol-o" aria-hidden="true"></i>
                        <span class="{{ (Request::is('games')) ? 'text-bold' : '' }}">Games</span>
                    </a>
                </li>
                <li class="{{ (Request::is('ranking')) ? 'active' : '' }}">
                    <a href="{{ URL::to('ranking') }}">
                        <i class="fa fa-line-chart" aria-hidden="true"></i>
                        <span class="{{ (Request::is('ranking')) ? 'text-bold' : '' }}">Ranking</span>
                    </a>
                </li>
                <li class="hide">
                    <a href="{{ URL::to('app/cyptech.apk') }}">
                        <i class="fa fa-download" aria-hidden="true"></i>
                        <span>Android APP</span>
                    </a>
                </li>
                @if(Auth::user()->levelAccess() >= 2)
                    <li class="header">Admins</li>
                    <li class="treeview {{ (Request::is('admin/*')) ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-cogs"></i> <span>Settings</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>

                        <ul class="treeview-menu">
                            <li class="{{ (Request::is('admin/players')) ? 'active' : '' }}">
                                <a href="{{ URL::to('admin/players') }}">
                                    <i class="fa fa-users"></i>
                                    <span class="{{ (Request::is('admin/players')) ? 'text-bold' : '' }}">Players</span>
                                </a>
                            </li>
                            <li class="{{ (Request::is('admin/games')) ? 'active' : '' }}">
                                <a href="{{ URL::to('admin/games') }}">
                                    <i class="fa fa-trophy" aria-hidden="true"></i>
                                    <span class="{{ (Request::is('admin/games')) ? 'text-bold' : '' }}">Games</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if(Auth::user()->levelAccess() >= 3)
                    <li class="header">Masters</li>
                    <li class="{{ (Request::is('master/visitors')) ? 'active' : '' }}">
                        <a href="{{ URL::to('master/visitors') }}">
                            <i class="fa fa-users"></i>
                            <span class="{{ (Request::is('master/visitors')) ? 'text-bold' : '' }}">Visitors</span>
                        </a>
                    </li>
                    <li class="{{ (Request::is('master/logs')) ? 'active' : '' }}">
                        <a href="{{ URL::to('master/logs') }}">
                            <i class="fa fa-calendar"></i>
                            <span class="{{ (Request::is('master/logs')) ? 'text-bold' : '' }}">Logs</span>
                        </a>
                    </li>
                    <li class="{{ (Request::is('master/activity')) ? 'active' : '' }}">
                        <a href="{{ URL::to('master/activity') }}">
                            <i class="fa fa-calendar"></i>
                            <span class="{{ (Request::is('master/activity')) ? 'text-bold' : '' }}">Activity</span>
                        </a>
                    </li>
                @endif
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <section class="content-wrapper">

        <section class="content-header">
            <h1>
                <small><b>{{ucwords($player->user->role)}}</b>
                    Role (Last login: {{\App\Player::getLastAccess()}})
                </small>
            </h1>

            <ol class="breadcrumb" style="position: relative; float: none;">
                <li><a href="{{ URL::to('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">{{ ucfirst( last(Request::segments()) ) }}</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                @if (session()->has('flash_notification.message'))
                    <section class="content-header">
                        <div class="alert alert-{{ session('flash_notification.level') }}">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {!! session('flash_notification.message') !!}
                        </div>
                    </section>
                @endif

                @yield('content')

            </div>
        </section>
    </section>
    <footer class="main-footer">
        <div class="pull-right">
            v{{ app()::VERSION }}
        </div>
        <strong>Copyright © 2014-<?php echo date("Y");  ?> <a href='#' target='_blank'>Cyptech</a>.</strong> All rights reserved.
    </footer>
</section>

<!-- Bottom Scripts  -->
@section('bottom_scripts')
    <script src="{{ asset('assets/js/theme.min.js') }}"></script>
    <script src="{{ asset('js/vendor.js') }}"></script>

@show

@section('plugin')
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-table/bootstrap-table.min.js') }}"></script>
    <script src="{{ asset('js/jgrowl.min.js') }}"></script>
    <script src="{{ asset('vendor/soon/soon.min.js') }}"></script>
    <script src="{{ asset('vendor/switchery/switchery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-toggle/bootstrap-toggle.min.js') }}"></script>
    <script src="{{ asset('vendor/bootbox/bootbox.min.js') }}"></script>

    <!-- Toastr -->
    <script src="{{ asset('js/toastr.min.js') }}"></script>

    <script>
        let lifetime = parseInt("{{$lifetime}}");
        let session_start_at = parseInt("{{$session_start_at}}");
    </script>
    <script src="{{ asset('js/admin.js') }}"></script>
    <script src="{{ asset('js/site.js') }}"></script>
@show

@section('custom')
    <script src="{{ asset('js/dashboard.js') }}"></script>
@show

@yield('local-js')

<script>
    // create a more advanced counter
    $(".soon").soon().create({
        due: "in 10 minutes",
        format: "m,s",
        scaleMax: "xxs",
        paddingMinutes: "00",
        paddingSeconds: "00",
        separator: ":",
        layout: "label-hidden",
        face: "flip color-light corners-sharp shadow-soft",
        eventComplete: function () {
            window.location = "/logout";
        }
    });

    function ajaxLoad(filename, content) {
        content = typeof content !== 'undefined' ? content : 'content';
        $.ajax({
            type: "GET",
            url: filename,
            contentType: false,
            success: function (data, textStatus, jqXHR) {
                $("#" + content).html(data);
                $('.loading').hide();
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
            },
            statusCode: {
                404: function () {
                    alert("page not found");
                }
            }
        });
    }
    $('.loading').hide();
</script>
<script>
    $(document).ready(function(){
        $('[data-toggle="popover"]').popover();
    });
</script>
</body>
</html>

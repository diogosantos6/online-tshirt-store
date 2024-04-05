<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords"
        content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="img/icons/icon-48x48.png" />

    <link rel="canonical" href="https://demo-basic.adminkit.io/" />

    <title>ImagineShirt</title>

    {{-- <link href="css/app.css" rel="stylesheet"> --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    @vite('resources/sass/admintemplate/app.scss')
</head>

<body>
    <div class="wrapper">
        <nav id="sidebar" class="sidebar js-sidebar">
            <div class="sidebar-content js-simplebar">
                <a class="sidebar-brand" href="{{ route('dashboard.index') }}">
                    <span class="align-middle">Admin Panel</span>
                </a>

                <ul class="sidebar-nav">
                    <li class="sidebar-header">
                        Pages
                    </li>
                    @if ((Auth::user()->user_type ?? '') == 'A')
                        <li class="sidebar-item {{ Route::currentRouteName() == 'dashboard.index' ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('dashboard.index') }}">
                                <i class="align-middle" data-feather="sliders"></i> <span
                                    class="align-middle">Dashboard</span>
                            </a>
                        </li>
                    @endif
                    <li class="sidebar-item {{ Route::currentRouteName() == 'orders.index' ? 'active' : '' }}">
                        <a href="{{ route('orders.index') }}" class="sidebar-link" href="pages-profile.html">
                            <i class="align-middle" data-feather="package"></i> <span
                                class="align-middle">Encomendas</span>
                        </a>
                    </li>
                    @if ((Auth::user()->user_type ?? '') == 'A')
                        <li
                            class="sidebar-item {{ Route::currentRouteName() == 'tshirt_images.index' ? 'active' : '' }}">
                            <a href="{{ route('tshirt_images.index') }}" class="sidebar-link" href="pages-sign-in.html">
                                <i class="align-middle" data-feather="image"></i> <span class="align-middle">Imagens
                                    T-Shirt</span>
                            </a>
                        </li>

                        <li class="sidebar-item" {{ Route::currentRouteName() == 'categories.index' ? 'active' : '' }}>
                            <a class="sidebar-link" href="{{ route('categories.index') }}">
                                <i class="align-middle" data-feather="grid"></i> <span
                                    class="align-middle">Categorias</span>
                            </a>
                        </li>

                        <li class="sidebar-item"{{ Route::currentRouteName() == 'users.index' ? 'active' : '' }}>
                            <a class="sidebar-link" href="{{ route('users.index') }}">
                                <i class="align-middle" data-feather="user-plus"></i> <span
                                    class="align-middle">Pessoal</span>
                            </a>
                        </li>

                        <li class="sidebar-item" {{ Route::currentRouteName() == 'customers.index' ? 'active' : '' }}>
                            <a class="sidebar-link" href="{{ route('customers.index') }}">
                                <i class="align-middle" data-feather="users"></i> <span
                                    class="align-middle">Clientes</span>
                            </a>
                        </li>

                        <li class="sidebar-item" {{ Route::currentRouteName() == 'prices.index' ? 'active' : '' }}>
                            <a class="sidebar-link" href="{{ route('prices.index') }}">
                                <i class="align-middle" data-feather="dollar-sign"></i> <span
                                    class="align-middle">Preços</span>
                            </a>
                        </li>

                        <li class="sidebar-item" {{ Route::currentRouteName() == 'colors.index' ? 'active' : '' }}>
                            <a class="sidebar-link" href="{{ route('colors.index') }}">
                                <i class="align-middle" data-feather="image"></i> <span class="align-middle">Cores
                                    T-Shirt</span>
                            </a>
                        </li>

                        <li class="sidebar-item" style="padding-top: 70%">
                            <hr>
                            <a class="sidebar-link" href="{{ route('tshirt_images.catalogo') }}">
                                <i class="align-middle" data-feather="log-out"></i> <span class="align-middle">Voltar à
                                    Loja</span>
                            </a>
                        </li>
                    @else
                        <li class="sidebar-item" style="padding-top: 180%">
                            <hr>
                            <a class="sidebar-link" href="{{ route('tshirt_images.catalogo') }}">
                                <i class="align-middle" data-feather="log-out"></i> <span class="align-middle">Voltar à
                                    Loja</span>
                            </a>
                        </li>
                    @endif
            </div>
        </nav>

        <div class="main">
            <nav class="navbar navbar-expand navbar-light navbar-bg">
                <a class="sidebar-toggle js-sidebar-toggle" id="hamburger-btn">
                    <i class="hamburger align-self-center"></i>
                </a>

                <div class="navbar-collapse collapse">
                    <ul class="navbar-nav navbar-align">
                        <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#"
                                data-bs-toggle="dropdown">
                                <i class="align-middle" data-feather="settings"></i>
                            </a>

                            <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#"
                                data-bs-toggle="dropdown">
                                <span class="text-dark">{{ Auth::user()->name }}</span>
                                <img src="{{ Auth::user()->fullPhotoUrl }}" class="avatar img-fluid rounded me-1"
                                    alt="" />
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                @if ((Auth::user()->user_type ?? '') == 'A')
                                    <a class="dropdown-item"
                                        href="{{ route('users.show', ['user' => Auth::user()]) }}">
                                        <i class="align-middle me-1" data-feather="user"></i> Profile
                                    </a>
                                    <!-- Profile route -->
                                    <div class="dropdown-divider"></div>
                                @endif
                                <a class="dropdown-item"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Sair
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="content">
                @if (session('alert-msg'))
                    @include('shared.messages')
                @endif
                @if ($errors->any())
                    @include('shared.alertValidation')
                @endif
                @yield('main')
            </main>

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-muted">
                        <div class="col-6 text-start">
                            <p class="mb-0">
                                <a class="text-muted" href="#"><strong>Politécnico de Leiria 2023</strong></a>
                                &copy;
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    {{-- <script src="js/app.js"></script> --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite('resources/js/admintemplate/app.js')
</body>

</html>

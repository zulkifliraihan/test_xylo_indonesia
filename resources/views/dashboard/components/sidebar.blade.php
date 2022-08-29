<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand" href="{{ route('staff.parking.index') }}">
                    <img src="{{ asset('images/Logo Pesantren Go Digital.png') }}" alt="" srcset=""
                        style="width: 170px; height: 60px">
                </a>
            </li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i
                        class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i
                        class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc"
                        data-ticon="disc"></i></a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main navigation-menu" id="main-menu-navigation" data-menu="menu-navigation">
            <li class=" nav-item ">
                <a class="d-flex align-items-center" href="{{ route('staff.parking.index') }}">
                    <i data-feather="home"></i>
                    <span class="menu-title text-truncate" data-i18n="Email">Record Parking</span>
                </a>
            </li>
            {{-- <li class=" nav-item ">
                <a class="d-flex align-items-center" href="{{ route('staff.vendor.index') }}">
                    <i data-feather="type"></i>
                    <span class="menu-title text-truncate" data-i18n="Daftar Rekanan">Daftar Rekanan</span>
                </a>
            </li> --}}
        </ul>
    </div>
</div>
<!-- END: Main Menu-->

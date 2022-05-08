<!-- ============================================================== -->
<!-- Topbar header - style you can find in pages.scss -->
<!-- ============================================================== -->
<header class="topbar" data-navbarbg="skin5">
    <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <div class="navbar-header" data-logobg="skin5" style="padding-left: 10px;">
            <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
            <!-- <a class="navbar-brand" href="dashboard.php"> -->
                <b class="logo-icon"><img src="assets/images/logo.png" alt="homepage" class="light-logo" /></b>
            <!-- </a> -->
        <? if (isset($_SESSION) && !empty($_SESSION)){ ?>
            <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
        <? } ?>

    </div>
    <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
        <ul class="navbar-nav float-left mr-auto">
            <? if (isset($_SESSION) && !empty($_SESSION)){ ?>
                <li class="nav-item d-none d-md-block"><a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)" data-sidebartype="mini-sidebar"><i class="mdi mdi-menu font-24"></i></a></li>
            <? } ?>

            <? if ($_DEBUG && ($WEBSITE_HOST.'' == '127.0.0.1' || $WEBSITE_HOST.'' == '192.168.1.6')){ ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       <span class="d-none d-md-block">Local Dev&nbsp; <i class="fa fa-angle-down"></i></span>                       
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="http://127.0.0.1/hkproperty_html" target="_blank">HTML Template</a>
                            <a class="dropdown-item" href="#" target="_blank" data-toggle="modal" data-target="#Modal2">Alert</a>
                    </div>
                </li>
            <? } ?>
        </ul>

    <? if (isset($_SESSION) && !empty($_SESSION)){ ?>
    <ul class="navbar-nav float-right">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="assets/images/users/user3.png" alt="user" class="rounded-circle" width="31"></a>
            <div class="dropdown-menu dropdown-menu-right user-dd animated">
                <!-- <a class="dropdown-item" href="javascript:void(0)"><i class="ti-user m-r-5 m-l-5"></i> My Profile</a> -->
                <!-- <a class="dropdown-item" href="javascript:void(0)"><i class="ti-wallet m-r-5 m-l-5"></i> My Balance</a> -->
                <!-- <a class="dropdown-item" href="javascript:void(0)"><i class="ti-email m-r-5 m-l-5"></i> Inbox</a> -->
                <!--  <a class="dropdown-item" href="javascript:void(0)"><i class="ti-settings m-r-5 m-l-5"></i> Account Setting</a> -->
                <a class="dropdown-item" href="logout.php"><i class="fa fa-power-off m-r-5 m-l-5"></i> Logout</a>

            </div>
        </ul>
    </div>
    <? } ?>
</nav>
</header>

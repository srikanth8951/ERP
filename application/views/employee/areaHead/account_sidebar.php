<!-- ========== Left Sidebar Start ========== -->
<div class="left side-menu">

    <!-- LOGO -->
    <div class="topbar-left">
        <div class="">
            <!--<a href="index.html" class="logo text-center">Admiria</a>-->
            <a href="<?= base_url('employee/areaHead/dashboard') ?>" class="logo"><img src="<?= base_url() ?>assets/images/SW-logo.png" height="36" alt="logo"></a>
        </div>
    </div>

    <div class="sidebar-inner slimscrollleft">
        <div id="sidebar-menu">
            <ul>

                <li class="menu-title">Main</li>
                <li>
                    <a href="<?= base_url() ?>employee/areaHead/dashboard" class="waves-effect"><i class="mdi mdi-view-dashboard"></i><span> Dashboard </span></a>
                </li>
               
                <li>
                    <a href="<?= base_url() ?>employee/areaHead/contract_job/" class="waves-effect"><i class="mdi mdi-view-dashboard"></i><span> Job/Contract </span></a>
                </li>
                <li>
                    <a href="<?= base_url() ?>employee/areaHead/customer" class="waves-effect"><i class="mdi mdi-view-dashboard"></i><span> Customer </span></a>
                </li>
                <li>
                    <a href="<?= base_url() ?>employee/areaHead/asset" class="waves-effect"><i class="mdi mdi-view-dashboard"></i><span> Assets </span></a>
                </li>
            </ul>
        </div>
        <div class="clearfix"></div>
    </div> <!-- end sidebarinner -->
</div>
<!-- Left Sidebar End -->
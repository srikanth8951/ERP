
<!-- Top Bar Start -->
<div class="topbar">

<nav class="navbar-custom">
    

    <ul class="list-inline float-right mb-0">
        
        <!-- notification-->
        <li class="list-inline-item dropdown notification-list">
            <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button"
               aria-haspopup="false" aria-expanded="false">
                <i class="ion-ios7-bell noti-icon"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-menu-lg">
                <!-- item-->
                <div class="dropdown-item noti-title">
                    <h5>Notification</h5>
                </div>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <div class="notify-icon bg-info"><i class="mdi mdi-message"></i></div>
                    <p class="notify-details"><b>No Message Available</b></p>
                </a>

            </div>
        </li>
        <!-- User-->
        <li class="list-inline-item dropdown notification-list">
            <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button"
               aria-haspopup="false" aria-expanded="false" id="profile-image">
                <!-- <img src="<?= base_url()?>assets/images/users/avatar-1.jpg" alt="user" class="rounded-circle"> -->
            </a>
            <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                <a class="dropdown-item" href="<?= base_url()?>admin/profile/"><i class="dripicons-user text-muted"></i> Profile</a>
                <a class="dropdown-item" href="<?= base_url()?>admin/password/"><i class="dripicons-lock text-muted"></i> Password</a>
                <a class="dropdown-item" href="<?= base_url()?>admin/notification/"><i class="dripicons-bell text-muted"></i> Notifications</a>
                <a class="dropdown-item" href="#"><span class="badge badge-success pull-right m-t-5">5</span><i class="dripicons-gear text-muted"></i> Settings</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="javascript:void(0);" id="app-logout"><i class="dripicons-exit text-muted"></i> Logout</a>
            </div>
        </li>
    </ul>

    <!-- Page title -->
    <ul class="list-inline menu-left mb-0">
        <li class="list-inline-item">
            <button type="button" class="button-menu-mobile open-left waves-effect">
                <i class="ion-navicon"></i>
            </button>
        </li>
        <li class="hide-phone list-inline-item app-search">
            <h3 class="page-title"><?= $page_name ?></h3>
        </li>
    </ul>

    <div class="clearfix"></div>
</nav>

</div>
<!-- Top Bar End -->
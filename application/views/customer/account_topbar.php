<!-- Top Bar Start -->
<div class="topbar">

<nav class="navbar-custom">
    

    <ul class="list-inline float-right mb-0">
        
        <!-- notification-->
        <li class="list-inline-item dropdown notification-list">
            <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button"
               aria-haspopup="false" aria-expanded="false">
                <i class="ion-ios7-bell noti-icon"></i>
                <span class="badge badge-danger noti-icon-badge">3</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-menu-lg">
                <!-- item-->
                <div class="dropdown-item noti-title">
                    <h5>Notification (3)</h5>
                </div>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item active">
                    <div class="notify-icon bg-success"><i class="mdi mdi-cart-outline"></i></div>
                    <p class="notify-details"><b>Your order is placed</b><small class="text-muted">Dummy text of the printing and typesetting industry.</small></p>
                </a>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <div class="notify-icon bg-warning"><i class="mdi mdi-message"></i></div>
                    <p class="notify-details"><b>New Message received</b><small class="text-muted">You have 87 unread messages</small></p>
                </a>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <div class="notify-icon bg-info"><i class="mdi mdi-martini"></i></div>
                    <p class="notify-details"><b>Your item is shipped</b><small class="text-muted">It is a long established fact that a reader will</small></p>
                </a>

                <!-- All-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    View All
                </a>

            </div>
        </li>
        <!-- User-->
        <li class="list-inline-item dropdown notification-list">
            <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button"
               aria-haspopup="false" aria-expanded="false">
                <img src="<?= base_url()?>assets/images/users/avatar-1.jpg" alt="user" class="rounded-circle">
            </a>
            <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                <a class="dropdown-item" href="<?= base_url("customer/profile") ?>"><i class="dripicons-user text-muted"></i> Profile</a>
                <a class="dropdown-item" href="<?= base_url()?>customer/password/"><i class="dripicons-lock text-muted"></i> Password</a>
                <a class="dropdown-item" href="<?= base_url()?>customer/notification/"><i class="dripicons-bell text-muted"></i> Notifications</a>
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
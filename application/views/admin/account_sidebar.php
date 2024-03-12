<!-- ========== Left Sidebar Start ========== -->
<div class="left side-menu">

    <!-- LOGO -->
    <div class="topbar-left">
        <div class="">
            <!--<a href="index.html" class="logo text-center">Admiria</a>-->
            <a href="<?= base_url() ?>admin/dashboard" class="logo"><img src="<?= base_url() ?>assets/images/SW-logo.png" height="36" alt="logo"></a>
        </div>
    </div>

    <div class="sidebar-inner slimscrollleft">
        <div id="sidebar-menu">
            <ul id="swnav">

                <li class="menu-title">Main</li>
                <li class="">
                    <a href="<?= base_url() ?>admin/dashboard" class="waves-effect"><i class="mdi mdi-view-dashboard"></i><span> Dashboard </span></a>
                </li>
                <li>
                    <a href="<?= base_url() ?>admin/contract_job/" class="waves-effect"><i class="mdi mdi-clipboard-text"></i><span> Job/Contract </span></a>
                </li>
                <li class="menu-title">O&M</li>
                <li>
                    <a href="<?= base_url() ?>admin/contract_job/operation_and_maintenance" class="waves-effect"><i class="mdi mdi-clipboard-text"></i><span> Job/Contract </span></a>
                </li>
                <li class="menu-title">Users</li>
                <li>
                    <a href="<?= base_url() ?>admin/national_head/" class="waves-effect"><i class="mdi mdi-crown"></i><span> National Head </span></a>
                </li>
                <li>
                    <a href="<?= base_url() ?>admin/aisd_head/" class="waves-effect"><i class="mdi mdi-account-box"></i><span> AISD Head </span></a>
                </li>
                <li>
                    <a href="<?= base_url() ?>admin/regional_head/" class="waves-effect"><i class="mdi mdi-google-maps"></i><span> Regional Head </span></a>
                </li>
                <li>
                    <a href="<?= base_url() ?>admin/rsd_head/" class="waves-effect"><i class="mdi mdi-houzz-box"></i><span> RSD Head </span></a>
                </li>
                <li>
                    <a href="<?= base_url() ?>admin/asd_head/" class="waves-effect"><i class="mdi mdi-map-marker-radius"></i><span> ASD Head </span></a>
                </li>
                <li>
                    <a href="<?= base_url() ?>admin/area_head/" class="waves-effect"><i class="mdi mdi-chart-bar"></i><span> Area Marketing Head </span></a>
                </li>
                <li>
                    <a href="<?= base_url() ?>admin/cam/" class="waves-effect"><i class="mdi mdi-account-star-variant"></i><span> Client Account Manager </span></a>
                </li>
                <li>
                    <a href="<?= base_url() ?>admin/engineer/" class="waves-effect"><i class="mdi mdi-account"></i><span> Engineer </span></a>
                </li>
                <li>
                    <a href="<?= base_url() ?>admin/manager/" class="waves-effect"><i class="mdi mdi-worker"></i><span> Site Incharge </span></a>
                </li>
                <li>
                    <a href="<?= base_url() ?>admin/supervisor/" class="waves-effect"><i class="mdi mdi-walk"></i><span> O&M Supervisor </span></a>
                </li>
                <li>
                    <a href="<?= base_url() ?>admin/technician/" class="waves-effect"><i class="mdi mdi-run"></i><span> O&M Technician </span></a>
                </li>
                <li>
                    <a href="<?= base_url() ?>admin/dmt/" class="waves-effect"><i class="mdi mdi-database"></i><span>Data management </span></a>
                </li>
                <li>
                    <a href="<?= base_url() ?>admin/customer/" class="waves-effect"><i class="mdi mdi-view-dashboard"></i><span>Customer </span></a>
                </li>
                <li>
                    <a href="<?= base_url() ?>admin/vendor/" class="waves-effect"><i class="mdi mdi-view-dashboard"></i><span>Vendor </span></a>
                </li>

                <li class="menu-title">Extras</li>


                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-email-outline"></i><span> Store <span class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                    <ul class="list-unstyled">
                        <li><a class="" href="<?= base_url() ?>admin/store/products/">Spare Part</a></li>
                        <li><a class="" href="<?= base_url() ?>admin/store/request/">Requests</a></li>
                        <li><a class="" href="<?= base_url() ?>admin/store/users/">Users</a></li>
                        <!-- <li><a class="" href="<?= base_url() ?>admin/store/attribute_group/">Attributes Group</a></li>
                        <li><a class="" href="<?= base_url() ?>admin/store/attribute/">Attributes</a></li> -->
                        <li><a class="" href="<?= base_url() ?>admin/store/category/">Category</a></li>
                        <li><a class="" href="<?= base_url() ?>admin/store/sub_category/">Sub-Category</a></li>

                    </ul>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect disabled-event"><i class="mdi mdi-email-outline"></i><span> Complaints <span class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                    <ul class="list-unstyled">
                        <li><a class="disabled-event" href="<?= base_url() ?>admin/complaint">All</a></li>
                        <li><a class="disabled-event" href="<?= base_url() ?>admin/complaint">Assigned</a></li>
                        <li><a class="disabled-event" href="<?= base_url() ?>admin/complaint">Unassigned</a></li>
                        <li><a class="disabled-event" href="<?= base_url() ?>admin/complaint/status">Statuses</a></li>
                    </ul>
                </li>
                <li>
                    <a href="calendar.html" class="waves-effect disabled-event"><i class="mdi mdi-view-dashboard"></i><span>O & M </span></a>
                </li>
                <li class="menu-title">Assets</li>
                <li>
                    <a href="<?= base_url() ?>admin/asset" class="waves-effect"><i class="mdi mdi-view-dashboard"></i><span>List </span></a>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-email-outline"></i><span> Group <span class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                    <ul class="list-unstyled">
                        <li><a href="<?= base_url()?>admin/group">Asset Group</a></li>
                        <li><a href="<?= base_url()?>admin/group/subgroup">Asset Sub Group</a></li>
                    </ul>
                </li>
                <li class="menu-title">Settings</li>

                <li>
                    <a href="<?= base_url() ?>admin/region" class="waves-effect"><i class="mdi mdi-map"></i><span> Region </span></a>
                </li>

                <li>
                    <a href="<?= base_url() ?>admin/branch" class="waves-effect"><i class="mdi mdi-source-branch"></i><span> Branch </span></a>
                </li>

                <li>
                    <a href="<?= base_url() ?>admin/area" class="waves-effect"><i class="mdi mdi-crosshairs-gps"></i><span> City<span class="small">(area)</span> </span></a>
                </li>

                <li>
                    <a href="<?= base_url() ?>admin/department" class="waves-effect"><i class="mdi mdi-briefcase"></i><span> Department </span></a>
                </li>
                <li>
                    <a href="<?= base_url() ?>admin/designation" class="waves-effect"><i class="mdi mdi-bookmark"></i><span> Designation </span></a>
                </li>
                <li>
                    <a href="<?= base_url() ?>admin/payment_terms" class="waves-effect"><i class="mdi mdi-wallet-membership"></i><span> Payment Terms </span></a>
                </li>
                <li>
                    <a href="<?= base_url() ?>admin/contract_nature" class="waves-effect"><i class="mdi mdi-book"></i><span> Nature of Contract </span></a>
                </li>
                <li>
                    <a href="<?= base_url() ?>admin/work_expertise" class="waves-effect"><i class="mdi mdi-star-circle"></i><span> Work Expertise </span></a>
                </li>
                <li>
                    <a href="<?= base_url() ?>admin/customer_sector" class="waves-effect"><i class="mdi mdi-flip-to-back"></i><span> Customer Sector </span></a>
                </li>
                <li>
                    <a href="<?= base_url() ?>admin/standard_operating_procedure" class="waves-effect"><i class="mdi mdi-pokeball"></i><span> SOP</span></a>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="fa fa-check-square-o"></i><span> Checklist <span class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                    <ul class="list-unstyled">
                        <li><a href="<?= base_url()?>admin/catalog/checklist/ppm">PPM Checklist</a></li>
                        <li><a href="<?= base_url()?>admin/catalog/checklist/daily">Daily Checklist</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="clearfix"></div>
    </div> <!-- end sidebarinner -->
</div>
<!-- Left Sidebar End -->
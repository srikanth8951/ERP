<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>SW-Application</title>
    <meta content="Admin Dashboard" name="description" />
    <meta content="Mentric" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App Icons -->
    <link rel="shortcut icon" href="<?= base_url('assets/images/sterling-wilson.png'); ?>">

    <?php require_once APPPATH . 'views/layout/headerStyles.php'; ?>
    <?php require_once APPPATH . 'views/layout/headerScripts.php'; ?>

</head>


<body class="fixed-left">

    <?php require_once APPPATH . 'views/layout/preloader.php'; ?>


    <!-- Begin page -->
    <div id="wrapper">
        <?php require_once 'account_sidebar.php'; ?>

        <!-- Start right Content here -->
        <div class="content-page">
            <!-- Start content -->
            <div class="content">

                <?php require_once 'account_topbar.php'; ?>

                <!-- ==================
                         PAGE CONTENT START
                         ================== -->

                <!-- start- load content view -->
                <?php $this->load->view($view_name) ?>
                <!-- end- load content view -->

            </div> <!-- content -->

            <footer class="footer">
                © 2021 Sterling Wilson <span class="text-muted d-none d-sm-inline-block float-right">Mentric technologies</span>
            </footer>

        </div>
        <!-- End Right content here -->

    </div>
    <!-- END wrapper -->

    <?php require_once APPPATH . 'views/layout/footerScripts.php'; ?>

    <?php $scripts = $this->document->getScripts('footer'); ?>
    <?php if ($scripts) { ?>
        <!-- Load script links -->
        <?php foreach ($scripts as $script) { ?>
            <?php
            $attributeText = '';
            $attributes = $script['attributes'];
            if ($attributes) {
                foreach ($attributes as $akey => $avalue) {
                    $attributeText .= $akey . '="' . $avalue . '" ';
                }
            }

            $attributeText = $attributeText ? trim($attributeText) : 'type="text/javascript"';
            ?>
            <script <?php echo $attributeText; ?> src="<?php echo $script['href']; ?>"></script>
        <?php } ?>
    <?php } ?>

</body>

</html>
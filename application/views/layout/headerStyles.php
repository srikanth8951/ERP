<!-- Basic Css files -->
<link href="<?= base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url('assets/css/icons.css')?>" rel="stylesheet" type="text/css" />

<!-- sweet alert -->
<link href="<?= base_url('assets/plugins/sweet-alert2/sweetalert2.min.css')?>" rel="stylesheet" type="text/css" />

<!-- Toastr -->
<link href="<?= base_url('assets/plugins/toastr/toastr.min.css')?>" rel="stylesheet" type="text/css" />

<!-- select2 -->
<link href="<?= base_url('assets/plugins/select2/css/select2.min.css')?>" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/datetimepicker/jquery.datetimepicker.min.css')?> " />

<link href="<?= base_url('assets/css/style.css')?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url('assets/css/custom.css')?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url('assets/css/navbar.css')?>" rel="stylesheet" type="text/css" />

<script>
    //Config Statuses
    const statusesStr = '<?php $statuses = getStatuses(); echo $statuses ? json_encode($statuses) : ""; ?>';
    const qstatusesStr = '<?php $statuses = getQStatuses(); echo $statuses ? json_encode($statuses) : ""; ?>';
</script>
<div class="page-content-wrapper">

    <div class="container-fluid" id="asset--deatils--area">
        
        <div class="row">
            <div class="col">
                <div class="card m-b-20">
                    <div class="card-header d-flex align-items-center justify-content-between bg-white">
                        <h4 class="m-0">Asset View</h4>
                        <a href="<?php echo base_url('employee/cam/asset'); ?>" class="btn btn-sm btn-outline-secondary waves-effect waves-light"><i class=" mdi mdi-chevron-double-left"></i>&nbsp;Back</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div data-container="assetDetailArea"></div>

    </div><!-- container -->

</div> <!-- Page content Wrapper -->

<script>
    window.assetId = '<?php echo $asset_id; ?>';
</script>

<!-- Custom Js -->
<script src="<?php echo base_url('assets/js/include/check_login.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/include/employee/cam/asset_view.js'); ?>"></script>

<script>
    $(function(){
                   
        function loadInitFunctions() {
            loadAssetDetail();
        }

        // Check Login
        $.when(wapLogin.check()).done(function (res) {
            if (res.status == 'success') {
                console.log(res.message);
                appUser = res.user; // Set user infos
                wapLogin.setStatus(res.login);
                loadInitFunctions();
            } else if(res.status == 'error') {
                Swal.fire({
                    icon: 'error',
                    title: res.message,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then(function () {
                    wapLogin.setStatus(res.login);
                });
            } else {
                wapLogin.setStatus(false);
                wapLogin.showDialog(res.message);
            }
        }).fail(function(jqXHR, textStatus) {
            wapLogin.setStatus(false);
            Swal.fire({
                icon: 'error',
                title: 'Something went wrong! Contact support',
                allowOutsideClick: false,
                allowEscapeKey: false,
            });
        });
    });
</script>
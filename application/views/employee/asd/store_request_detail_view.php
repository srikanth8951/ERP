<?php
$request_id = $request_id ?? 0;
?>
<style>
    .status-block {
        min-width: 100px;
        padding: 1rem;
        height: 80px;
        background-color: #ccc;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
    }
</style>
<div class="page-content-wrapper">

    <div class="container-fluid">

        <div class="row">
            <div class="col-12" id="request--deatils--area">
                <div class="card m-b-20">
                    <div class="card-body" id="heading-area">
                    </div>
                </div>
                <div class="card m-b-20">
                    <div class="card-body">
                        <div class="row" id="request-detail-area">
                        </div>
                    </div>
                </div>
                <div class="card card-body mb-3">
                    <p class="mb-2"><b>Requested Spare Parts Details</b></p>
                    <div class="attachments-block">
                        <div class="row">
                            <div class="col-12">
                                <div class="card card-body bg-light position-relative py-2 mb-2">
                                    <div class="media">
                                        <div class="media-body">
                                            <table class="table table-borderless" id="request-product-detail">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Spare Part Name</th>
                                                        <th scope="col">Category</th>
                                                        <th scope="col">Sub-Category</th>
                                                        <th scope="col">Requested Quantity</th>
                                                        <th scope="col">Total Price</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-12">
                                <p>No details</p>
                            </div> -->
                        </div>
                    </div>
                </div>

            </div> <!-- end col -->
        </div> <!-- end row -->

    </div><!-- container -->

</div> <!-- Page content Wrapper -->

<script>
    const request_id = "<?php echo $request_id; ?>";
    $(function() {
        window.requestbDetailArea = $('#request--deatils--area');
    });
</script>
<script src="<?php echo base_url('assets/js/include/check_login.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/include/employee/asd/store_product_request_view.js'); ?>"></script>
<script>
    $(function() {
        $('[data-toggle="select2"]').select2();
        moment().format();

        function loadInitFunctions() {
            loadRequestDetail();
        }

        // Check Login
        $.when(wapLogin.check()).done(function(res) {
            if (res.status == 'success') {
                console.log(res.message);
                appUser = res.user; // Set user infos
                wapLogin.setStatus(res.login);
                loadInitFunctions();
            } else if (res.status == 'error') {
                Swal.fire({
                    icon: 'error',
                    title: res.message,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then(function() {
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
                // timer: 3000,
                // timerProgressBar: true
            });
        });
    });
</script>
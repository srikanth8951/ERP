<div class="page-content-wrapper">
    
    <div class="container-fluid" id="contractjob--deatils--area">
        <div class="p-2" style="background-color: #dee1e5;"> 
            <div class="row">
                <div class="col">
                    <form id="searchForm">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search" />
                            <button type="submit" class="input-group-append input-group-text btn btn-sm waves-effect waves-light">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <br>
        <div class="row" data-container="contractJobArea"></div>
        <div class="row align-items-center justify-content-between" data-pagination="contractJobArea">
            <div class="col">
                <div class="list-pagination-label float-left"></div>
                <div class="list-pagination navigation float-right"></div>
            </div>
        </div>

    </div><!-- container -->

</div> <!-- Page content Wrapper -->


<!-- Pagination -->
<script src="<?php echo base_url('assets/js/jquery.simplePagination.js'); ?>"></script>

<!-- Custom Js -->
<script src="<?php echo base_url('assets/js/include/employee/check_login.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/include/employee/cam/contract_job_list.js'); ?>"></script>

<script>
    $(function(){
        $('[data-toggle="select2"]').select2();
           
        function loadInitFunctions() {
            loadContractJobDetails(formApiUrl('employee/cam/contract_job/list'));
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
                // timer: 3000,
                // timerProgressBar: true
            });
        });
    });
</script>
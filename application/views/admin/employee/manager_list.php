<div class="page-content-wrapper">
    
    <div class="container-fluid" id="empmanager--deatils--area">
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
                
                <div class="col text-right">
                    <a data-toggle="modal" href="#empUploadModal" class="btn  btn-outline-dark waves-effect waves-light ">Upload</a>
                    <a href="<?php echo base_url('admin/manager/add') ?>" class="btn  btn-outline-primary waves-effect waves-light ">Add</a>
                </div>
            </div>
        </div>
        <br>
        <div class="row" data-container="empManagerArea"></div>

        <div class="row align-items-center justify-content-between" data-pagination="empManagerArea">
            <div class="col">
                <div class="list-pagination-label float-left"></div>
                <div class="list-pagination navigation float-right"></div>
            </div>
        </div>
        <!-- end row -->

    </div><!-- container -->

</div> <!-- Page content Wrapper -->

<!-- Upload Modal -->
<div class="modal fade" id="empUploadModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Bulk upload</h5>
                <button data-dismiss="modal" class="close">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="emp-bulk-upload" />
                        <label class="custom-file-label" for="emp-bulk-upload">Choose File</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <a class="btn btn-link btn-sm" href="javascript:void(0);"><i class="fa fa-file-excel-o"></i>&nbsp;Download sample</a>
                <button class="btn btn-sm btn-primary" type="button"><i class="fa fa-cloud-download"></i>&nbsp;Upload</button>
            </div>
        </div>
    </div>
</div>

<!-- Pagination -->
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.simplePagination.js'); ?>"></script>

<!-- Custom Js -->
<script type="text/javascript" src="<?php echo base_url('assets/js/include/check_login.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/include/admin/manager_list.js'); ?>"></script>

<script>
    $(function() {
        $('[data-toggle="select2"]').select2();

        function loadInitFunctions() {
            loadEmpDetails(formApiUrl('admin/employee/manager/list'));
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

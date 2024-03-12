<div class="page-content-wrapper">
    
    <div class="container-fluid" id="empasdhead--deatils--area">
    <div class="card card-body p-2">
            <div class="row">
                <div class="col">
                    <form id="searchForm" class="mt-1">
                        <div class="row height d-flex justify-content-center align-items-center">
                            <div class="col-md-12">
                                <div class="form">
                                    <i class="mdi mdi-magnify">
                                    </i> <input type="text" class="form-control form-input" name="search" placeholder="Search here..."> <span class="left-pan"></span>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search" />
                            <button type="submit" class="input-group-append input-group-text btn btn-sm waves-effect waves-light">Search</button>
                        </div> -->
                    </form>
                </div>

                <div class="col text-right">
                    <a data-toggle="modal" href="#asdHeadUploadModal" data-backdrop="static" data-keyboard="false" class="btn btn-outline-info mt-1"><i class=" mdi mdi-folder-upload"></i> Upload</a>
                    <a href="<?php echo base_url('admin/asd_head/add') ?>" class="btn btn-outline-primary mt-1"><i class="mdi mdi-account-plus"></i> Add</a>
                    <a href="<?php echo base_url(); ?>admin/dashboard" class="btn btn-outline-secondary mt-1"><i class="mdi mdi-chevron-double-left"></i> Back</a>
                </div>
            </div>
        </div>
        <br>
        <div class="row" data-container="empASDHeadArea"></div>

        <div class="row align-items-center justify-content-between" data-pagination="empASDHeadArea">
            <div class="col">
                <div class="list-pagination-label float-left"></div>
                <div class="list-pagination navigation float-right"></div>
            </div>
        </div>
        <!-- end row -->

    </div><!-- container -->

</div> <!-- Page content Wrapper -->

<!-- Upload Modal -->
<div class="modal fade" id="asdHeadUploadModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Bulk upload</h5>
                <button data-dismiss="modal" class="close">&times;</button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" enctype="multipart/form-data" method="post" id="uploadForm">
                    <div class="form-group row">
                        <div class="col">
                            <input type="file" class="filestyle" data-buttonname="btn-secondary" required id="file" placeholder="Enter file" name="file">
                        </div>
                    </div>
                </form>
            </div>            
            <div class="modal-footer justify-content-between">
            <a class="btn btn-link btn-sm" id="btn-download-upload-sample" href="javascript:void(0);"><i class="fa fa-file-excel-o"></i>&nbsp;Download sample</a>
                <button form="uploadForm" class="btn btn-primary" id="btn-upload-asd-head">Submit</button>
            </div>
        </div>
    </div>
</div>

<div id="asdHeadListModal" class="modal fade add-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Area Service Delivery List</h4>
            </div>
            <span id="duplicate-msg" style="text-align: center;color:#f02121;margin: 10px;"></span>

            <form id="uploadForm" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/formdata">   
                <div class="modal-body" style="overflow: scroll;max-height: 400px;">                                                                 
                   <div class="table-responsive-sm mt-6">
                        <table id="asdHeadTableId" class="table table-striped table-centered mb-0">
                        </table>
                    </div>    
                </div> 
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="asd-head-submit">Submit</button>   
                </div>                                                   
            </form>
        </div>
    </div>
</div>

<!-- Pagination -->
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.simplePagination.js'); ?>"></script>

<!-- Custom Js -->
<script type="text/javascript" src="<?php echo base_url('assets/js/include/check_login.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/include/admin/asd_head_list.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/include/admin/upload_asd_head.js'); ?>"></script>
<script>
    $(function() {
        $('[data-toggle="select2"]').select2();

        function loadInitFunctions() {
            loadEmpDetails(formApiUrl('admin/employee/asd_head/list'));
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

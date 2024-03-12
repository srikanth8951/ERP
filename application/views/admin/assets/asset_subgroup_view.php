<div class="page-content-wrapper">

    <div class="container-fluid">
        <div class="card m-b-20" id="sub-group--deatils--area">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-12 text-right">
                        <!-- <a data-toggle="modal" href="#sub-groupUploadModal" data-backdrop="static" data-keyboard="false" class="btn  btn-outline-dark waves-effect waves-light ">Upload</a> -->
                        <button id="btn-add-sub-group" class="btn btn-outline-primary waves-effect waves-light">Add</button>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-sm-12 col-md-6">
                        <div class="d-flex align-items-center w-25 d-search">
                            <label class="mb-0 mr-2">Show </label>
                            <select data-jy-length="record" name="length" class="custom-select custom-select-sm form-control form-control-sm">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 text-md-right">
                        <div class="w-50 ml-auto">
                            <div class="d-flex align-items-center d-search">
                                <label class="mb-0 mr-2">Search:</label>
                                <form data-jy-search="record">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control form-control-sm" placeholder="" />
                                        <button type="submit" class="input-group-append input-group-text btn btn-sm"><i class="fa fa-search"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12" data-container="sub-groupListArea">
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered dt-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th width="15%">Sl.No</th>
                                        <th>Sub Group name</th>
                                        <th>Group name</th>
                                        <th>Status</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody data-container="sub-groupTlistArea">
                                </tbody>
                            </table>
                        </div>
                        <div data-pagination="sub-groupTlistArea" class="clearfix mb-4">
                            <div class="list-pagination-label float-left"></div>
                            <div class="list-pagination navigation float-right"></div>
                        </div>
                    </div> <!-- end col -->
                </div> <!-- end row -->
            </div>
        </div>
    </div><!-- container -->

</div> <!-- Page content Wrapper -->

<!-- sub-group Modal -->
<div class="modal fade" id="sub-groupModal">
    <div class="modal-dialog modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="font-18 modal-title">Sub Group</h4>
                <button type="button" data-dismiss="modal" class="close"><i class="mdi mdi-close"></i></button>
            </div>
            <div class="modal-body">
                <form id="sub-groupForm" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group ele-jqValid">
                                <label class="control-label">Group<span class="text-danger"> *</span></label>
                                <select name="parent" data-toggle="select2" class="select2"></select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="control-label">Name<span class="text-danger"> *</span></label>
                                <input type="text" name="group_name" class="form-control" />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group ele-jqValid">
                                <label class="control-label">Status<span class="text-danger"> *</span></label>
                                <select name="status" data-toggle="select2" class="select2">
                                    <option value="">Select</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <a href="javascript:void(0)" id="btn-reset-sub-group-form" class="btn btn-secondary btn-sm" style="display:none;">
                            <i class="mdi mdi-reload"></i>&nbsp;Reset
                        </a>
                        <button type="submit" class="btn btn-indigo btn-sm">
                            <i class="mdi mdi-content-save"></i>&nbsp;Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="sub-groupUploadModal">
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
                <a class="btn btn-link btn-sm" href="<?php echo base_url('assets/uploads/sample-format.csv'); ?>"><i class="fa fa-file-excel-o"></i>&nbsp;Download sample</a>
                <button form="uploadForm" class="btn btn-primary" id="btn-upload-sub-group">Submit</button>
            </div>
        </div>
    </div>
</div>


<div id="sub-groupListModal" class="modal fade add-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Sub Groups List</h4>
            </div>
            <span id="emsg" style="display: none;text-align: center;color:#f02121;margin: 10px;">Already Existing Groups are Higlighted</span>
            <span id="duplicate-msg" style="text-align: center;color:#f02121;margin: 10px;"></span>

            <form id="uploadForm" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/formdata">
                <div class="modal-body" style="overflow: scroll;max-height: 400px;">
                    <div class="table-responsive-sm mt-6">
                        <table id="sub-groupTableId" class="table table-striped table-centered mb-0">
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="sub-group-submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const formActionUrl = 'admin/asset/group/sub_group/list';
</script>

<!-- Pagination -->
<script src="<?php echo base_url('assets/js/jquery.simplePagination.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js'); ?>"></script>

<!-- Custom Js -->
<script src="<?php echo base_url('assets/js/include/check_login.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/include/admin/sub-group.js'); ?>"></script>
<!-- <script src="<?php echo base_url('assets/js/include/admin/upload_sub-group.js'); ?>"></script> -->

<script>
    $(function() {

        $('[data-toggle="select2"]').select2();

        function loadInitFunctions() {
            loadDetails(formApiUrl(formActionUrl));
            loadAutocompleteparents();
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
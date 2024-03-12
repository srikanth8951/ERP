<?php
    $group = isset($group) ? $group : '';
?>

<script>
    window.checklist_group = '<?php echo $group; ?>';
</script>
<div class="page-content-wrapper">

    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card m-b-20">
                    <div class="card-body">
                        <div class="clearfix mb-4">
                            <button id="btn-add-checklist" data-permission="add_checklist" class="btn btn-indigo btn-sm waves-effect waves-light float-right"><i class="mdi mdi-plus"></i>&nbsp;Checklist</button>
                            <h4 class="mt-0 header-title lead"><i class="mdi mdi-view-list"></i>&nbsp;<?php echo $this->lang->line('text_list'); ?></h4>
                        </div>


                        <div class="table-rep-plugin" id="checklist--deatils--area">
                            <div class="table-responsive mb-0" data-pattern="priority-columns">
                                <table id="tech-companies-1" class="table  table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th>Type</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody data-container="checklistArea">
                                        <tr data-jy-loader="timeline"></tr>
                                        <tr data-jy-loader="timeline"></tr>
                                        <tr data-jy-loader="timeline"></tr>
                                        <tr data-jy-loader="timeline"></tr>
                                        <tr data-jy-loader="timeline"></tr>
                                    </tbody>
                                </table>
                            </div>

                            <div data-pagination="checklistArea" class="navigation my-4"></div>
                        </div>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div><!-- container -->

</div> <!-- Page content Wrapper -->

<!-- checklist Modal -->
<div class="modal fade" id="checklistModal">
    <div class="modal-dialog modal-dialog-slideout">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="font-18 modal-title">Add/Edit checklist</h4>
                <button type="button" data-dismiss="modal" class="close"><i class="mdi mdi-close"></i></button>
            </div>
            <div class="modal-body">
                <form id="checklistForm" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Name<span class="text-danger">*</span></label>
                                <input type="text" name="checklist_name" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Description</label>
                                <textarea class="form-control" name="checklist_description" id="checklist-description" rows="4"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Checklist Type</label>
                                <div class="col-md-10">
                                    <input value="1" class="radio-inline mx-2" type="radio" name="checklist_type" checked="">Type 1
                                    <input value="2" class="radio-inline mx-2" type="radio" name="checklist_type">Type 2(sub divisions)
                                </div>
                            </div>
                            <?php $statuses = array(
                                1 => 'Active',
                                0 => 'Inactive'
                            ); ?>
                            <div class="form-group ele-jqValid">
                                <label class="control-label">Status<span class="text-danger">*</span></label>
                                <select class="form-control select2" data-toggle="select2" name="checklist_status">
                                    <option value="">select</option>
                                    <?php foreach ($statuses as $skey => $status) { ?>
                                        <option value="<?php echo $skey; ?>"><?php echo $status; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="form-group mb-0">
                    <a href="javascript:void(0)" id="btn-reset-checklist-form" class="btn btn-secondary btn-sm" style="display:none;">
                        <i class="mdi mdi-reload"></i>&nbsp;Reset
                    </a>
                    <button form="checklistForm" type="submit" class="btn btn-indigo btn-sm">
                        <i class="mdi mdi-content-save"></i>&nbsp;Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


<?php $this->document->addScript(base_url('assets/js/jquery.simplePagination.js')); ?>
<?php $this->document->addScript(base_url('assets/js/include/check_login.js')); ?>
<?php $this->document->addScript(base_url('assets/js/include/admin/checklist_list.js')); ?>
<?php $this->document->addScript(base_url('assets/js/page/admin/checklist_list.js')); ?>
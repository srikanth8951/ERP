
<div class="page-content-wrapper">

    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card m-b-20">
                    <div class="card-body">
                        <div class="clearfix mb-4">
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



<?php $this->document->addScript(base_url('assets/js/jquery.simplePagination.js')); ?>
<?php $this->document->addScript(base_url('assets/js/include/employee/check_login.js')); ?>
<?php $this->document->addScript(base_url('assets/js/include/employee/aisd/checklist_list.js')); ?>
<?php $this->document->addScript(base_url('assets/js/page/employee/checklist_list.js')); ?>

    <style>
        .btn-task-close, .btn-actions-box {
            position: absolute;
            top: 30px;
            right: 30px;
            z-index: 9;
        }
    </style>
    <script>
        window.checklist_id = "<?php echo $checklist_id; ?>";
    </script>

    <div class="page-content-wrapper">
        <div class="container-fluid" id="checklisttask--deatils--area">
            <div class="row">
                <div class="col-12">
                    <div class="card m-b-20" data-container="checklistArea">
                        <div class="card-body">
                            <div class="clearfix">
                                <div class="float-right" id="add-button-area">
                                    <a href="<?php echo base_url('employee/nationalHead/checklist'); ?>" class="btn btn-secondary btn-sm waves-effect waves-light ml-2"><i class="mdi mdi-arrow-left-bold mr-2"></i>Back</a>
                                    <!-- <button id="btn-add-checklistview" data-permission="add_checklistview" class="btn btn-indigo btn-sm waves-effect waves-light"><i class="mdi mdi-plus"></i>&nbsp;Task</button> -->
                                </div>
                                
                                <div class="detail-area">
                                    <div class="ph-item">
                                        <div class="ph-col-12">
                                            <div class="ph-row">
                                                <div class="ph-col-4 big mb-2"></div>
                                                <div class="ph-col-8 empty big mb-2"></div>
                                                <div class="ph-col-2"></div>
                                                <div class="ph-col-10 empty"></div>
                                                <div class="ph-col-3"></div>
                                                <div class="ph-col-9 empty"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->

            
            <div data-mview="checklistType"></div>
        </div><!-- container -->

    </div> <!-- Page content Wrapper -->


    <?php $this->document->addScript(base_url('assets/js/include/employee/check_login.js')); ?>

    <?php $this->document->addScript(base_url('assets/js/include/employee/nationalHead/checklist_view_type1.js')); ?>
    <?php $this->document->addScript(base_url('assets/js/include/employee/nationalHead/checklist_view_type2.js')); ?>
    <?php $this->document->addScript(base_url('assets/js/page/employee/checklist_view.js')); ?>

<?php
$view_type = $view_type ?? '';
$product_id = $product_id ?? 0;
?>
<div class="page-content-wrapper">

    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12">
                <form id="productForm" enctype="multipart/form-data">

                    <div class="card m-b-20">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Product Name (Spare Part)<span class="text-danger"> *</span></label>
                                        <input type="text" name="name" class="form-control" placeholder="" />
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="">Category<span class="text-danger"> *</span></label>
                                        <div class="ele-jqValid">
                                            <select name="category_id" data-toggle="select2" class="select2 form-control"></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="">Sub-Category<span class="text-danger"> *</span></label>
                                        <div class="ele-jqValid">
                                            <select name="sub_category_id" data-toggle="select2" class="select2 form-control"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Quantity</label>
                                        <?php if ($view_type == 'add') { ?>
                                            <input type="number" class="form-control" name="quantity" placeholder="" />
                                        <?php } else if ($view_type == 'edit') { ?>
                                            <input type="number" class="form-control" name="quantity" disabled placeholder="" />
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group ele-jqValid">
                                        <label>unit (UOM)</label>
                                        <select id="product-uom" class="form-control select2" data-toggle="select2" name="unit">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Price (per unit)</label>
                                        <input type="number" class="form-control" name="amount" placeholder="" />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Technical Specification</label>
                                        <textarea name="specification" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- this we need in future to add multiple specification -->
                    <!-- <div class="row">
                        <div class="col-md-12">
                            <div class="card m-b-20">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h6>Technical Specification</h6>
                                        <button type="button" id="btn-add-attribute" class="btn btn-secondary">+</button>
                                    </div>
                                    <hr>
                                    <div id="attribute_div"></div>
                                    <div class="mt-4">
                                        <button type="button" id="bt_attribute" class="btn btn-primary">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <div class="form-group">
                        <div>
                            <button type="submit" class="btn btn-indigo m-r-5 waves-effect waves-light">
                                Submit
                            </button>
                            <button type="reset" class="btn btn-secondary waves-effect">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<!-- attribute Modal -->
<div class="modal fade" id="attributeModal">
    <div class="modal-dialog modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="font-18 modal-title">Attribute</h4>
                <button type="button" data-dismiss="modal" class="close"><i class="mdi mdi-close"></i></button>
            </div>
            <div class="modal-body">
                <form id="attributeForm" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group ele-jqValid">
                                <label class="control-label">Attribute Group<span class="text-danger"> *</span></label>
                                <select name="attribute_group_id" data-toggle="select2" class="select2"></select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="control-label">Name<span class="text-danger"> *</span></label>
                                <input type="text" name="attribute_name" class="form-control" />
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
                        <a href="javascript:void(0)" id="btn-reset-attribute-form" class="btn btn-secondary btn-sm" style="display:none;">
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

<script>
    const product_id = "<?php echo $product_id; ?>";
    const form_view_type = "<?php echo $view_type; ?>";
    var formActionUrl;
    window.productForm = '';
    console.log(form_view_type);
    console.log(product_id);
    $(function() {
        productForm = $('#productForm');
    });
</script>

<script src="<?php echo base_url('assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js'); ?>"></script>

<!-- Custom Js -->
<script src="<?php echo base_url('assets/js/include/check_login.js'); ?>"></script>
<script src="<?= base_url('assets/js/include/employee/store/product_form.js') ?>"></script>

<script>
    $(function() {
        $('[data-toggle="select2"]').select2();

        async function loadInitFunctions() {
            if (form_view_type == 'edit') {
                formActionUrl = formApiUrl('employee/store/product/edit', {
                    product_id: product_id
                });
                loadProductDetail();
            } else {
                formActionUrl = formApiUrl('employee/store/product/add');

                // Load autocompletes
                // Category
                await loadAutocompleteCategory({
                    element: $('#productForm [name="category_id"]'),
                    // params: {
                    //     selected: [101]
                    // }
                });
            }
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
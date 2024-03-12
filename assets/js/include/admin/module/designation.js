/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 Path: module
 File: designation js
 */


'use strict'

class ModuleDesignation {
    constructor(options) {
        this.autoloadUrl = options.autoloadUrl;
        this.selectboxElement = options.selectboxElement;
        this.selected = typeof options.selected != 'undefined' ? Object.values(options.selected) : [];
        this.designationForm = '';
        this.designationModal = '';
        this.designationFormValidator = '';
    }

    autocomplete(selectedValues = {}) {
        // console.log(Object.keys(selectedValues).length);
        this.selected = (Object.keys(selectedValues).length > 0) ? Object.values(selectedValues) : [];

        $.ajax({
            url: this.autoloadUrl,
            type: "get",
            dataType: "json",
            headers: {
                Authorization: `Bearer ${wapLogin.getToken()}`,
            }
        }).then((res) => {
            this.selectboxElement.html('').trigger('change');   // Reset selectbox
            
            if (res.status == 'success') {
                // Set default option
                var departmentOptionz = new Option('Select', '', false, false)
                this.selectboxElement.append(departmentOptionz);

                if (res.designations) {
                    var designations = res.designations;

                    designations.forEach((designation, bi) => {
                        if (this.selected.find((value) => {
                            return value == designation.id
                        })) {
                            var designationOption = new Option(designation.name, designation.id, true, true);
                        } else {
                            var designationOption = new Option(designation.name, designation.id, false, false);
                        }
                        
                        this.selectboxElement.append(designationOption);
                    });
                    this.selectboxElement.trigger('change');
                }
            } else if (res.status == 'error') {
                console.log(res.message);
            } else {
                console.log('designation Autocomlete: Something went wrong!');
            }
        }).catch((xhr) => {
            console.log(xhr.responseText + ' ' + xhr.responseText);
        });
    }

    loadPrompt(option) {
        
        if ($('#designationModal').length <= 0) {
          
            let prompt = `<!-- designation Modal -->
            <div class="modal fade" id="designationModal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="font-18 modal-title">Add Designation</h4>
                            <button type="button" data-dismiss="modal" class="close"><i class="mdi mdi-close"></i></button>
                        </div>
                        <div class="modal-body">
                            <form id="designationForm" action="${option.submitAction}" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Name<span class="text-danger">*</span></label>
                                            <input type="text" name="designation_name" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <a href="javascript:void(0)" id="btn-reset-designation-form" class="btn btn-secondary btn-sm" style="display:none;">
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
            </div>`;
            
            $('body').append(prompt);

            this.loadMethods(option);
        }
        
        $('#designationModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });

        
    }

    loadMethods(optionz) {
        this.designationForm = $('#designationForm');
        this.designationModal = $('#designationModal');

        // designation Form
        this.designationFormValidator = this.designationForm.validate({
            onkeyup: (element) => {
                $(element).valid();
            },
            onclick: (element) => {
                $(element).valid();
            },
            rules: {
                designation_name: {
                    required: true,
                    // minlength: 3
                },
                designation_status: {
                    required: true
                }
            },
            messages: {
                designation_name: {
                    required: 'Specify designation name',
                    // minlength: 'Specify atleast 3 characters'
                },
                designation_status: {
                    required: 'Select status'
                }
            },
            errorPlacement: (error, element) => {
                // Add the `invalid-feedback` class to the error element
                error.addClass("invalid-feedback");

                if (element.prop("type") === "checkbox" || element.attr('data-toggle') == 'select2') {
                    // error.insertAfter( element.next( "label" ) );
                    element.parents('.ele-jqValid').append(error);
                } else {
                    error.insertAfter(element);
                }

            },
        });
        this.designationForm.submit((e) => {
            e.preventDefault();
            if (this.designationFormValidator.valid()) {
                this.save(optionz)
            }
        });

        // Modal Form close
        this.designationModal.find('[data-dismiss="modal"]').click(() => {
            this.resetForm();
        });
    }

    resetForm(resetAction = false) {
        if (resetAction == true) {
            this.designationForm.attr('action', '');    // Form Attribute
        }

        this.designationForm[0].reset(); // Form
        this.designationForm.find('[data-toggle="select2"]')
            .prop('disabled', false)
            .val(null)
            .trigger('change'); // Select2
        this.designationFormValidator.resetForm();   // Jquery validation  
    }

    save(optionz) {
        var formData = new FormData(this.designationForm[0]);

        $.ajax({
            url: this.designationForm.attr('action'),
            type: "post",
            dataType: "json",
            headers: {
                Authorization: `Bearer ${wapLogin.getToken()}`,
            },
            data: formData,
            processData: false,
            contentType: false,
        }).then((res) => {
            if (res.status == 'success') {
                this.resetForm();    // Reset form
                this.autocomplete(optionz.selected); // Reload selectbox
                this.designationModal.modal('hide');    // Hide modal
                toastr.success(res.message);
            } else if (res.status == 'error') {
                toastr.error(res.message);
            } else {
                toastr.error(res.message);
            }
        }).catch((jqXHR) => {
            toastr.error(jqXHR);
        });
    }

}


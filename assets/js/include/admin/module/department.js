/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 Path: module
 File: department js
 */


'use strict'

class ModuleDepartment {
    constructor(options) {
        this.autoloadUrl = options.autoloadUrl;
        this.selectboxElement = options.selectboxElement;
        this.departmentForm = '';
        this.departmentModal = '';
        this.departmentFormValidator = '';
    }

    autocomplete(selectedValues = {}) {
        // console.log(Object.keys(selectedValues).length);
        let selectedValuess = (typeof selectedValues == 'object' && Object.keys(selectedValues).length > 0) ? selectedValues : Object.assign({}, selectedValues);

        $.ajax({
            url: this.autoloadUrl,
            type: "get",
            dataType: "json",
            headers: {
                Authorization: `Bearer ${wapLogin.getToken()}`,
            },
        }).then((res) => {
            this.selectboxElement.html('').trigger('change');   // Reset selectbox
            if (res.status == 'success') {
                // Set default option
                let departmentOptionz = new Option('Select', '', false, false)
                this.selectboxElement.append(departmentOptionz);
                
                if (res.departments) {
                    let departments = res.departments;
                    let departmentOption;
                    
                    departments.forEach((department, bi) => {
                        if (Object.values(selectedValuess).find((value) => {
                            return value == department.id
                        })) {
                            departmentOption = new Option(department.name, department.id, true, true)
                        } else {
                            departmentOption = new Option(department.name, department.id, false, false)
                        }
                        this.selectboxElement.append(departmentOption);
                    });
                    this.selectboxElement.trigger('change');
                }
            } else if (res.status == 'error') {
                console.log(res.message);
            } else {
                console.log('department Autocomlete: Something went wrong!');
            }
        }).catch((xhr) => {
            console.log(xhr.responseText + ' ' + xhr.responseText);
        });
    }

    loadPrompt(option) {
        // console.log(option);
        if ($('#departmentModal').length <= 0) {
           
            let prompt = `<!-- department Modal -->
             <div class="modal fade" id="departmentModal">
                 <div class="modal-dialog modal-dialog-centered">
                     <div class="modal-content">
                         <div class="modal-header">
                             <h4 class="font-18 modal-title">Add department</h4>
                             <button type="button" data-dismiss="modal" class="close"><i class="mdi mdi-close"></i></button>
                         </div>
                         <div class="modal-body">
                             <form id="departmentForm" action="${option.submitAction}" enctype="multipart/form-data">
                                 <div class="row">
                                     <div class="col-md-12">
                                         <div class="form-group">
                                             <label class="control-label">Name<span class="text-danger">*</span></label>
                                             <input type="text" name="department_name" class="form-control" />
                                         </div>
                                     </div>
                                 </div>
                                 <div class="form-group">
                                     <a href="javascript:void(0)" id="btn-reset-department-form" class="btn btn-secondary btn-sm" style="display:none;">
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

        $('#departmentModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });


    }

    loadMethods(optionz) {
        this.departmentForm = $('#departmentForm');
        this.departmentModal = $('#departmentModal');

        // department Form
        this.departmentFormValidator = this.departmentForm.validate({
            onkeyup: (element) => {
                $(element).valid();
            },
            onclick: (element) => {
                $(element).valid();
            },
            rules: {
                department_name: {
                    required: true,
                    // minlength: 3
                },
                department_status: {
                    required: true
                }
            },
            messages: {
                department_name: {
                    required: 'Specify department name',
                    // minlength: 'Specify atleast 3 characters'
                },
                department_status: {
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
        this.departmentForm.submit((e) => {
            e.preventDefault();

            if (this.departmentFormValidator.valid()) {
                this.save(optionz);
            }

        });

        // Modal Form close
        this.departmentModal.find('[data-dismiss="modal"]').click(() => {
            this.resetForm();
        });
    }

    resetForm(resetAction = false) {
        if (resetAction == true) {
            this.departmentForm.attr('action', '');    // Form Attribute
        }

        this.departmentForm[0].reset(); // Form
        this.departmentForm.find('[data-toggle="select2"]')
            .prop('disabled', false)
            .val(null)
            .trigger('change'); // Select2
        this.departmentFormValidator.resetForm();   // Jquery validation  
    }

    save(optionz) {
        var formData = new FormData(this.departmentForm[0]);
        $.ajax({
            url: this.departmentForm.attr('action'),
            type: 'post',
            dataType: 'json',
            data: formData,
            headers: {
                Authorization: `Bearer ${wapLogin.getToken()}`,
            },
            processData: false,
            contentType: false,
            cache: false,
        }).then((res) => {
            if (res.status == 'success') {
                this.resetForm();    // Reset form
                this.autocomplete(optionz.selected); // Reload selectbox
                this.departmentModal.modal('hide');    // Hide modal
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


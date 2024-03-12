/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 Path: module
 File: workExpertise js
 */


'use strict'

class ModuleWorkExpertise {
    constructor(options) {
        this.autoloadUrl = options.autoloadUrl;
        this.selectboxElement = options.selectboxElement;
        this.workExpertiseForm = '';
        this.workExpertiseModal = '';
        this.workExpertiseFormValidator = '';
    }

    autocomplete(selectedValues = {}) {
        console.log(Object.keys(selectedValues).length);
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
                let workExpertiseOptionz = new Option('Select', '', false, false)
                this.selectboxElement.append(workExpertiseOptionz);

                if (res.work_expertises) {
                    let work_expertises = res.work_expertises;
                    let workExpertiseOption;

                    work_expertises.forEach((workExpertise, bi) => {
                        if (Object.values(selectedValuess).find((value) => {
                            return value == workExpertise.id
                        })) {
                            workExpertiseOption = new Option(workExpertise.name, workExpertise.id, true, true)
                        } else {
                            workExpertiseOption = new Option(workExpertise.name, workExpertise.id, false, false)
                        }
                        this.selectboxElement.append(workExpertiseOption);
                    });
                    this.selectboxElement.trigger('change');
                }
            } else if (res.status == 'error') {
                console.log(res.message);
            } else {
                console.log('workExpertise Autocomlete: Something went wrong!');
            }
        }).catch((xhr) => {
            console.log(xhr.responseText + ' ' + xhr.responseText);
        });
    }

    loadPrompt(option) {

        if ($('#workExpertiseModal').length <= 0) {

            let prompt = `<!-- Work Expertise Modal -->
              <div class="modal fade" id="workExpertiseModal">
                  <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h4 class="font-18 modal-title">Add work expertise</h4>
                              <button type="button" data-dismiss="modal" class="close"><i class="mdi mdi-close"></i></button>
                          </div>
                          <div class="modal-body">
                              <form id="workExpertiseForm" action="${option.submitAction}" enctype="multipart/form-data">
                                  <div class="row">
                                      <div class="col-md-12">
                                          <div class="form-group">
                                              <label class="control-label">Name<span class="text-danger">*</span></label>
                                              <input type="text" name="work_expertise_name" class="form-control" />
                                          </div>
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <a href="javascript:void(0)" id="btn-reset-workExpertise-form" class="btn btn-secondary btn-sm" style="display:none;">
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

        $('#workExpertiseModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });


    }

    loadMethods(optionz) {
        this.workExpertiseForm = $('#workExpertiseForm');
        this.workExpertiseModal = $('#workExpertiseModal');

        // workExpertise Form
        this.workExpertiseFormValidator = this.workExpertiseForm.validate({
            onkeyup: (element) => {
                $(element).valid();
            },
            onclick: (element) => {
                $(element).valid();
            },
            rules: {
                workExpertise_name: {
                    required: true,
                    // minlength: 3
                },
                // workExpertise_status: {
                //     required: true
                // }
            },
            messages: {
                workExpertise_name: {
                    required: 'Specify work expertise name',
                    // minlength: 'Specify atleast 3 characters'
                },
                // workExpertise_status: {
                //     required: 'Select status'
                // }
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
        this.workExpertiseForm.submit((e) => {
            e.preventDefault();

            if (this.workExpertiseFormValidator.valid()) {
                this.save(optionz);
            }

        });

        // Modal Form close
        this.workExpertiseModal.find('[data-dismiss="modal"]').click(() => {
            this.resetForm();
        });
    }

    resetForm(resetAction = false) {
        if (resetAction == true) {
            this.workExpertiseForm.attr('action', '');    // Form Attribute
        }

        this.workExpertiseForm[0].reset(); // Form
        this.workExpertiseForm.find('[data-toggle="select2"]')
            .prop('disabled', false)
            .val(null)
            .trigger('change'); // Select2
        this.workExpertiseFormValidator.resetForm();   // Jquery validation  
    }

    save(optionz) {
        var formData = new FormData(this.workExpertiseForm[0]);
        $.ajax({
            url: this.workExpertiseForm.attr('action'),
            type: 'post',
            dataType: 'json',
            data: formData,
            processData: false,
            headers: {
                Authorization: `Bearer ${wapLogin.getToken()}`,
            },
            contentType: false,
            cache: false,
        }).then((res) => {
            if (res.status == 'success') {
                this.resetForm();    // Reset form
                this.autocomplete(optionz.selected); // Reload selectbox
                this.workExpertiseModal.modal('hide');    // Hide modal
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


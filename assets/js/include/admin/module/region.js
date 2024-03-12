/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 Path: module
 File: region js
 */


 'use strict'

 class ModuleRegion {
     constructor(options) {
         this.autoloadUrl = options.autoloadUrl;
         this.selectboxElement = options.selectboxElement;
         this.regionForm = '';
         this.regionModal = '';
         this.regionFormValidator = '';
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
                 let regionOptionz = new Option('Select', '', false, false)
                 this.selectboxElement.append(regionOptionz);
                 
                 if (res.regions) {
                     let regions = res.regions;
                     let regionOption;
                     
                     regions.forEach((region, bi) => {
                         if (Object.values(selectedValuess).find((value) => {
                             return value == region.id
                         })) {
                             regionOption = new Option(region.name, region.id, true, true)
                         } else {
                             regionOption = new Option(region.name, region.id, false, false)
                         }
                         this.selectboxElement.append(regionOption);
                     });
                     this.selectboxElement.trigger('change');
                 }
             } else if (res.status == 'error') {
                 console.log(res.message);
             } else {
                 console.log('region Autocomlete: Something went wrong!');
             }
         }).catch((xhr) => {
             console.log(xhr.responseText + ' ' + xhr.responseText);
         });
     }
 
     loadPrompt(option) {
 
         if ($('#regionModal').length <= 0) {
            
             let prompt = `<!-- region Modal -->
              <div class="modal fade" id="regionModal">
                  <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h4 class="font-18 modal-title">Add region</h4>
                              <button type="button" data-dismiss="modal" class="close"><i class="mdi mdi-close"></i></button>
                          </div>
                          <div class="modal-body">
                              <form id="regionForm" action="${option.submitAction}" enctype="multipart/form-data">
                                  <div class="row">
                                      <div class="col-md-12">
                                          <div class="form-group">
                                              <label class="control-label">Name<span class="text-danger">*</span></label>
                                              <input type="text" name="region_name" class="form-control" />
                                          </div>
                                      </div>
                                      <div class="col-md-12">
                                          <div class="form-group">
                                              <label class="control-label">Code<span class="text-danger">*</span></label>
                                              <input type="text" name="region_code" class="form-control" />
                                          </div>
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <a href="javascript:void(0)" id="btn-reset-region-form" class="btn btn-secondary btn-sm" style="display:none;">
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
 
         $('#regionModal').modal({
             backdrop: 'static',
             keyboard: false,
             show: true
         });
 
 
     }
 
     loadMethods(optionz) {
         this.regionForm = $('#regionForm');
         this.regionModal = $('#regionModal');
 
         // region Form
         this.regionFormValidator = this.regionForm.validate({
             onkeyup: (element) => {
                 $(element).valid();
             },
             onclick: (element) => {
                 $(element).valid();
             },
             rules: {
                 region_name: {
                     required: true,
                    //  minlength: 3
                 },
                 region_code: {
                     required: true
                 }
             },
             messages: {
                 region_name: {
                     required: 'Specify region name',
                    //  minlength: 'Specify atleast 3 characters'
                 },
                 region_code: {
                     required: 'Specify code'
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
         this.regionForm.submit((e) => {
             e.preventDefault();
 
             if (this.regionFormValidator.valid()) {
                 this.save(optionz);
             }
 
         });
 
         // Modal Form close
         this.regionModal.find('[data-dismiss="modal"]').click(() => {
             this.resetForm();
         });
     }
 
     resetForm(resetAction = false) {
         if (resetAction == true) {
             this.regionForm.attr('action', '');    // Form Attribute
         }
 
         this.regionForm[0].reset(); // Form
         this.regionForm.find('[data-toggle="select2"]')
             .prop('disabled', false)
             .val(null)
             .trigger('change'); // Select2
         this.regionFormValidator.resetForm();   // Jquery validation  
     }
 
     save(optionz) {
         var formData = new FormData(this.regionForm[0]);
         $.ajax({
             url: this.regionForm.attr('action'),
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
                 this.regionModal.modal('hide');    // Hide modal
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
 
 
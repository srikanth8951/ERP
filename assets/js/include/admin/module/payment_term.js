/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 Path: module
 File: paymentTerm js
 */


 'use strict'

 class ModulePaymentTerm {
     constructor(options) {
         this.autoloadUrl = options.autoloadUrl;
         this.selectboxElement = options.selectboxElement;
         this.paymentTermForm = '';
         this.paymentTermModal = '';
         this.paymentTermFormValidator = '';
     }
 
     autocomplete(selectedValues = {}) {
         console.log(selectedValues);
         let selectedValuess = (typeof selectedValues == 'object' && Object.keys(selectedValues).length > 0) ? selectedValues : Object.assign({}, selectedValues);
         console.log(this.autoloadUrl);
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
                 let paymentTermOptionz = new Option('Select', '', false, false)
                 this.selectboxElement.append(paymentTermOptionz);
 
                 if (res.payment_terms) {
                     let payment_terms = res.payment_terms;
                     let paymentTermOption;
 
                     payment_terms.forEach((paymentTerm, bi) => {
                         if (Object.values(selectedValuess).find((value) => {
                             return value == paymentTerm.id
                         })) {
                             paymentTermOption = new Option(paymentTerm.name, paymentTerm.id, true, true)
                         } else {
                             paymentTermOption = new Option(paymentTerm.name, paymentTerm.id, false, false)
                         }
                         this.selectboxElement.append(paymentTermOption);
                     });
                     this.selectboxElement.trigger('change');
                 }
             } else if (res.status == 'error') {
                 console.log(res.message);
             } else {
                 console.log('paymentTerm Autocomlete: Something went wrong!');
             }
         }).catch((xhr) => {
             console.log(xhr.responseText + ' ' + xhr.responseText);
         });
     }
 
     loadPrompt(option) {
 
         if ($('#paymentTermModal').length <= 0) {
 
             let prompt = `<!-- Payment Terms Modal -->
               <div class="modal fade" id="paymentTermModal">
                   <div class="modal-dialog modal-dialog-centered">
                       <div class="modal-content">
                           <div class="modal-header">
                               <h4 class="font-18 modal-title">Add Payment Terms</h4>
                               <button type="button" data-dismiss="modal" class="close"><i class="mdi mdi-close"></i></button>
                           </div>
                           <div class="modal-body">
                               <form id="paymentTermForm" action="${option.submitAction}" enctype="multipart/form-data">
                                   <div class="row">
                                       <div class="col-md-12">
                                           <div class="form-group">
                                               <label class="control-label">Title<span class="text-danger">*</span></label>
                                               <input type="text" name="payment_term_title" class="form-control" />
                                           </div>
                                       </div>
                                       <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Description</label>
                                                <textarea class="form-control" name="payment_term_description" cols="30" rows="10"></textarea>
                                            </div>
                                       </div>
                                   </div>
                                   <div class="form-group">
                                       <a href="javascript:void(0)" id="btn-reset-paymentTerm-form" class="btn btn-secondary btn-sm" style="display:none;">
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
             console.log(option);
             this.loadMethods(option);
         }
 
         $('#paymentTermModal').modal({
             backdrop: 'static',
             keyboard: false,
             show: true
         });
 
 
     }
 
     loadMethods(optionz) {
         this.paymentTermForm = $('#paymentTermForm');
         this.paymentTermModal = $('#paymentTermModal');
 
         // paymentTerm Form
         this.paymentTermFormValidator = this.paymentTermForm.validate({
             onkeyup: (element) => {
                 $(element).valid();
             },
             onclick: (element) => {
                 $(element).valid();
             },
             rules: {
                 payment_term_title: {
                     required: true,
                 },
                 // paymentTerm_status: {
                 //     required: true
                 // }
             },
             messages: {
                payment_term_title: {
                     required: 'Specify work expertise name',
                 },
                 // paymentTerm_status: {
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
         this.paymentTermForm.submit((e) => {
             e.preventDefault();
 
             if (this.paymentTermFormValidator.valid()) {
                 this.save(optionz);
             }
 
         });
 
         // Modal Form close
         this.paymentTermModal.find('[data-dismiss="modal"]').click(() => {
             this.resetForm();
         });
     }
 
     resetForm(resetAction = false) {
         if (resetAction == true) {
             this.paymentTermForm.attr('action', '');    // Form Attribute
         }
 
         this.paymentTermForm[0].reset(); // Form
         this.paymentTermForm.find('[data-toggle="select2"]')
             .prop('disabled', false)
             .val(null)
             .trigger('change'); // Select2
         this.paymentTermFormValidator.resetForm();   // Jquery validation  
     }
 
     save(optionz) {
         var formData = new FormData(this.paymentTermForm[0]);
         $.ajax({
             url: this.paymentTermForm.attr('action'),
             type: 'post',
             dataType: 'json',
             data: formData,
             processData: false,
             contentType: false,
             headers: {
                Authorization: `Bearer ${wapLogin.getToken()}`,
            },
             cache: false,
         }).then((res) => {
             if (res.status == 'success') {
                 this.resetForm();    // Reset form
                 this.autocomplete(optionz.selected); // Reload selectbox
                 this.paymentTermModal.modal('hide');    // Hide modal
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
 
 
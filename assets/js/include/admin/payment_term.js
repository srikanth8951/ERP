/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: payment_term js
 */

 var apppayment_term = {};

 $(function () {
     const listPayment_term = $('#payment_term--deatils--area');
     const lengthContainer = listPayment_term.find('[data-jy-length="record"]');
     const searchContainer = listPayment_term.find('[data-jy-search="record"]');
     const tableContainer = listPayment_term.find('[data-container="payment_termListArea"]');
     
     const listContainer = tableContainer.find('[data-container="payment_termTlistArea"]');
     const listPagination = tableContainer.find('[data-pagination="payment_termTlistArea"]');
     const payment_termForm = $('#payment_termForm');
     var payment_termFormValidator;
     const payment_termModal = $('#payment_termModal');
     const btnResetpayment_termForm = $('#btn-reset-payment_term-form');
     listPagination.find('.list-pagination').html('');
     listPagination.find('.list-pagination-label').html('');
 
     // Add payment_term
     $('#btn-add-payment_term').click(function (e) {
         e.preventDefault();
 
         btnResetpayment_termForm.show();    // Show reset button
         payment_termForm.attr('action', formApiUrl('admin/payment_term/add'));
         payment_termModal.find('.modal-header .modal-title').html('Add payment_term');
         payment_termModal.modal({
             backdrop: 'static',
             keyboard: false,
             show: true
         });
     });
 
     window.resetpayment_termForm = function (resetAction = true) {
 
         if (resetAction == true) {
             payment_termForm.attr('action', '');    // Form Attribute
         }
 
         payment_termForm[0].reset(); // Form
         payment_termForm.find('[data-toggle="select2"]')
             .prop('disabled', false)
             .val(null)
             .trigger('change'); // Select2
         payment_termFormValidator.resetForm();   // Jquery validation 
     }
 
     // Form reset button
     btnResetpayment_termForm.click(function (e) {
         e.preventDefault();
         resetpayment_termForm(false);
     });
 
     // Modal Form close
     payment_termModal.find('[data-dismiss="modal"]').click(function () {
         resetpayment_termForm();
     });
 
     window.loadEmptyDetail = function () {
         listContainer.html('');
         listPagination.find('.list-pagination').html('');
         listPagination.find('.list-pagination-label').html('');
         listContainer.append('<tr>' +
             '<td colspan="6" class="text-center">No Details Found!</td>' +
             '</tr>');
     }
 
     window.loadDetails = function (href) {
         let loadSwal;
         let filterData = {};
         if (lengthContainer.val() != '') {
             filterData['length'] = lengthContainer.val();
         }
         if (searchContainer.find('input[name="search"]').val() != '') {
            filterData['search'] = searchContainer.find('input[name="search"]').val();
        }
 
         $.ajax({
             url: href,
             type: 'get',
             dataType: 'json',
             headers: {
                 Authorization: `Bearer ${wapLogin.getToken()}`
             },
             data: filterData,
             beforeSend: function () {
                 loadSwal = Swal.fire({
                     html: '<div class="my-4 text-center d-inline-block">' + loaderContent + '</div>',
                     customClass: {
                         popup: 'col-6 col-sm-5 col-md-3 col-lg-2'
                     },
                     allowOutsideClick: false,
                     allowEscapeKey: false,
                     showConfirmButton: false
                 });
             },
             success: function (res) {
                 if (res.status == 'success') {
                     if (res.payment_terms) {
                         listContainer.html('');
                         var details = res.payment_terms;
                         var pagination = res.pagination;
                         if (details.length && pagination.total > 0) {
                             let status_badge_class = '';
                             $.each(details, function (listIn, listVal) {
                                 if (listVal.status == 1) {
                                     status_badge_class = 'badge-success';
                                 } else {
                                     status_badge_class = 'badge-danger';
                                 }
 
                                 listContainer.append('<tr>' +
                                     '<td>' + (listIn+1) + '</td>' +
                                     '<td>' + listVal.title + '</td>' +
                                     '<td>' +
                                     '<a href="javascript:void(0)" data-payment_term="' + listVal.payment_term_id + '" class="text-white btn btn-sm btn-dark waves-effect waves-light btn-edit-payment_term mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit payment_term"><i class="mdi mdi-pencil"></i></a>' +
 
                                     '<a href="javascript:void(0)" data-payment_term="' + listVal.payment_term_id + '" class="text-white btn btn-sm btn-danger waves-effect waves-light btn-delete-payment_term mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="mdi mdi-delete"></i></a>' +
                                     '</td>' +
                                     '</tr>');
                             });
 
                             listContainer.find('[data-toggle="tooltip"]').tooltip();    // Load tooltip
                             listPagination.find('.list-pagination-label').html(`Showing ${pagination.start} to ${(parseInt(pagination.start) -1) + pagination.records} of ${pagination.total}`);
                             listPagination.find('.list-pagination').pagination({
                                 items: parseInt(pagination.total),
                                 itemsOnPage: parseInt(pagination.length),
                                 currentPage: Math.ceil(parseInt(pagination.start) / parseInt(pagination.length)),
                                 displayedPages: 3,
                                 navStyle: 'pagination',
                                 listStyle: 'page-item',
                                 linkStyle: 'page-link',
                                 onPageClick: function (pageNumber, event) {
                                     var page_link = formApiUrl('admin/payment_term/list', { start: ((parseInt(pagination.length) * (pageNumber - 1)) + 1)});
                                     loadDetails(page_link);
                                 }
                             });
 
                         } else {
                             loadEmptyDetail();
                         }
                     } else {
                         loadEmptyDetail();
                     }
                 } else if (res.status == 'error') {
                    // toastr.error(res.message);
                    loadEmptyDetail();
                 } else {
                    toastr.error('No response status', 'Error');
                    loadEmptyDetail();
                 }
             },
             error: function (xhr, textStatus, errorThrown) {
                 toastr.error(`${textStatus} <br />${errorThrown}` , 'Error');
                 loadEmptyDetail();
             },
             complete: function () {
                 loadSwal.close();
             }
         });
     }
 
     lengthContainer.change(function () {
         loadDetails(formApiUrl('admin/payment_term/list'));  // Load payment_term details
     });
 
     searchContainer.submit(function (e) {
        e.preventDefault();
        loadDetails(formApiUrl('admin/payment_term/list'));  // Load payment_term details
    });
 
     // payment_term Form
     payment_termFormValidator = payment_termForm.validate({
         onkeyup: function (element) {
             $(element).valid();
         },
         onclick: function (element) {
             $(element).valid();
         },
         rules: {
             payment_term_title: {
                 required: true,
                //  minlength: 3
             }
         },
         messages: {
             payment_term_title: {
                 required: 'Specify payment_term name',
                //  minlength: 'Specify atleast 3 characters'
             }
         },
         errorPlacement: function (error, element) {
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
 
     function savepayment_term() {
         let loadSwal;
         var formData = new FormData(payment_termForm[0]);
 
         $.ajax({
             url: payment_termForm.attr('action'),
             type: 'post',
             data: formData,
             dataType: 'json',
             headers: {
                 Authorization: `Bearer ${wapLogin.getToken()}`
             },
             processData: false,
             contentType: false,
             cache: false,
             beforeSend: function () {
                 loadSwal = Swal.fire({
                     html: '<div class="my-4 text-center d-inline-block">' + loaderContent + '</div>',
                     customClass: {
                         popup: 'col-6 col-sm-5 col-md-3 col-lg-2'
                     },
                     allowOutsideClick: false,
                     allowEscapeKey: false,
                     showConfirmButton: false
                 });
             },
             complete: function () {
                 loadSwal.close();
             }
         }).done(function (res) {
             if (res.status == 'success') {
                 loadDetails(formApiUrl('admin/payment_term/list'));  // Load payment_term details
                 toastr.success(res.message);
                 payment_termModal.modal('hide');    // Hide modal
                 resetpayment_termForm();    // Reset form
             } else if (res.status == 'error') {
                 toastr.error(res.message);
             } else {
                 toastr.error('No response status!', 'Error');
             }
         }).fail(function (jqXHR, textStatus, errorThrown) {
             toastr.error(`${textStatus} <br />${errorThrown}` , 'Error');
         });
     }
 
     payment_termForm.submit(function (e) {
         e.preventDefault();
         if (payment_termFormValidator.valid()) {
             savepayment_term();
         }
 
     });
 
     // Edit payment_term
     $(listContainer).on('click', '.btn-edit-payment_term', function (e) {
         e.preventDefault();
 
         btnResetpayment_termForm.hide();    // Hide button
 
         var payment_term_id = $(this).attr('data-payment_term');
         $.ajax({
             url: formApiUrl('admin/payment_term/detail', { payment_term_id: payment_term_id }),
             type: 'get',
             dataType: 'json',
             headers: {
                 Authorization: `Bearer ${wapLogin.getToken()}`
             },
             beforeSend: function () {
                 loadSwal = Swal.fire({
                     html: '<div class="my-4 text-center d-inline-block">' + loaderContent + '</div>',
                     customClass: {
                         popup: 'col-6 col-sm-5 col-md-3 col-lg-2'
                     },
                     allowOutsideClick: false,
                     allowEscapeKey: false,
                     showConfirmButton: false
                 });
             },
             complete: function () {
                 loadSwal.close();
             }
         }).done(function (res) {
             if (res.status == 'success') {
                 if (res.payment_term) {
                     var payment_term = res.payment_term;
 
                     // Set payment_term Info
                     payment_termForm.attr('action', formApiUrl('admin/payment_term/edit', { payment_term_id: payment_term.payment_term_id }));
                     payment_termForm.find('[name="payment_term_title"]').val(payment_term.title);
                     payment_termForm.find('[name="payment_term_description"]').val(payment_term.description);
                     payment_termModal.find('.modal-header .modal-title').html('Edit payment_term');
 
                     // Show payment_term modal
                     payment_termModal.modal({
                         backdrop: 'static',
                         keyboard: false,
                         show: true
                     });
                 } else {
                     toastr.error('No payment_term available');
                 }
             } else if (res.status == 'error') {
                 toastr.error(res.message);
             } else {
                 toastr.error('No response status!', 'Error');
             }
         }).fail(function (jqXHR, textStatus, errorThrown) {
             toastr.error(`${textStatus} <br />${errorThrown}` , 'Error');
         });
         
     });
 
     // Delete payment_term
     $(listContainer).on('click', '.btn-delete-payment_term', function (e) {
         e.preventDefault();
         var payment_term_id = $(this).attr('data-payment_term');
         Swal.fire({
             icon: 'question',
             title: 'Are you sure to delete payment_term',
             allowOutsideClick: false,
             allowEscapeKey: false,
             showConfirmButton: true,
             confirmButtonText: 'Yes',
             showCancelButton: true,
             cancelButtonText: 'No',
             focusCancel: true
         }).then((result) => {
             if (result.isConfirmed) {
 
                 $.ajax({
                     url: formApiUrl('admin/payment_term/delete', { payment_term_id: payment_term_id }),
                     type: 'post',
                     dataType: 'json',
                     headers: {
                         Authorization: `Bearer ${wapLogin.getToken()}`
                     },
                     beforeSend: function () {
                         loadSwal = Swal.fire({
                             html: '<div class="my-4 text-center d-inline-block">' + loaderContent + '</div>',
                             customClass: {
                                 popup: 'col-6 col-sm-5 col-md-3 col-lg-2'
                             },
                             allowOutsideClick: false,
                             allowEscapeKey: false,
                             showConfirmButton: false
                         });
                     },
                     complete: function () {
                         loadSwal.close();
                     }
                 }).done(function (res) {
                     if (res.status == 'success') {
                         loadDetails(formApiUrl('admin/payment_term/list'));  // Load payment_term details
                         toastr.success(res.message);
                         payment_termModal.modal('hide');    // Reset form
                         resetpayment_termForm();
                     } else if (res.status == 'error') {
                         toastr.error(res.message);
                     } else {
                         toastr.error('No response status!', 'Error');
                     }
                 }).fail(function (jqXHR, textStatus, errorThrown) {
                     toastr.error(`${textStatus} <br />${errorThrown}` , 'Error');
                 });
             }
         });
     });
 });
 
 
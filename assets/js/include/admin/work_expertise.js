/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: work_expertise js
 */

 var appwork_expertise = {};

 $(function () {
     const listRegion = $('#work_expertise--deatils--area');
     const lengthContainer = listRegion.find('[data-jy-length="record"]');
     const searchContainer = listRegion.find('[data-jy-search="record"]');
     const tableContainer = listRegion.find('[data-container="work_expertiseListArea"]');
 
     const listContainer = tableContainer.find('[data-container="work_expertiseTlistArea"]');
     const listPagination = tableContainer.find('[data-pagination="work_expertiseTlistArea"]');
     const work_expertiseForm = $('#work_expertiseForm');
     var work_expertiseFormValidator;
     const work_expertiseModal = $('#work_expertiseModal');
     const btnResetwork_expertiseForm = $('#btn-reset-work_expertise-form');
     listPagination.find('.list-pagination').html('');
     listPagination.find('.list-pagination-label').html('');
 
     // Add work_expertise
     $('#btn-add-work_expertise').click(function (e) {
         e.preventDefault();
 
         btnResetwork_expertiseForm.show();    // Show reset button
         work_expertiseForm.attr('action', formApiUrl('admin/work_expertise/add'));
         work_expertiseModal.find('.modal-header .modal-title').html('Add work expertise');
         work_expertiseModal.modal({
             backdrop: 'static',
             keyboard: false,
             show: true
         });
     });
 
     window.resetwork_expertiseForm = function (resetAction = true) {
 
         if (resetAction == true) {
             work_expertiseForm.attr('action', '');    // Form Attribute
         }
 
         work_expertiseForm[0].reset(); // Form
         work_expertiseForm.find('[data-toggle="select2"]')
             .prop('disabled', false)
             .val(null)
             .trigger('change'); // Select2
         work_expertiseFormValidator.resetForm();   // Jquery validation 
     }
 
     // Form reset button
     btnResetwork_expertiseForm.click(function (e) {
         e.preventDefault();
         resetwork_expertiseForm(false);
     });
 
     // Modal Form close
     work_expertiseModal.find('[data-dismiss="modal"]').click(function () {
         resetwork_expertiseForm();
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
                     if (res.work_expertises) {
                         listContainer.html('');
                         var details = res.work_expertises;
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
                                     '<td>' + (listIn + 1) + '</td>' +
                                     '<td>' + listVal.name + '</td>' +
                                     '<td>' +
                                     '<a href="javascript:void(0)" data-work_expertise="' + listVal.work_expertise_id + '" class="text-white btn btn-sm btn-dark waves-effect waves-light btn-edit-work_expertise mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit work_expertise"><i class="mdi mdi-pencil"></i></a>' +
 
                                     '<a href="javascript:void(0)" data-work_expertise="' + listVal.work_expertise_id + '" class="text-white btn btn-sm btn-danger waves-effect waves-light btn-delete-work_expertise mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="mdi mdi-delete"></i></a>' +
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
                                     var page_link = formApiUrl('admin/work_expertise/list', { start: ((parseInt(pagination.length) * (pageNumber - 1)) + 1)});
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
                    //  toastr.error(res.message);
                     loadEmptyDetail();
                 } else {
                     toastr.error('No response status', 'Error');
                     loadEmptyDetail();
                 }
             },
             error: function (xhr, textStatus, errorThrown) {
                 toastr.error(`${textStatus} <br />${errorThrown}`, 'Error');
                 loadEmptyDetail();
             },
             complete: function () {
                 loadSwal.close();
             }
         });
     }
 
     lengthContainer.change(function () {
         loadDetails(formApiUrl('admin/work_expertise/list'));  // Load work_expertise details
     });
 
     searchContainer.submit(function (e) {
         e.preventDefault();
         loadDetails(formApiUrl('admin/work_expertise/list'));  // Load work_expertise details
     });
 
     // work_expertise Form
     work_expertiseFormValidator = work_expertiseForm.validate({
         onkeyup: function (element) {
             $(element).valid();
         },
         onclick: function (element) {
             $(element).valid();
         },
         rules: {
             work_expertise_name: {
                 required: true,
                //  minlength: 3
             },
         },
         messages: {
             work_expertise_name: {
                 required: 'Specify work_expertise name',
                //  minlength: 'Specify atleast 3 characters'
             },
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
 
     function savework_expertise() {
         let loadSwal;
         var formData = new FormData(work_expertiseForm[0]);
 
         $.ajax({
             url: work_expertiseForm.attr('action'),
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
                 loadDetails(formApiUrl('admin/work_expertise/list'));  // Load work_expertise details
                 toastr.success(res.message);
                 work_expertiseModal.modal('hide');    // Hide modal
                 resetwork_expertiseForm();    // Reset form
             } else if (res.status == 'error') {
                 toastr.error(res.message);
             } else {
                 toastr.error('No response status!', 'Error');
             }
         }).fail(function (jqXHR, textStatus, errorThrown) {
             toastr.error(`${textStatus} <br />${errorThrown}`, 'Error');
         });
     }
 
     work_expertiseForm.submit(function (e) {
         e.preventDefault();
         if (work_expertiseFormValidator.valid()) {
             savework_expertise();
         }
 
     });
 
     // Edit work_expertise
     $(listContainer).on('click', '.btn-edit-work_expertise', function (e) {
         e.preventDefault();
 
         btnResetwork_expertiseForm.hide();    // Hide button
 
         var work_expertise_id = $(this).attr('data-work_expertise');
         $.ajax({
             url: formApiUrl('admin/work_expertise/detail', { work_expertise_id: work_expertise_id }),
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
                 if (res.work_expertise) {
                     var work_expertise = res.work_expertise;
 
                     // Set work_expertise Info
                     work_expertiseForm.attr('action', formApiUrl('admin/work_expertise/edit', { work_expertise_id: work_expertise.work_expertise_id }));
                     work_expertiseForm.find('[name="work_expertise_name"]').val(work_expertise.name);
                     work_expertiseForm.find('[name="work_expertise_code"]').val(work_expertise.code);
                     work_expertiseModal.find('.modal-header .modal-title').html('Edit work expertise');
 
                     // Show work_expertise modal
                     work_expertiseModal.modal({
                         backdrop: 'static',
                         keyboard: false,
                         show: true
                     });
                 } else {
                     toastr.error('No work_expertise available');
                 }
             } else if (res.status == 'error') {
                 toastr.error(res.message);
             } else {
                 toastr.error('No response status!', 'Error');
             }
         }).fail(function (jqXHR, textStatus, errorThrown) {
             toastr.error(`${textStatus} <br />${errorThrown}`, 'Error');
         });
 
     });
 
     // Delete work_expertise
     $(listContainer).on('click', '.btn-delete-work_expertise', function (e) {
         e.preventDefault();
         var work_expertise_id = $(this).attr('data-work_expertise');
         Swal.fire({
             icon: 'question',
             title: 'Are you sure to delete work_expertise',
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
                     url: formApiUrl('admin/work_expertise/delete', { work_expertise_id: work_expertise_id }),
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
                         loadDetails(formApiUrl('admin/work_expertise/list'));  // Load work_expertise details
                         toastr.success(res.message);
                         work_expertiseModal.modal('hide');    // Reset form
                         resetwork_expertiseForm();
                     } else if (res.status == 'error') {
                         toastr.error(res.message);
                     } else {
                         toastr.error('No response status!', 'Error');
                     }
                 }).fail(function (jqXHR, textStatus, errorThrown) {
                     toastr.error(`${textStatus} <br />${errorThrown}`, 'Error');
                 });
             }
         });
     });
 });
 
 
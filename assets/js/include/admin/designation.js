/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: designation js
 */

 var appdesignation = {};

 $(function () {
     const listDesignation = $('#designation--deatils--area');
     const lengthContainer = listDesignation.find('[data-jy-length="record"]');
     const searchContainer = listDesignation.find('[data-jy-search="record"]');
     const tableContainer = listDesignation.find('[data-container="designationListArea"]');
     
     const listContainer = tableContainer.find('[data-container="designationTlistArea"]');
     const listPagination = tableContainer.find('[data-pagination="designationTlistArea"]');
     const designationForm = $('#designationForm');
     var designationFormValidator;
     const designationModal = $('#designationModal');
     const btnResetdesignationForm = $('#btn-reset-designation-form');
     listPagination.find('.list-pagination').html('');
     listPagination.find('.list-pagination-label').html('');
 
     // Add designation
     $('#btn-add-designation').click(function (e) {
         e.preventDefault();
 
         btnResetdesignationForm.show();    // Show reset button
         designationForm.attr('action', formApiUrl('admin/designation/add'));
         designationModal.find('.modal-header .modal-title').html('Add designation');
         designationModal.modal({
             backdrop: 'static',
             keyboard: false,
             show: true
         });
     });
 
     window.resetdesignationForm = function (resetAction = true) {
 
         if (resetAction == true) {
             designationForm.attr('action', '');    // Form Attribute
         }
 
         designationForm[0].reset(); // Form
         designationForm.find('[data-toggle="select2"]')
             .prop('disabled', false)
             .val(null)
             .trigger('change'); // Select2
         designationFormValidator.resetForm();   // Jquery validation 
     }
 
     // Form reset button
     btnResetdesignationForm.click(function (e) {
         e.preventDefault();
         resetdesignationForm(false);
     });
 
     // Modal Form close
     designationModal.find('[data-dismiss="modal"]').click(function () {
         resetdesignationForm();
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
                     if (res.designations) {
                         listContainer.html('');
                         var details = res.designations;
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
                                     '<td>' + listVal.name + '</td>' +
                                     '<td>' +
                                     '<a href="javascript:void(0)" data-designation="' + listVal.designation_id + '" class="text-white btn btn-sm btn-dark waves-effect waves-light btn-edit-designation mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit designation"><i class="mdi mdi-pencil"></i></a>' +
 
                                     '<a href="javascript:void(0)" data-designation="' + listVal.designation_id + '" class="text-white btn btn-sm btn-danger waves-effect waves-light btn-delete-designation mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="mdi mdi-delete"></i></a>' +
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
                                     var page_link = formApiUrl('admin/designation/list', { start: ((parseInt(pagination.length) * (pageNumber - 1)) + 1)});
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
         loadDetails(formApiUrl('admin/designation/list'));  // Load designation details
     });
 
     searchContainer.submit(function (e) {
        e.preventDefault();
        loadDetails(formApiUrl('admin/designation/list'));  // Load designation details
    });
 
     // designation Form
     designationFormValidator = designationForm.validate({
         onkeyup: function (element) {
             $(element).valid();
         },
         onclick: function (element) {
             $(element).valid();
         },
         rules: {
             designation_name: {
                 required: true,
                //  minlength: 3
             }
         },
         messages: {
             designation_name: {
                 required: 'Specify designation name',
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
 
     function savedesignation() {
         let loadSwal;
         var formData = new FormData(designationForm[0]);
 
         $.ajax({
             url: designationForm.attr('action'),
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
                 loadDetails(formApiUrl('admin/designation/list'));  // Load designation details
                 toastr.success(res.message);
                 designationModal.modal('hide');    // Hide modal
                 resetdesignationForm();    // Reset form
             } else if (res.status == 'error') {
                 toastr.error(res.message);
             } else {
                 toastr.error('No response status!', 'Error');
             }
         }).fail(function (jqXHR, textStatus, errorThrown) {
             toastr.error(`${textStatus} <br />${errorThrown}` , 'Error');
         });
     }
 
     designationForm.submit(function (e) {
         e.preventDefault();
         if (designationFormValidator.valid()) {
             savedesignation();
         }
 
     });
 
     // Edit designation
     $(listContainer).on('click', '.btn-edit-designation', function (e) {
         e.preventDefault();
 
         btnResetdesignationForm.hide();    // Hide button
 
         var designation_id = $(this).attr('data-designation');
         $.ajax({
             url: formApiUrl('admin/designation/detail', { designation_id: designation_id }),
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
                 if (res.designation) {
                     var designation = res.designation;
 
                     // Set designation Info
                     designationForm.attr('action', formApiUrl('admin/designation/edit', { designation_id: designation.designation_id }));
                     designationForm.find('[name="designation_name"]').val(designation.name);
                     designationModal.find('.modal-header .modal-title').html('Edit designation');
 
                     // Show designation modal
                     designationModal.modal({
                         backdrop: 'static',
                         keyboard: false,
                         show: true
                     });
                 } else {
                     toastr.error('No designation available');
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
 
     // Delete designation
     $(listContainer).on('click', '.btn-delete-designation', function (e) {
         e.preventDefault();
         var designation_id = $(this).attr('data-designation');
         Swal.fire({
             icon: 'question',
             title: 'Are you sure to delete designation',
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
                     url: formApiUrl('admin/designation/delete', { designation_id: designation_id }),
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
                         loadDetails(formApiUrl('admin/designation/list'));  // Load designation details
                         toastr.success(res.message);
                         designationModal.modal('hide');    // Reset form
                         resetdesignationForm();
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
 
 
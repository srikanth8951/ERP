/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: attribute_group js
 */

 var appattribute_group = {};

 $(function () {
     const listAttributeGroup = $('#attribute_group--deatils--area');
     const lengthContainer = listAttributeGroup.find('[data-jy-length="record"]');
     const searchContainer = listAttributeGroup.find('[data-jy-search="record"]');
     const tableContainer = listAttributeGroup.find('[data-container="attribute_groupListArea"]');
 
     const listContainer = tableContainer.find('[data-container="attribute_groupTlistArea"]');
     const listPagination = tableContainer.find('[data-pagination="attribute_groupTlistArea"]');
     const attribute_groupForm = $('#attribute_groupForm');
     var attribute_groupFormValidator;
     const attribute_groupModal = $('#attribute_groupModal');
     const btnResetattribute_groupForm = $('#btn-reset-attribute_group-form');
     listPagination.find('.list-pagination').html('');
     listPagination.find('.list-pagination-label').html('');
 
     // Add attribute_group
     $('#btn-add-attribute_group').click(function (e) {
         e.preventDefault();
 
         btnResetattribute_groupForm.show();    // Show reset button
         attribute_groupForm.attr('action', formApiUrl('admin/store/attribute_group/add'));
         attribute_groupModal.find('.modal-header .modal-title').html('Add attribute_group');
         attribute_groupModal.modal({
             backdrop: 'static',
             keyboard: false,
             show: true
         });
     });
 
     window.resetattribute_groupForm = function (resetAction = true) {
 
         if (resetAction == true) {
             attribute_groupForm.attr('action', '');    // Form Attribute
         }
 
         attribute_groupForm[0].reset(); // Form
         attribute_groupForm.find('[data-toggle="select2"]')
             .prop('disabled', false)
             .val(null)
             .trigger('change'); // Select2
         attribute_groupFormValidator.resetForm();   // Jquery validation 
     }
 
     // Form reset button
     btnResetattribute_groupForm.click(function (e) {
         e.preventDefault();
         resetattribute_groupForm(false);
     });
 
     // Modal Form close
     attribute_groupModal.find('[data-dismiss="modal"]').click(function () {
         resetattribute_groupForm();
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
                     if (res.attribute_groups) {
                         listContainer.html('');
                         var details = res.attribute_groups;
                         var pagination = res.pagination;
                         if (details.length && pagination.total > 0) {
                             let status_badge_class = '';
                             let status_lable = '';
                             $.each(details, function (listIn, listVal) {
                                if (listVal.status == 1) {
                                    status_badge_class = 'text-success';
                                    status_lable = 'Active';
                                } else {
                                    status_badge_class = 'text-danger';
                                    status_lable = 'Inactive';
                                }
 
                                 listContainer.append('<tr>' +
                                     '<td>' + (listIn + 1) + '</td>' +
                                     '<td>' + listVal.name + '</td>' +
                                     '<td><span class="'+ status_badge_class +'">' + status_lable + '</span></td>' +
                                     '<td>' +
                                     '<a href="javascript:void(0)" data-attribute_group="' + listVal.attribute_group_id + '" class="text-white btn btn-sm btn-dark waves-effect waves-light btn-edit-attribute_group mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit attribute_group"><i class="mdi mdi-pencil"></i></a>' +
 
                                     '<a href="javascript:void(0)" data-attribute_group="' + listVal.attribute_group_id + '" class="text-white btn btn-sm btn-danger waves-effect waves-light btn-delete-attribute_group mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="mdi mdi-delete"></i></a>' +
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
                                     var page_link = formApiUrl('admin/store/attribute_group/list', { start: ((parseInt(pagination.length) * (pageNumber - 1)) + 1)});
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
                 toastr.error(`${textStatus} <br />${errorThrown}`, 'Error');
                 loadEmptyDetail();
             },
             complete: function () {
                 loadSwal.close();
             }
         });
     }
 
     lengthContainer.change(function () {
         loadDetails(formApiUrl('admin/store/attribute_group/list'));  // Load attribute_group details
     });
 
     searchContainer.submit(function (e) {
         e.preventDefault();
         loadDetails(formApiUrl('admin/store/attribute_group/list'));  // Load attribute_group details
     });
 
     // attribute_group Form
     attribute_groupFormValidator = attribute_groupForm.validate({
         onkeyup: function (element) {
             $(element).valid();
         },
         onclick: function (element) {
             $(element).valid();
         },
         rules: {
             attribute_group_name: {
                 required: true,
                 // minlength: 3
             },
             status: {
                 required: true,
             }
         },
         messages: {
             attribute_group_name: {
                 required: 'Specify attribute_group name',
                 // minlength: 'Specify atleast 3 characters'
             },
             status: {
                 required: 'Select Status'
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
 
     function saveattribute_group() {
         let loadSwal;
         var formData = new FormData(attribute_groupForm[0]);
 
         $.ajax({
             url: attribute_groupForm.attr('action'),
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
                 loadDetails(formApiUrl('admin/store/attribute_group/list'));  // Load attribute_group details
                 toastr.success(res.message);
                 attribute_groupModal.modal('hide');    // Hide modal
                 resetattribute_groupForm();    // Reset form
             } else if (res.status == 'error') {
                 toastr.error(res.message);
             } else {
                 toastr.error('No response status!', 'Error');
             }
         }).fail(function (jqXHR, textStatus, errorThrown) {
             toastr.error(`${textStatus} <br />${errorThrown}`, 'Error');
         });
     }
 
     attribute_groupForm.submit(function (e) {
         e.preventDefault();
         if (attribute_groupFormValidator.valid()) {
             saveattribute_group();
         }
 
     });
 
     // Edit attribute_group
     $(listContainer).on('click', '.btn-edit-attribute_group', function (e) {
         e.preventDefault();
 
         btnResetattribute_groupForm.hide();    // Hide button
 
         var attribute_group_id = $(this).attr('data-attribute_group');
         $.ajax({
             url: formApiUrl('admin/store/attribute_group/detail', { attribute_group_id: attribute_group_id }),
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
                 if (res.attribute_group) {
                     var attribute_group = res.attribute_group;
 
                     // Set attribute_group Info
                     attribute_groupForm.attr('action', formApiUrl('admin/store/attribute_group/edit', { attribute_group_id: attribute_group.attribute_group_id }));
                     attribute_groupForm.find('[name="attribute_group_name"]').val(attribute_group.name);
                     attribute_groupForm.find('[name="status"]').val(attribute_group.status).trigger('change');
                     attribute_groupModal.find('.modal-header .modal-title').html('Edit attribute_group');
 
                     // Show attribute_group modal
                     attribute_groupModal.modal({
                         backdrop: 'static',
                         keyboard: false,
                         show: true
                     });
                 } else {
                     toastr.error('No attribute_group available');
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
 
     // Delete attribute_group
     $(listContainer).on('click', '.btn-delete-attribute_group', function (e) {
         e.preventDefault();
         var attribute_group_id = $(this).attr('data-attribute_group');
         Swal.fire({
             icon: 'question',
             title: 'Are you sure to delete attribute_group',
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
                     url: formApiUrl('admin/store/attribute_group/delete', { attribute_group_id: attribute_group_id }),
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
                         loadDetails(formApiUrl('admin/store/attribute_group/list'));  // Load attribute_group details
                         toastr.success(res.message);
                         attribute_groupModal.modal('hide');    // Reset form
                         resetattribute_groupForm();
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
 
 
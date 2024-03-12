/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: group js
 */

 var appgroup = {};

 $(function () {
     const listGroup = $('#group--deatils--area');
     const lengthContainer = listGroup.find('[data-jy-length="record"]');
     const searchContainer = listGroup.find('[data-jy-search="record"]');
     const tableContainer = listGroup.find('[data-container="groupListArea"]');
 
     const listContainer = tableContainer.find('[data-container="groupTlistArea"]');
     const listPagination = tableContainer.find('[data-pagination="groupTlistArea"]');
     const groupForm = $('#groupForm');
     var groupFormValidator;
     const groupModal = $('#groupModal');
     const btnResetgroupForm = $('#btn-reset-group-form');
     listPagination.find('.list-pagination').html('');
     listPagination.find('.list-pagination-label').html('');
 
     // Add group
     $('#btn-add-group').click(function (e) {
         e.preventDefault();
 
         btnResetgroupForm.show();    // Show reset button
         groupForm.attr('action', formApiUrl('admin/asset/group/add'));
         groupModal.find('.modal-header .modal-title').html('Add group');
         groupModal.modal({
             backdrop: 'static',
             keyboard: false,
             show: true
         });
     });
 
     window.resetgroupForm = function (resetAction = true) {
 
         if (resetAction == true) {
             groupForm.attr('action', '');    // Form Attribute
         }
 
         groupForm[0].reset(); // Form
         groupForm.find('[data-toggle="select2"]')
             .prop('disabled', false)
             .val(null)
             .trigger('change'); // Select2
         groupFormValidator.resetForm();   // Jquery validation 
     }
 
     // Form reset button
     btnResetgroupForm.click(function (e) {
         e.preventDefault();
         resetgroupForm(false);
     });
 
     // Modal Form close
     groupModal.find('[data-dismiss="modal"]').click(function () {
         resetgroupForm();
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
                     if (res.groups) {
                         listContainer.html('');
                         var details = res.groups;
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
                                     '<a href="javascript:void(0)" data-group="' + listVal.asset_group_id + '" class="text-white btn btn-sm btn-dark waves-effect waves-light btn-edit-group mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit group"><i class="mdi mdi-pencil"></i></a>' +
 
                                     '<a href="javascript:void(0)" data-group="' + listVal.asset_group_id + '" class="text-white btn btn-sm btn-danger waves-effect waves-light btn-delete-group mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="mdi mdi-delete"></i></a>' +
                                     '</td>' +
                                     '</tr>');
                             });
 
                             listContainer.find('[data-toggle="tooltip"]').tooltip();    // Load tooltip
                             listPagination.find('.list-pagination-label').html(`Showing ${pagination.start} to ${pagination.records} of ${pagination.total}`);
                             listPagination.find('.list-pagination').pagination({
                                 items: parseInt(pagination.total),
                                 itemsOnPage: parseInt(pagination.length),
                                 currentPage: Math.ceil(parseInt(pagination.start) / parseInt(pagination.length)),
                                 displayedPages: 3,
                                 navStyle: 'pagination',
                                 listStyle: 'page-item',
                                 linkStyle: 'page-link',
                                 onPageClick: function (pageNumber, event) {
                                     var page_link = formApiUrl('admin/asset/group/list', { start: ((parseInt(pagination.length) * (pageNumber - 1)) + 1)});
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
                     toastr.error(res.message);
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
         loadDetails(formApiUrl('admin/asset/group/list'));  // Load group details
     });
 
     searchContainer.submit(function (e) {
         e.preventDefault();
         loadDetails(formApiUrl('admin/asset/group/list'));  // Load group details
     });
 
     // group Form
     groupFormValidator = groupForm.validate({
         onkeyup: function (element) {
             $(element).valid();
         },
         onclick: function (element) {
             $(element).valid();
         },
         rules: {
             group_name: {
                 required: true,
                 minlength: 3
             },
             status: {
                 required: true,
             }
         },
         messages: {
             group_name: {
                 required: 'Specify group name',
                 minlength: 'Specify atleast 3 characters'
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
 
     function savegroup() {
         let loadSwal;
         var formData = new FormData(groupForm[0]);
 
         $.ajax({
             url: groupForm.attr('action'),
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
                 loadDetails(formApiUrl('admin/asset/group/list'));  // Load group details
                 toastr.success(res.message);
                 groupModal.modal('hide');    // Hide modal
                 resetgroupForm();    // Reset form
             } else if (res.status == 'error') {
                 toastr.error(res.message);
             } else {
                 toastr.error('No response status!', 'Error');
             }
         }).fail(function (jqXHR, textStatus, errorThrown) {
             toastr.error(`${textStatus} <br />${errorThrown}`, 'Error');
         });
     }
 
     groupForm.submit(function (e) {
         e.preventDefault();
         if (groupFormValidator.valid()) {
             savegroup();
         }
 
     });
 
     // Edit group
     $(listContainer).on('click', '.btn-edit-group', function (e) {
         e.preventDefault();
 
         btnResetgroupForm.hide();    // Hide button
 
         var group_id = $(this).attr('data-group');
         $.ajax({
             url: formApiUrl('admin/asset/group/detail', { group_id: group_id }),
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
                 if (res.group) {
                     var group = res.group;
 
                     // Set group Info
                     groupForm.attr('action', formApiUrl('admin/asset/group/edit', { group_id: group.asset_group_id }));
                     groupForm.find('[name="group_name"]').val(group.name);
                     groupForm.find('[name="status"]').val(group.status).trigger('change');
                    //  groupForm.find('[name="group_code"]').val(group.code);
                     groupModal.find('.modal-header .modal-title').html('Edit group');
 
                     // Show group modal
                     groupModal.modal({
                         backdrop: 'static',
                         keyboard: false,
                         show: true
                     });
                 } else {
                     toastr.error('No group available');
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
 
     // Delete group
     $(listContainer).on('click', '.btn-delete-group', function (e) {
         e.preventDefault();
         var group_id = $(this).attr('data-group');
         Swal.fire({
             icon: 'question',
             title: 'Are you sure to delete group',
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
                     url: formApiUrl('admin/asset/group/delete', { group_id: group_id }),
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
                         loadDetails(formApiUrl('admin/asset/group/list'));  // Load group details
                         toastr.success(res.message);
                         groupModal.modal('hide');    // Reset form
                         resetgroupForm();
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
 
 
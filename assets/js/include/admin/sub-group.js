/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: sub_group js
 */

 var appsub_group = {};

 $(function () {
     const listSubGroup = $('#sub-group--deatils--area');
     const lengthContainer = listSubGroup.find('[data-jy-length="record"]');
     const searchContainer = listSubGroup.find('[data-jy-search="record"]');
     const tableContainer = listSubGroup.find('[data-container="sub-groupListArea"]');
 
     const listContainer = tableContainer.find('[data-container="sub-groupTlistArea"]');
     const listPagination = tableContainer.find('[data-pagination="sub-groupTlistArea"]');
     const sub_groupForm = $('#sub-groupForm');
     var sub_groupFormValidator;
     const sub_groupModal = $('#sub-groupModal');
     const btnResetsub_groupForm = $('#btn-reset-sub-group-form');
     listPagination.find('.list-pagination').html('');
     listPagination.find('.list-pagination-label').html('');
 
     // Add sub_group
     $('#btn-add-sub-group').click(function (e) {
         e.preventDefault();
 
         btnResetsub_groupForm.show();    // Show reset button
         sub_groupForm.attr('action', formApiUrl('admin/asset/group/add'));
         sub_groupModal.find('.modal-header .modal-title').html('Add sub group');
         sub_groupModal.modal({
             backdrop: 'static',
             keyboard: false,
             show: true
         });
     });
 
     window.resetsub_groupForm = function (resetAction = true) {
 
         if (resetAction == true) {
             sub_groupForm.attr('action', '');    // Form Attribute
         }
 
         sub_groupForm[0].reset(); // Form
         sub_groupForm.find('[data-toggle="select2"]')
             .prop('disabled', false)
             .val(null)
             .trigger('change'); // Select2
         sub_groupFormValidator.resetForm();   // Jquery validation 
     }
 
     // Form reset button
     btnResetsub_groupForm.click(function (e) {
         e.preventDefault();
         resetsub_groupForm(false);
     });
 
     // Modal Form close
     sub_groupModal.find('[data-dismiss="modal"]').click(function () {
         resetsub_groupForm();
     });

     // asset group Autocomplete
    window.loadAutocompleteparents = function (options = {}) {
        var selected;
        var parentSelectbox = sub_groupForm.find('[name="parent"]');

        if (parseValue(options.selected) != '') {
            selected = (Object.keys(options.selected).length > 0) ? Object.values(options.selected) : [];
        } else {
            selected = [];
        }
        
        parentSelectbox.html("").trigger("change"); // Reset selectbox
        $.ajax({
        url: formApiUrl("admin/asset/group/autocomplete", options),
        type: "get",
        dataType: "json",
        headers: {
            Authorization: `Bearer ${wapLogin.getToken()}`,
        }
        }).done((res) => {
            parentSelectbox.append(new Option("Select", "", false, false)); // Load initial select
            if (res.status == "success") {
            if (res.asset_groups) {
                var groups = res.asset_groups;
                var groupOption;

                $.each(groups, function (bi, group) {
                    if (selected.find((value) => {
                        return value == group.id
                    })) {
                        groupOption = new Option(group.name, group.id, true, true);
                    } else {
                        groupOption = new Option(group.name, group.id, false, false);
                    }
                    
                    parentSelectbox.append(groupOption);
                });
                parentSelectbox.trigger("change");
            }
            } else if (res.status == "error") {
            console.log(res.message);
            } else {
            console.log("Group Autocomlete: Something went wrong!");
            }
        })
        .fail((xhr, ajaxOptions, errorThrown) => {
            console.log(xhr.responseText + " " + xhr.responseText);
        });
    };
 
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
                                     '<td>' + listVal.parent + '</td>' +
                                     '<td><span class="'+ status_badge_class +'">' + status_lable + '</span></td>' +
                                     '<td>' +
                                     '<a href="javascript:void(0)" data-group="' + listVal.asset_group_id + '" class="text-white btn btn-sm btn-dark waves-effect waves-light btn-edit-sub-group mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit sub-group"><i class="mdi mdi-pencil"></i></a>' +
 
                                     '<a href="javascript:void(0)" data-group="' + listVal.asset_group_id + '" class="text-white btn btn-sm btn-danger waves-effect waves-light btn-delete-sub-group mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="mdi mdi-delete"></i></a>' +
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
                                     var page_link = formApiUrl('admin/asset/group/sub_group/list', { start: ((parseInt(pagination.length) * (pageNumber - 1)) + 1)});
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
         loadDetails(formApiUrl('admin/asset/group/sub_group/list'));  // Load sub-group details
     });
 
     searchContainer.submit(function (e) {
         e.preventDefault();
         loadDetails(formApiUrl('admin/asset/group/sub_group/list'));  // Load sub-group details
     });
 
     // sub-group Form
     sub_groupFormValidator = sub_groupForm.validate({
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
             parent: {
                required: true,
            },
             status: {
                required: true,
            }
         },
         messages: {
             group_name: {
                 required: 'Specify sub-group name',
                 minlength: 'Specify atleast 3 characters'
             },
             parent: {
                required: 'Select parent',
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
 
     function savesub_group() {
         let loadSwal;
         var formData = new FormData(sub_groupForm[0]);
 
         $.ajax({
             url: sub_groupForm.attr('action'),
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
                 loadDetails(formApiUrl('admin/asset/group/sub_group/list'));  // Load sub_group details
                 toastr.success(res.message);
                 sub_groupModal.modal('hide');    // Hide modal
                 resetsub_groupForm();    // Reset form
             } else if (res.status == 'error') {
                 toastr.error(res.message);
             } else {
                 toastr.error('No response status!', 'Error');
             }
         }).fail(function (jqXHR, textStatus, errorThrown) {
             toastr.error(`${textStatus} <br />${errorThrown}`, 'Error');
         });
     }
 
     sub_groupForm.submit(function (e) {
         e.preventDefault();
         if (sub_groupFormValidator.valid()) {
             savesub_group();
         }
 
     });
 
     // Edit sub_group
     $(listContainer).on('click', '.btn-edit-sub-group', function (e) {
         e.preventDefault();
 
         btnResetsub_groupForm.hide();    // Hide button
 
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
                     sub_groupForm.attr('action', formApiUrl('admin/asset/group/edit', { group_id: group.asset_group_id }));
                     sub_groupForm.find('[name="group_name"]').val(group.name);
                     sub_groupForm.find('[name="status"]').val(group.status).trigger('change');
                     loadAutocompleteparents({
                        'selected': [group.parent]
                     })
                     sub_groupModal.find('.modal-header .modal-title').html('Edit sub group');
 
                     // Show sub_group modal
                     sub_groupModal.modal({
                         backdrop: 'static',
                         keyboard: false,
                         show: true
                     });
                 } else {
                     toastr.error('No sub_group available');
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
 
     // Delete sub_group
     $(listContainer).on('click', '.btn-delete-sub-group', function (e) {
         e.preventDefault();
         var group_id = $(this).attr('data-group');
         Swal.fire({
             icon: 'question',
             title: 'Are you sure to delete sub_group',
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
                         loadDetails(formApiUrl('admin/asset/group/sub_group/list'));  // Load group details
                         toastr.success(res.message);
                         sub_groupModal.modal('hide');    // Reset form
                         resetsub_groupForm();
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
 
 
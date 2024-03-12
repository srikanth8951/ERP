/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: sub_category js
 */

 var appsub_category = {};

 $(function () {
     const listSubCategory = $('#store-sub-category--deatils--area');
     const lengthContainer = listSubCategory.find('[data-jy-length="record"]');
     const searchContainer = listSubCategory.find('[data-jy-search="record"]');
     const tableContainer = listSubCategory.find('[data-container="store-sub-categoryListArea"]');
 
     const listContainer = tableContainer.find('[data-container="store-sub-categoryTlistArea"]');
     const listPagination = tableContainer.find('[data-pagination="store-sub-categoryTlistArea"]');
     const sub_categoryForm = $('#store-sub-categoryForm');
     var sub_categoryFormValidator;
     const sub_categoryModal = $('#store-sub-categoryModal');
     const btnResetsub_categoryForm = $('#btn-reset-store-sub-category-form');
     listPagination.find('.list-pagination').html('');
     listPagination.find('.list-pagination-label').html('');
 
     // Add sub_category
     $('#btn-add-store-sub-category').click(function (e) {
         e.preventDefault();
 
         btnResetsub_categoryForm.show();    // Show reset button
         sub_categoryForm.attr('action', formApiUrl('employee/store/category/add'));
         sub_categoryModal.find('.modal-header .modal-title').html('Add sub store category');
         sub_categoryModal.modal({
             backdrop: 'static',
             keyboard: false,
             show: true
         });
     });
 
     window.resetsub_categoryForm = function (resetAction = true) {
 
         if (resetAction == true) {
             sub_categoryForm.attr('action', '');    // Form Attribute
         }
 
         sub_categoryForm[0].reset(); // Form
         sub_categoryForm.find('[data-toggle="select2"]')
             .prop('disabled', false)
             .val(null)
             .trigger('change'); // Select2
         sub_categoryFormValidator.resetForm();   // Jquery validation 
     }
 
     // Form reset button
     btnResetsub_categoryForm.click(function (e) {
         e.preventDefault();
         resetsub_categoryForm(false);
     });
 
     // Modal Form close
     sub_categoryModal.find('[data-dismiss="modal"]').click(function () {
         resetsub_categoryForm();
     });

     // asset store_category Autocomplete
    window.loadAutocompleteparents = function (options = {}) {
        var selected;
        var parentSelectbox = sub_categoryForm.find('[name="parent"]');

        if (parseValue(options.selected) != '') {
            selected = (Object.keys(options.selected).length > 0) ? Object.values(options.selected) : [];
        } else {
            selected = [];
        }
        
        parentSelectbox.html("").trigger("change"); // Reset selectbox
        $.ajax({
        url: formApiUrl("employee/store/category/autocomplete", options),
        type: "get",
        dataType: "json",
        headers: {
            Authorization: `Bearer ${wapLogin.getToken()}`,
        }
        }).done((res) => {
            parentSelectbox.append(new Option("Select", "", false, false)); // Load initial select
            if (res.status == "success") {
            if (res.store_categories) {
                var store_categories = res.store_categories;
                var store_categoryOption;

                $.each(store_categories, function (bi, store_category) {
                    if (selected.find((value) => {
                        return value == store_category.id
                    })) {
                        store_categoryOption = new Option(store_category.name, store_category.id, true, true);
                    } else {
                        store_categoryOption = new Option(store_category.name, store_category.id, false, false);
                    }
                    
                    parentSelectbox.append(store_categoryOption);
                });
                parentSelectbox.trigger("change");
            }
            } else if (res.status == "error") {
            console.log(res.message);
            } else {
            console.log("store_category Autocomlete: Something went wrong!");
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
                     if (res.store_categories) {
                         listContainer.html('');
                         var details = res.store_categories;
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
                                     '<a href="javascript:void(0)" data-store_category="' + listVal.category_id + '" class="text-white btn btn-sm btn-dark waves-effect waves-light btn-edit-store-sub-category mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit store-sub-category"><i class="mdi mdi-pencil"></i></a>' +
 
                                     '<a href="javascript:void(0)" data-store_category="' + listVal.category_id + '" class="text-white btn btn-sm btn-danger waves-effect waves-light btn-delete-store-sub-category mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="mdi mdi-delete"></i></a>' +
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
                                     var page_link = formApiUrl('employee/store/category/sub_category/list', { start: ((parseInt(pagination.length) * (pageNumber - 1)) + 1)});
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
         loadDetails(formApiUrl('employee/store/category/sub_category/list'));  // Load store-sub-category details
     });
 
     searchContainer.submit(function (e) {
         e.preventDefault();
         loadDetails(formApiUrl('employee/store/category/sub_category/list'));  // Load store-sub-category details
     });
 
     // store-sub-category Form
     sub_categoryFormValidator = sub_categoryForm.validate({
         onkeyup: function (element) {
             $(element).valid();
         },
         onclick: function (element) {
             $(element).valid();
         },
         rules: {
             store_category_name: {
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
             store_category_name: {
                 required: 'Specify store-sub-category name',
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
 
     function savesub_category() {
         let loadSwal;
         var formData = new FormData(sub_categoryForm[0]);
 
         $.ajax({
             url: sub_categoryForm.attr('action'),
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
                 loadDetails(formApiUrl('employee/store/category/sub_category/list'));  // Load sub_category details
                 toastr.success(res.message);
                 sub_categoryModal.modal('hide');    // Hide modal
                 resetsub_categoryForm();    // Reset form
             } else if (res.status == 'error') {
                 toastr.error(res.message);
             } else {
                 toastr.error('No response status!', 'Error');
             }
         }).fail(function (jqXHR, textStatus, errorThrown) {
             toastr.error(`${textStatus} <br />${errorThrown}`, 'Error');
         });
     }
 
     sub_categoryForm.submit(function (e) {
         e.preventDefault();
         if (sub_categoryFormValidator.valid()) {
             savesub_category();
         }
 
     });
 
     // Edit sub_category
     $(listContainer).on('click', '.btn-edit-store-sub-category', function (e) {
         e.preventDefault();
 
         btnResetsub_categoryForm.hide();    // Hide button
 
         var store_category_id = $(this).attr('data-store_category');
         $.ajax({
             url: formApiUrl('employee/store/category/detail', { store_category_id: store_category_id }),
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
                 if (res.store_category) {
                     var store_category = res.store_category;
 
                     // Set store_category Info
                     sub_categoryForm.attr('action', formApiUrl('employee/store/category/edit', { store_category_id: store_category.category_id }));
                     sub_categoryForm.find('[name="store_category_name"]').val(store_category.name);
                     sub_categoryForm.find('[name="status"]').val(store_category.status).trigger('change');
                     loadAutocompleteparents({
                        'selected': [store_category.parent]
                     })
                     sub_categoryModal.find('.modal-header .modal-title').html('Edit sub store category');
 
                     // Show sub_category modal
                     sub_categoryModal.modal({
                         backdrop: 'static',
                         keyboard: false,
                         show: true
                     });
                 } else {
                     toastr.error('No sub_category available');
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
 
     // Delete sub_category
     $(listContainer).on('click', '.btn-delete-store-sub-category', function (e) {
         e.preventDefault();
         var store_category_id = $(this).attr('data-store_category');
         Swal.fire({
             icon: 'question',
             title: 'Are you sure to delete sub_category',
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
                     url: formApiUrl('employee/store/category/delete', { store_category_id: store_category_id }),
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
                         loadDetails(formApiUrl('employee/store/category/sub_category/list'));  // Load store_category details
                         toastr.success(res.message);
                         sub_categoryModal.modal('hide');    // Reset form
                         resetsub_categoryForm();
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
 
 
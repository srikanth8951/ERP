/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: store_category js
 */

 var appstore_category = {};

 $(function () {
     const listStoreCategory = $('#store_category--deatils--area');
     const lengthContainer = listStoreCategory.find('[data-jy-length="record"]');
     const searchContainer = listStoreCategory.find('[data-jy-search="record"]');
     const tableContainer = listStoreCategory.find('[data-container="store_categoryListArea"]');
 
     const listContainer = tableContainer.find('[data-container="store_categoryTlistArea"]');
     const listPagination = tableContainer.find('[data-pagination="store_categoryTlistArea"]');
     const store_categoryForm = $('#store_categoryForm');
     var store_categoryFormValidator;
     const store_categoryModal = $('#store_categoryModal');
     const btnResetstore_categoryForm = $('#btn-reset-store_category-form');
     listPagination.find('.list-pagination').html('');
     listPagination.find('.list-pagination-label').html('');
 
     // Add store_category
     $('#btn-add-store_category').click(function (e) {
         e.preventDefault();
 
         btnResetstore_categoryForm.show();    // Show reset button
         store_categoryForm.attr('action', formApiUrl('admin/store/category/add'));
         store_categoryModal.find('.modal-header .modal-title').html('Add store category');
         store_categoryModal.modal({
             backdrop: 'static',
             keyboard: false,
             show: true
         });
     });
 
     window.resetstore_categoryForm = function (resetAction = true) {
 
         if (resetAction == true) {
             store_categoryForm.attr('action', '');    // Form Attribute
         }
 
         store_categoryForm[0].reset(); // Form
         store_categoryForm.find('[data-toggle="select2"]')
             .prop('disabled', false)
             .val(null)
             .trigger('change'); // Select2
         store_categoryFormValidator.resetForm();   // Jquery validation 
     }
 
     // Form reset button
     btnResetstore_categoryForm.click(function (e) {
         e.preventDefault();
         resetstore_categoryForm(false);
     });
 
     // Modal Form close
     store_categoryModal.find('[data-dismiss="modal"]').click(function () {
         resetstore_categoryForm();
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
                                     '<td><span class="'+ status_badge_class +'">' + status_lable + '</span></td>' +
                                     '<td>' +
                                     '<a href="javascript:void(0)" data-store_category="' + listVal.category_id + '" class="text-white btn btn-sm btn-dark waves-effect waves-light btn-edit-store_category mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit store_category"><i class="mdi mdi-pencil"></i></a>' +
 
                                     '<a href="javascript:void(0)" data-store_category="' + listVal.category_id + '" class="text-white btn btn-sm btn-danger waves-effect waves-light btn-delete-store_category mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="mdi mdi-delete"></i></a>' +
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
                                     var page_link = formApiUrl('admin/store/category/list', { start: ((parseInt(pagination.length) * (pageNumber - 1)) + 1)});
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
         loadDetails(formApiUrl('admin/store/category/list'));  // Load store_category details
     });
 
     searchContainer.submit(function (e) {
         e.preventDefault();
         loadDetails(formApiUrl('admin/store/category/list'));  // Load store_category details
     });
 
     // store_category Form
     store_categoryFormValidator = store_categoryForm.validate({
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
             status: {
                 required: true,
             }
         },
         messages: {
             store_category_name: {
                 required: 'Specify store_category name',
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
 
     function savestore_category() {
         let loadSwal;
         var formData = new FormData(store_categoryForm[0]);
 
         $.ajax({
             url: store_categoryForm.attr('action'),
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
                 loadDetails(formApiUrl('admin/store/category/list'));  // Load store_category details
                 toastr.success(res.message);
                 store_categoryModal.modal('hide');    // Hide modal
                 resetstore_categoryForm();    // Reset form
             } else if (res.status == 'error') {
                 toastr.error(res.message);
             } else {
                 toastr.error('No response status!', 'Error');
             }
         }).fail(function (jqXHR, textStatus, errorThrown) {
             toastr.error(`${textStatus} <br />${errorThrown}`, 'Error');
         });
     }
 
     store_categoryForm.submit(function (e) {
         e.preventDefault();
         if (store_categoryFormValidator.valid()) {
             savestore_category();
         }
 
     });
 
     // Edit store_category
     $(listContainer).on('click', '.btn-edit-store_category', function (e) {
         e.preventDefault();
 
         btnResetstore_categoryForm.hide();    // Hide button
 
         var store_category_id = $(this).attr('data-store_category');
         $.ajax({
             url: formApiUrl('admin/store/category/detail', { store_category_id: store_category_id }),
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
                     store_categoryForm.attr('action', formApiUrl('admin/store/category/edit', { store_category_id: store_category.category_id }));
                     store_categoryForm.find('[name="store_category_name"]').val(store_category.name);
                     store_categoryForm.find('[name="status"]').val(store_category.status).trigger('change');
                    //  store_categoryForm.find('[name="store_category_code"]').val(store_category.code);
                     store_categoryModal.find('.modal-header .modal-title').html('Edit category');
 
                     // Show store_category modal
                     store_categoryModal.modal({
                         backdrop: 'static',
                         keyboard: false,
                         show: true
                     });
                 } else {
                     toastr.error('No store_category available');
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
 
     // Delete store_category
     $(listContainer).on('click', '.btn-delete-store_category', function (e) {
         e.preventDefault();
         var store_category_id = $(this).attr('data-store_category');
         Swal.fire({
             icon: 'question',
             title: 'Are you sure to delete store_category',
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
                     url: formApiUrl('admin/store/category/delete', { store_category_id: store_category_id }),
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
                         loadDetails(formApiUrl('admin/store/category/list'));  // Load store_category details
                         toastr.success(res.message);
                         store_categoryModal.modal('hide');    // Reset form
                         resetstore_categoryForm();
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
 
 
/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: department js
 */

 var appdepartment = {};

 $(function () {
     const listDepartment = $('#department--deatils--area');
     const lengthContainer = listDepartment.find('[data-jy-length="record"]');
     const searchContainer = listDepartment.find('[data-jy-search="record"]');
     const tableContainer = listDepartment.find('[data-container="departmentListArea"]');
     
     const listContainer = tableContainer.find('[data-container="departmentTlistArea"]');
     const listPagination = tableContainer.find('[data-pagination="departmentTlistArea"]');
     const departmentForm = $('#departmentForm');
     var departmentFormValidator;
     const departmentModal = $('#departmentModal');
     const btnResetdepartmentForm = $('#btn-reset-department-form');
     listPagination.find('.list-pagination').html('');
     listPagination.find('.list-pagination-label').html('');
 
     // Add department
     $('#btn-add-department').click(function (e) {
         e.preventDefault();
 
         btnResetdepartmentForm.show();    // Show reset button
         departmentForm.attr('action', formApiUrl('admin/department/add'));
         departmentModal.find('.modal-header .modal-title').html('Add department');
         departmentModal.modal({
             backdrop: 'static',
             keyboard: false,
             show: true
         });
     });
 
     window.resetdepartmentForm = function (resetAction = true) {
 
         if (resetAction == true) {
             departmentForm.attr('action', '');    // Form Attribute
         }
 
         departmentForm[0].reset(); // Form
         departmentForm.find('[data-toggle="select2"]')
             .prop('disabled', false)
             .val(null)
             .trigger('change'); // Select2
         departmentFormValidator.resetForm();   // Jquery validation 
     }
 
     // Form reset button
     btnResetdepartmentForm.click(function (e) {
         e.preventDefault();
         resetdepartmentForm(false);
     });
 
     // Modal Form close
     departmentModal.find('[data-dismiss="modal"]').click(function () {
         resetdepartmentForm();
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
                     if (res.departments) {
                         listContainer.html('');
                         var details = res.departments;
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
                                     '<a href="javascript:void(0)" data-department="' + listVal.department_id + '" class="text-white btn btn-sm btn-dark waves-effect waves-light btn-edit-department mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit department"><i class="mdi mdi-pencil"></i></a>' +
 
                                     '<a href="javascript:void(0)" data-department="' + listVal.department_id + '" class="text-white btn btn-sm btn-danger waves-effect waves-light btn-delete-department mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="mdi mdi-delete"></i></a>' +
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
                                     var page_link = formApiUrl('admin/department/list', { start: ((parseInt(pagination.length) * (pageNumber - 1)) + 1)});
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
         loadDetails(formApiUrl('admin/department/list'));  // Load department details
     });
 
     searchContainer.submit(function (e) {
        e.preventDefault();
        loadDetails(formApiUrl('admin/department/list'));  // Load department details
    });
 
     // department Form
     departmentFormValidator = departmentForm.validate({
         onkeyup: function (element) {
             $(element).valid();
         },
         onclick: function (element) {
             $(element).valid();
         },
         rules: {
             department_name: {
                 required: true,
                //  minlength: 3
             }
         },
         messages: {
             department_name: {
                 required: 'Specify department name',
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
 
     function savedepartment() {
         let loadSwal;
         var formData = new FormData(departmentForm[0]);
 
         $.ajax({
             url: departmentForm.attr('action'),
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
                 loadDetails(formApiUrl('admin/department/list'));  // Load department details
                 toastr.success(res.message);
                 departmentModal.modal('hide');    // Hide modal
                 resetdepartmentForm();    // Reset form
             } else if (res.status == 'error') {
                 toastr.error(res.message);
             } else {
                 toastr.error('No response status!', 'Error');
             }
         }).fail(function (jqXHR, textStatus, errorThrown) {
             toastr.error(`${textStatus} <br />${errorThrown}` , 'Error');
         });
     }
 
     departmentForm.submit(function (e) {
         e.preventDefault();
         if (departmentFormValidator.valid()) {
             savedepartment();
         }
 
     });
 
     // Edit department
     $(listContainer).on('click', '.btn-edit-department', function (e) {
         e.preventDefault();
 
         btnResetdepartmentForm.hide();    // Hide button
 
         var department_id = $(this).attr('data-department');
         $.ajax({
             url: formApiUrl('admin/department/detail', { department_id: department_id }),
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
                 if (res.department) {
                     var department = res.department;
 
                     // Set department Info
                     departmentForm.attr('action', formApiUrl('admin/department/edit', { department_id: department.department_id }));
                     departmentForm.find('[name="department_name"]').val(department.name);
                     departmentModal.find('.modal-header .modal-title').html('Edit department');
 
                     // Show department modal
                     departmentModal.modal({
                         backdrop: 'static',
                         keyboard: false,
                         show: true
                     });
                 } else {
                     toastr.error('No department available');
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
 
     // Delete department
     $(listContainer).on('click', '.btn-delete-department', function (e) {
         e.preventDefault();
         var department_id = $(this).attr('data-department');
         Swal.fire({
             icon: 'question',
             title: 'Are you sure to delete department',
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
                     url: formApiUrl('admin/department/delete', { department_id: department_id }),
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
                         loadDetails(formApiUrl('admin/department/list'));  // Load department details
                         toastr.success(res.message);
                         departmentModal.modal('hide');    // Reset form
                         resetdepartmentForm();
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
 
 
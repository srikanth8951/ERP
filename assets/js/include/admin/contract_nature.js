/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: contract_nature js
 */

 var appcontract_nature = {};

 $(function () {
     const listContract_nature = $('#contract_nature--deatils--area');
     const lengthContainer = listContract_nature.find('[data-jy-length="record"]');
     const searchContainer = listContract_nature.find('[data-jy-search="record"]');
     const tableContainer = listContract_nature.find('[data-container="contract_natureListArea"]');
     
     const listContainer = tableContainer.find('[data-container="contract_natureTlistArea"]');
     const listPagination = tableContainer.find('[data-pagination="contract_natureTlistArea"]');
     const contract_natureForm = $('#contract_natureForm');
     var contract_natureFormValidator;
     const contract_natureModal = $('#contract_natureModal');
     const btnResetcontract_natureForm = $('#btn-reset-contract_nature-form');
     listPagination.find('.list-pagination').html('');
     listPagination.find('.list-pagination-label').html('');
 
     // Add contract_nature
     $('#btn-add-contract_nature').click(function (e) {
         e.preventDefault();
 
         btnResetcontract_natureForm.show();    // Show reset button
         contract_natureForm.attr('action', formApiUrl('admin/contract_nature/add'));
         contract_natureModal.find('.modal-header .modal-title').html('Add contract_nature');
         contract_natureModal.modal({
             backdrop: 'static',
             keyboard: false,
             show: true
         });
     });
 
     window.resetcontract_natureForm = function (resetAction = true) {
 
         if (resetAction == true) {
             contract_natureForm.attr('action', '');    // Form Attribute
         }
 
         contract_natureForm[0].reset(); // Form
         contract_natureForm.find('[data-toggle="select2"]')
             .prop('disabled', false)
             .val(null)
             .trigger('change'); // Select2
         contract_natureFormValidator.resetForm();   // Jquery validation 
     }
 
     // Form reset button
     btnResetcontract_natureForm.click(function (e) {
         e.preventDefault();
         resetcontract_natureForm(false);
     });
 
     // Modal Form close
     contract_natureModal.find('[data-dismiss="modal"]').click(function () {
         resetcontract_natureForm();
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
                     if (res.contract_natures) {
                         listContainer.html('');
                         var details = res.contract_natures;
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
                                     '<td>' + listVal.code + '</td>' +
                                     '<td>' +
                                     '<a href="javascript:void(0)" data-contract_nature="' + listVal.contract_nature_id + '" class="text-white btn btn-sm btn-dark waves-effect waves-light btn-edit-contract_nature mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit contract_nature"><i class="mdi mdi-pencil"></i></a>' +
 
                                     '<a href="javascript:void(0)" data-contract_nature="' + listVal.contract_nature_id + '" class="text-white btn btn-sm btn-danger waves-effect waves-light btn-delete-contract_nature mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="mdi mdi-delete"></i></a>' +
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
                                     var page_link = formApiUrl('admin/contract_nature/list', { start: ((parseInt(pagination.length) * (pageNumber - 1)) + 1)});
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
         loadDetails(formApiUrl('admin/contract_nature/list'));  // Load contract_nature details
     });
 
     searchContainer.submit(function (e) {
        e.preventDefault();
        loadDetails(formApiUrl('admin/contract_nature/list'));  // Load contract_nature details
    });
 
     // contract_nature Form
     contract_natureFormValidator = contract_natureForm.validate({
         onkeyup: function (element) {
             $(element).valid();
         },
         onclick: function (element) {
             $(element).valid();
         },
         rules: {
             contract_nature_name: {
                 required: true,
                //  minlength: 3
             },
             contract_nature_code: {
                required: true,
            }
         },
         messages: {
             contract_nature_name: {
                 required: 'Specify contract_nature name',
                //  minlength: 'Specify atleast 3 characters'
             },
             contract_nature_code: {
                required: 'Specify contract_nature code',
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
 
     function savecontract_nature() {
         let loadSwal;
         var formData = new FormData(contract_natureForm[0]);
 
         $.ajax({
             url: contract_natureForm.attr('action'),
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
                 loadDetails(formApiUrl('admin/contract_nature/list'));  // Load contract_nature details
                 toastr.success(res.message);
                 contract_natureModal.modal('hide');    // Hide modal
                 resetcontract_natureForm();    // Reset form
             } else if (res.status == 'error') {
                 toastr.error(res.message);
             } else {
                 toastr.error('No response status!', 'Error');
             }
         }).fail(function (jqXHR, textStatus, errorThrown) {
             toastr.error(`${textStatus} <br />${errorThrown}` , 'Error');
         });
     }
 
     contract_natureForm.submit(function (e) {
         e.preventDefault();
         if (contract_natureFormValidator.valid()) {
             savecontract_nature();
         }
 
     });
 
     // Edit contract_nature
     $(listContainer).on('click', '.btn-edit-contract_nature', function (e) {
         e.preventDefault();
 
         btnResetcontract_natureForm.hide();    // Hide button
 
         var contract_nature_id = $(this).attr('data-contract_nature');
         $.ajax({
             url: formApiUrl('admin/contract_nature/detail', { contract_nature_id: contract_nature_id }),
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
                 if (res.contract_nature) {
                     var contract_nature = res.contract_nature;
 
                     // Set contract_nature Info
                     contract_natureForm.attr('action', formApiUrl('admin/contract_nature/edit', { contract_nature_id: contract_nature.contract_nature_id }));
                     contract_natureForm.find('[name="contract_nature_name"]').val(contract_nature.name);
                     contract_natureForm.find('[name="contract_nature_code"]').val(contract_nature.code);
                     contract_natureModal.find('.modal-header .modal-title').html('Edit contract_nature');
 
                     // Show contract_nature modal
                     contract_natureModal.modal({
                         backdrop: 'static',
                         keyboard: false,
                         show: true
                     });
                 } else {
                     toastr.error('No contract_nature available');
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
 
     // Delete contract_nature
     $(listContainer).on('click', '.btn-delete-contract_nature', function (e) {
         e.preventDefault();
         var contract_nature_id = $(this).attr('data-contract_nature');
         Swal.fire({
             icon: 'question',
             title: 'Are you sure to delete contract_nature',
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
                     url: formApiUrl('admin/contract_nature/delete', { contract_nature_id: contract_nature_id }),
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
                         loadDetails(formApiUrl('admin/contract_nature/list'));  // Load contract_nature details
                         toastr.success(res.message);
                         contract_natureModal.modal('hide');    // Reset form
                         resetcontract_natureForm();
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
 
 
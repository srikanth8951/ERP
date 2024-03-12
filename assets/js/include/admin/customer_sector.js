/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: customer_sector js
 */

 var appcustomer_sector = {};

 $(function () {
     const listCustomerSector = $('#customer_sector--deatils--area');
     const lengthContainer = listCustomerSector.find('[data-jy-length="record"]');
     const searchContainer = listCustomerSector.find('[data-jy-search="record"]');
     const tableContainer = listCustomerSector.find('[data-container="customer_sectorListArea"]');
 
     const listContainer = tableContainer.find('[data-container="customer_sectorTlistArea"]');
     const listPagination = tableContainer.find('[data-pagination="customer_sectorTlistArea"]');
     const customer_sectorForm = $('#customer_sectorForm');
     var customer_sectorFormValidator;
     const customer_sectorModal = $('#customer_sectorModal');
     const btnResetcustomer_sectorForm = $('#btn-reset-customer_sector-form');
     listPagination.find('.list-pagination').html('');
     listPagination.find('.list-pagination-label').html('');
 
     // Add customer_sector
     $('#btn-add-customer_sector').click(function (e) {
         e.preventDefault();
 
         loadAutocompleteCustomerSectorType(); // Load autocomplete for type
         btnResetcustomer_sectorForm.show();    // Show reset button
         customer_sectorForm.attr('action', formApiUrl('admin/customer_sector/add'));
         customer_sectorModal.find('.modal-header .modal-title').html('Add customer sector');
         customer_sectorModal.modal({
             backdrop: 'static',
             keyboard: false,
             show: true
         });
     });
 
     window.resetcustomer_sectorForm = function (resetAction = true) {
 
         if (resetAction == true) {
             customer_sectorForm.attr('action', '');    // Form Attribute
         }
 
         customer_sectorForm[0].reset(); // Form
         customer_sectorForm.find('[data-toggle="select2"]')
             .prop('disabled', false)
             .val(null)
             .trigger('change'); // Select2
         customer_sectorFormValidator.resetForm();   // Jquery validation 
     }
 
     // Form reset button
     btnResetcustomer_sectorForm.click(function (e) {
         e.preventDefault();
         resetcustomer_sectorForm(false);
     });
 
     // Modal Form close
     customer_sectorModal.find('[data-dismiss="modal"]').click(function () {
         resetcustomer_sectorForm();
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
                     if (res.customer_sectores) {
                         listContainer.html('');
                         var details = res.customer_sectores;
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
                                     '<td>' + listVal.title + '</td>' +
                                     '<td>' + listVal.type_name + '</td>' +
                                     '<td>' +
                                     '<a href="javascript:void(0)" data-customer_sector="' + listVal.customer_sector_id + '" class="text-white btn btn-sm btn-dark waves-effect waves-light btn-edit-customer_sector mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit customer_sector"><i class="mdi mdi-pencil"></i></a>' +
 
                                     '<a href="javascript:void(0)" data-customer_sector="' + listVal.customer_sector_id + '" class="text-white btn btn-sm btn-danger waves-effect waves-light btn-delete-customer_sector mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="mdi mdi-delete"></i></a>' +
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
                                     var page_link = formApiUrl('admin/customer_sector/list', {
                                        start: parseInt(pagination.length) * (pageNumber - 1) + 1,
                                      });
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
         loadDetails(formApiUrl('admin/customer_sector/list'));  // Load customer_sector details
     });
 
     searchContainer.submit(function (e) {
         e.preventDefault();
         loadDetails(formApiUrl('admin/customer_sector/list'));  // Load customer_sector details
     });
     
     // Customer Sector Type Autocomplete
     window.loadAutocompleteCustomerSectorType = function (options = {}) {
        var selected;
        var typeSelectbox = customer_sectorForm.find('[name="type_id"]');

        if (parseValue(options.selected) != '') {
            selected = (Object.keys(options.selected).length > 0) ? Object.values(options.selected) : [];
        } else {
            selected = [];
        }
        
        typeSelectbox.html("").trigger("change"); // Reset selectbox
        $.ajax({
        url: formApiUrl("admin/customer_sector_type/autocomplete", options),
        type: "get",
        dataType: "json",
        headers: {
            Authorization: `Bearer ${wapLogin.getToken()}`,
        }
        }).done((res) => {
            typeSelectbox.append(new Option("Select", "", false, false)); // Load initial select
            if (res.status == "success") {
            if (res.types) {
                var types = res.types;
                var typeOption;

                $.each(types, function (bi, type) {
                    if (selected.find((value) => {
                        return value == type.id
                    })) {
                        typeOption = new Option(type.name, type.id, true, true);
                    } else {
                        typeOption = new Option(type.name, type.id, false, false);
                    }
                    
                    typeSelectbox.append(typeOption);
                });
                typeSelectbox.trigger("change");
            }
            } else if (res.status == "error") {
            console.log(res.message);
            } else {
            console.log("type Autocomlete: Something went wrong!");
            }
        })
        .fail((xhr, ajaxOptions, errorThrown) => {
            console.log(xhr.responseText + " " + xhr.responseText);
        });
     };
     
     // customer_sector Form
     customer_sectorFormValidator = customer_sectorForm.validate({
         onkeyup: function (element) {
             $(element).valid();
         },
         onclick: function (element) {
             $(element).valid();
         },
         rules: {
             title: {
                 required: true,
                 minlength: 3
             },
             type: {
                 required: true,
             }
         },
         messages: {
             title: {
                 required: 'Specify customer_sector title',
                 minlength: 'Specify atleast 3 characters'
             },
             type: {
                 required: 'Specify customer_sector type'
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
 
     function savecustomer_sector() {
         let loadSwal;
         var formData = new FormData(customer_sectorForm[0]);
 
         $.ajax({
             url: customer_sectorForm.attr('action'),
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
                 loadDetails(formApiUrl('admin/customer_sector/list'));  // Load customer_sector details
                 toastr.success(res.message);
                 customer_sectorModal.modal('hide');    // Hide modal
                 resetcustomer_sectorForm();    // Reset form
             } else if (res.status == 'error') {
                 toastr.error(res.message);
             } else {
                 toastr.error('No response status!', 'Error');
             }
         }).fail(function (jqXHR, textStatus, errorThrown) {
             toastr.error(`${textStatus} <br />${errorThrown}`, 'Error');
         });
     }
 
     customer_sectorForm.submit(function (e) {
         e.preventDefault();
         if (customer_sectorFormValidator.valid()) {
             savecustomer_sector();
         }
 
     });
 
     // Edit customer_sector
     $(listContainer).on('click', '.btn-edit-customer_sector', function (e) {
         e.preventDefault();
 
         btnResetcustomer_sectorForm.hide();    // Hide button
 
         var customer_sector_id = $(this).attr('data-customer_sector');
         $.ajax({
             url: formApiUrl('admin/customer_sector/detail', { customer_sector_id: customer_sector_id }),
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
                 if (res.customer_sector) {
                     var customer_sector = res.customer_sector;
 
                     // Set customer_sector Info
                     customer_sectorForm.attr('action', formApiUrl('admin/customer_sector/edit', { customer_sector_id: customer_sector.customer_sector_id }));

                     // types selectbox with selected value
                     loadAutocompleteCustomerSectorType({
                        'selected': [customer_sector.type_id]
                     });

                     customer_sectorForm.find('[name="title"]').val(customer_sector.title);
                     customer_sectorModal.find('.modal-header .modal-title').html('Edit customer_sector');
 
                     // Show customer_sector modal
                     customer_sectorModal.modal({
                         backdrop: 'static',
                         keyboard: false,
                         show: true
                     });
                 } else {
                     toastr.error('No customer_sector available');
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
 
     // Delete customer_sector
     $(listContainer).on('click', '.btn-delete-customer_sector', function (e) {
         e.preventDefault();
         var customer_sector_id = $(this).attr('data-customer_sector');
         Swal.fire({
             icon: 'question',
             title: 'Are you sure to delete customer_sector',
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
                     url: formApiUrl('admin/customer_sector/delete', { customer_sector_id: customer_sector_id }),
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
                         loadDetails(formApiUrl('admin/customer_sector/list'));  // Load customer_sector details
                         toastr.success(res.message);
                         customer_sectorModal.modal('hide');    // Reset form
                         resetcustomer_sectorForm();
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
 
 
/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: attribute js
 */

 var appattribute = {};

 $(function () {
     const listAttribute = $('#attribute--deatils--area');
     const lengthContainer = listAttribute.find('[data-jy-length="record"]');
     const searchContainer = listAttribute.find('[data-jy-search="record"]');
     const tableContainer = listAttribute.find('[data-container="attributeListArea"]');
 
     const listContainer = tableContainer.find('[data-container="attributeTlistArea"]');
     const listPagination = tableContainer.find('[data-pagination="attributeTlistArea"]');
     const attributeForm = $('#attributeForm');
     var attributeFormValidator;
     const attributeModal = $('#attributeModal');
     const btnResetattributeForm = $('#btn-reset-attribute-form');
     listPagination.find('.list-pagination').html('');
     listPagination.find('.list-pagination-label').html('');
 
     // Add attribute
     $('#btn-add-attribute').click(function (e) {
         e.preventDefault();
 
         loadAutocompleteAttributeGroups(); // Load autocomplete for AttributeGroup
         btnResetattributeForm.show();    // Show reset button
         attributeForm.attr('action', formApiUrl('admin/store/attribute/add'));
         attributeModal.find('.modal-header .modal-title').html('Add attribute');
         attributeModal.modal({
             backdrop: 'static',
             keyboard: false,
             show: true
         });
     });
 
     window.resetattributeForm = function (resetAction = true) {
 
         if (resetAction == true) {
             attributeForm.attr('action', '');    // Form Attribute
         }
 
         attributeForm[0].reset(); // Form
         attributeForm.find('[data-toggle="select2"]')
             .prop('disabled', false)
             .val(null)
             .trigger('change'); // Select2
         attributeFormValidator.resetForm();   // Jquery validation 
     }
 
     // Form reset button
     btnResetattributeForm.click(function (e) {
         e.preventDefault();
         resetattributeForm(false);
     });
 
     // Modal Form close
     attributeModal.find('[data-dismiss="modal"]').click(function () {
         resetattributeForm();
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
                     if (res.attributes) {
                         listContainer.html('');
                         var details = res.attributes;
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
                                     "<td>" + listVal.attribute_group_name + "</td>" +
                                     '<td><span class="'+ status_badge_class +'">' + status_lable + '</span></td>' +
                                     '<td>' +
                                     '<a href="javascript:void(0)" data-attribute="' + listVal.attribute_id + '" class="text-white btn btn-sm btn-dark waves-effect waves-light btn-edit-attribute mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit attribute"><i class="mdi mdi-pencil"></i></a>' +
 
                                     '<a href="javascript:void(0)" data-attribute="' + listVal.attribute_id + '" class="text-white btn btn-sm btn-danger waves-effect waves-light btn-delete-attribute mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="mdi mdi-delete"></i></a>' +
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
                                     var page_link = formApiUrl('admin/store/attribute/list', { start: ((parseInt(pagination.length) * (pageNumber - 1)) + 1)});
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
         loadDetails(formApiUrl('admin/store/attribute/list'));  // Load attribute details
     });
 
     searchContainer.submit(function (e) {
         e.preventDefault();
         loadDetails(formApiUrl('admin/store/attribute/list'));  // Load attribute details
     });
 
     // Attribute Group Autocomplete
     window.loadAutocompleteAttributeGroups = function (options = {}) {
         var selected;
         var attributeGroupSelectbox = attributeForm.find('[name="attribute_group_id"]');
 
         if (parseValue(options.selected) != '') {
             selected = (Object.keys(options.selected).length > 0) ? Object.values(options.selected) : [];
         } else {
             selected = [];
         }
 
         attributeGroupSelectbox.html("").trigger("change"); // Reset selectbox
         $.ajax({
             url: formApiUrl("admin/store/attribute_group/autocomplete", options),
             type: "get",
             dataType: "json",
             headers: {
                 Authorization: `Bearer ${wapLogin.getToken()}`,
             }
         }).done((res) => {
            attributeGroupSelectbox.append(new Option("Select", "", false, false)); // Load initial select
             if (res.status == "success") {
                 if (res.attribute_groups) {
                     var attribute_groups = res.attribute_groups;
                     var attribute_groupOption;
 
                     $.each(attribute_groups, function (bi, attribute_group) {
                         if (selected.find((value) => {
                             return value == attribute_group.id
                         })) {
                             attribute_groupOption = new Option(attribute_group.name, attribute_group.id, true, true);
                         } else {
                             attribute_groupOption = new Option(attribute_group.name, attribute_group.id, false, false);
                         }
 
                         attributeGroupSelectbox.append(attribute_groupOption);
                     });
                     attributeGroupSelectbox.trigger("change");
                 }
             } else if (res.status == "error") {
                 console.log(res.message);
             } else {
                 console.log("attribute_group Autocomlete: Something went wrong!");
             }
         })
             .fail((xhr, ajaxOptions, errorThrown) => {
                 console.log(xhr.responseText + " " + xhr.responseText);
             });
     };
 
     // attribute Form
     attributeFormValidator = attributeForm.validate({
         onkeyup: function (element) {
             $(element).valid();
         },
         onclick: function (element) {
             $(element).valid();
         },
         rules: {
             attribute_group_id: {
                 required: true
             },
             attribute_name: {
                 required: true,
                 // minlength: 3
             },
             attribute_code: {
                 required: true,
             }
         },
         messages: {
             attribute_group_id: {
                 required: 'Select attribute_group'
             },
             attribute_name: {
                 required: 'Specify attribute name',
                 // minlength: 'Specify atleast 3 characters'
             },
             attribute_code: {
                 required: 'Specify attribute code'
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
 
     function saveattribute() {
         let loadSwal;
         var formData = new FormData(attributeForm[0]);
 
         $.ajax({
             url: attributeForm.attr('action'),
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
                 loadDetails(formApiUrl('admin/store/attribute/list'));  // Load attribute details
                 toastr.success(res.message);
                 attributeModal.modal('hide');    // Hide modal
                 resetattributeForm();    // Reset form
             } else if (res.status == 'error') {
                 toastr.error(res.message);
             } else {
                 toastr.error('No response status!', 'Error');
             }
         }).fail(function (jqXHR, textStatus, errorThrown) {
             toastr.error(`${textStatus} <br />${errorThrown}`, 'Error');
         });
     }
 
     attributeForm.submit(function (e) {
         e.preventDefault();
         if (attributeFormValidator.valid()) {
             saveattribute();
         }
 
     });
 
     // Edit attribute
     $(listContainer).on('click', '.btn-edit-attribute', function (e) {
         e.preventDefault();
 
         btnResetattributeForm.hide();    // Hide button
 
         var attribute_id = $(this).attr('data-attribute');
         $.ajax({
             url: formApiUrl('admin/store/attribute/detail', { attribute_id: attribute_id }),
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
                 if (res.attribute) {
                     var attribute = res.attribute;
 
                     // Set attribute Info
                     attributeForm.attr('action', formApiUrl('admin/store/attribute/edit', { attribute_id: attribute.attribute_id }));
 
                     // Attribute Groups selectbox with selected value
                     loadAutocompleteAttributeGroups({
                         'selected': [attribute.attribute_group_id]
                     });
 
 
                     attributeForm.find('[name="attribute_name"]').val(attribute.name);
                     attributeForm.find('[name="status"]').val(attribute.status).trigger('change');
                     attributeModal.find('.modal-header .modal-title').html('Edit attribute');
 
                     // Show attribute modal
                     attributeModal.modal({
                         backdrop: 'static',
                         keyboard: false,
                         show: true
                     });
                 } else {
                     toastr.error('No attribute available');
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
 
     // Delete attribute
     $(listContainer).on('click', '.btn-delete-attribute', function (e) {
         e.preventDefault();
         var attribute_id = $(this).attr('data-attribute');
         Swal.fire({
             icon: 'question',
             title: 'Are you sure to delete attribute',
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
                     url: formApiUrl('admin/store/attribute/delete', { attribute_id: attribute_id }),
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
                         loadDetails(formApiUrl('admin/store/attribute/list'));  // Load attribute details
                         toastr.success(res.message);
                         attributeModal.modal('hide');    // Reset form
                         resetattributeForm();
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
 
     $('#btn-download-upload-sample').click(function (e) {
         e.preventDefault();
         let loadSwal;
         const invForm = $(this);
 
         $.ajax({
             url: formApiUrl('admin/store/attribute/downloadSample'),
             type: 'get',
             dataType: 'json',
             headers: {
                 Authorization: `Bearer ${wapLogin.getToken()}`,
             },
             beforeSend: function () {
                 loadSwal = Swal.fire({
                     html:
                         '<div class="my-4 text-center d-inline-block">' +
                         loaderContent +
                         "</div>",
                     customClass: {
                         popup: "col-6 col-sm-5 col-md-3 col-lg-2",
                     },
                     allowOutsideClick: false,
                     allowEscapeKey: false,
                     showConfirmButton: false,
                 });
             },
             complete: function () {
                 loadSwal.close();
             }
         }).then(function (res) {
             if (res.status == 'success') {
                 if (res.content) {
                     let fileName = 'attribute-' + moment().format('DD/MM/YYYY') + '.xlsx';
                     var anchorElement = $('<a></a>');
                     anchorElement.attr('href', res.content);
                     anchorElement.attr('download', fileName);
                     anchorElement.css('display', 'none');
                     anchorElement.html('Download');
                     anchorElement.appendTo('body');
                     anchorElement[0].click();
 
                     setTimeout(function () {
                         anchorElement.remove();
                     }, 1000);
                 }
             } else {
                 let res_message = '';
                 if (typeof res.message != 'undefined') {
                     res_message = res.message;
                 } else {
                     res_message = 'Something went wrong!';
                 }
 
                 toastr.error(res_message);
             }
         }).catch(function (error) {
             toastr.error('Something went wrong! Contact support');
         });
     });
 });
 
 
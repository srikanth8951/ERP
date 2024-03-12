/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: standard_operating_procedure js
 */

var appstandard_operating_procedure = {};

$(function () {
    const listPayment_term = $('#standard_operating_procedure--deatils--area');
    const lengthContainer = listPayment_term.find('[data-jy-length="record"]');
    const searchContainer = listPayment_term.find('[data-jy-search="record"]');
    const tableContainer = listPayment_term.find('[data-container="standardOperatingProcedureListArea"]');

    const listContainer = tableContainer.find('[data-container="standardOperatingProcedureTlistArea"]');
    const listPagination = tableContainer.find('[data-pagination="standardOperatingProcedureTlistArea"]');
    const standardOperatingProcedureForm = $('#standardOperatingProcedureForm');
    var standardOperatingProcedureFormValidator;
    const standardOperatingProcedureModal = $('#standardOperatingProcedureModal');
    const btnResetstandardOperatingProcedureForm = $('#btn-reset-standard-operating-procedure-form');
    listPagination.find('.list-pagination').html('');
    listPagination.find('.list-pagination-label').html('');

    // Add standard_operating_procedure
    $('#btn-add-standard-operating-procedure').click(function (e) {
        e.preventDefault();

        btnResetstandardOperatingProcedureForm.show();    // Show reset button
        standardOperatingProcedureForm.attr('action', formApiUrl('admin/standard_operating_procedure/add'));
        standardOperatingProcedureModal.find('.modal-header .modal-title').html('Add standard Operating Procedure');
        standardOperatingProcedureModal.modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
    });

    window.resetstandardOperatingProcedureForm = function (resetAction = true) {

        if (resetAction == true) {
            standardOperatingProcedureForm.attr('action', '');    // Form Attribute
        }

        standardOperatingProcedureForm[0].reset(); // Form
        standardOperatingProcedureForm.find('[data-toggle="select2"]')
            .prop('disabled', false)
            .val(null)
            .trigger('change'); // Select2
        standardOperatingProcedureFormValidator.resetForm();   // Jquery validation 
        tinymce.get('tinyEditorElm').setContent('');
    }

    // Form reset button
    btnResetstandardOperatingProcedureForm.click(function (e) {
        e.preventDefault();
        resetstandardOperatingProcedureForm(false);
    });

    // Modal Form close
    standardOperatingProcedureModal.find('[data-dismiss="modal"]').click(function () {
        resetstandardOperatingProcedureForm();
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
                    if (res.standard_operating_procedures) {
                        listContainer.html('');
                        var details = res.standard_operating_procedures;
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
                                    '<td>' +
                                        '<a href="javascript:void(0)" data-standard-operating-procedure="' + listVal.standard_operating_procedure_id + '" class="text-white btn btn-sm btn-dark waves-effect waves-light btn-edit-standard-operating-procedure mr-1" data-toggle="tooltip" title="Edit"><i class="mdi mdi-pencil"></i></a>' +
                                        '<a href="javascript:void(0)" data-standard-operating-procedure="' + listVal.standard_operating_procedure_id + '" class="text-white btn btn-sm btn-danger waves-effect waves-light btn-delete-standard-operating-procedure mr-1" data-toggle="tooltip" title="Delete"><i class="mdi mdi-delete"></i></a>' +
                                    '</td>' +
                                    '</tr>');
                            });

                            listContainer.find('[data-toggle="tooltip"]').tooltip();    // Load tooltip
                            listPagination.find('.list-pagination-label').html(`Showing ${pagination.start} to ${(parseInt(pagination.start) - 1) + pagination.records} of ${pagination.total}`);
                            listPagination.find('.list-pagination').pagination({
                                items: parseInt(pagination.total),
                                itemsOnPage: parseInt(pagination.length),
                                currentPage: Math.ceil(parseInt(pagination.start) / parseInt(pagination.length)),
                                displayedPages: 3,
                                navStyle: 'pagination',
                                listStyle: 'page-item',
                                linkStyle: 'page-link',
                                onPageClick: function (pageNumber, event) {
                                    var page_link = formApiUrl('admin/standard_operating_procedure/list', { start: ((parseInt(pagination.length) * (pageNumber - 1)) + 1) });
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
        loadDetails(formApiUrl('admin/standard_operating_procedure/list'));  // Load standard_operating_procedure details
    });

    searchContainer.submit(function (e) {
        e.preventDefault();
        loadDetails(formApiUrl('admin/standard_operating_procedure/list'));  // Load standard_operating_procedure details
    });

    // standard_operating_procedure Form
    standardOperatingProcedureFormValidator = standardOperatingProcedureForm.validate({
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
            }
        },
        messages: {
            title: {
                required: 'Specify title',
                minlength: 'Specify atleast 3 characters'
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

    function saveStandardOperatingProcedure() {
        let loadSwal;
        var formData = new FormData(standardOperatingProcedureForm[0]);

        $.ajax({
            url: standardOperatingProcedureForm.attr('action'),
            type: 'post',
            data: {
                title: formData.get('title'),
                description: tinymce.get('tinyEditorElm').getContent()
            },
            dataType: 'json',
            headers: {
                Authorization: `Bearer ${wapLogin.getToken()}`
            },
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
                loadDetails(formApiUrl('admin/standard_operating_procedure/list'));  // Load standard_operating_procedure details
                toastr.success(res.message);
                standardOperatingProcedureModal.modal('hide');    // Hide modal
                resetstandardOperatingProcedureForm();    // Reset form
            } else if (res.status == 'error') {
                if (typeof res.message == 'object' && Object.keys(res.message).length > 0) {
                    for (let [field, message] of Object.entries(res.message)) {
                        toastr.error(message, field);
                    }
                } else {
                    toastr.error(res.message);
                }
                
            } else {
                toastr.error('No response status!', 'Error');
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            toastr.error(`${textStatus} <br />${errorThrown}`, 'Error');
        });
    }

    standardOperatingProcedureForm.submit(function (e) {
        e.preventDefault();
        if (standardOperatingProcedureFormValidator.valid()) {
            saveStandardOperatingProcedure();
        }

    });

    // Edit standard_operating_procedure
    $(listContainer).on('click', '.btn-edit-standard-operating-procedure', function (e) {
        e.preventDefault();

        btnResetstandardOperatingProcedureForm.hide();    // Hide button

        var standard_operating_procedure_id = $(this).attr('data-standard-operating-procedure');
        $.ajax({
            url: formApiUrl('admin/standard_operating_procedure/detail', { id: standard_operating_procedure_id }),
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
                if (res.standard_operating_procedure) {
                    var sop = res.standard_operating_procedure;

                    // Set standard_operating_procedure Info
                    standardOperatingProcedureForm.attr('action', formApiUrl('admin/standard_operating_procedure/edit', { id: sop.standard_operating_procedure_id }));
                    standardOperatingProcedureForm.find('[name="title"]').val(sop.title);
                    tinymce.get('tinyEditorElm').setContent(sop.description, { format: 'html' });
                    //  standardOperatingProcedureForm.find('[name="standard_operating_procedure_description"]').val(sop.description);
                    standardOperatingProcedureModal.find('.modal-header .modal-title').html('Edit standard operating procedure');

                    // Show standard_operating_procedure modal
                    standardOperatingProcedureModal.modal({
                        backdrop: 'static',
                        keyboard: false,
                        show: true
                    });
                } else {
                    toastr.error('No standard_operating_procedure available');
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

    // Delete standard_operating_procedure
    $(listContainer).on('click', '.btn-delete-standard-operating-procedure', function (e) {
        e.preventDefault();
        var standard_operating_procedure_id = $(this).attr('data-standard-operating-procedure');
        Swal.fire({
            icon: 'question',
            title: 'Are you sure to delete standard operating procedure',
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
                    url: formApiUrl('admin/standard_operating_procedure/delete', { id: standard_operating_procedure_id }),
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
                        loadDetails(formApiUrl('admin/standard_operating_procedure/list'));  // Load standard_operating_procedure details
                        toastr.success(res.message);
                        standardOperatingProcedureModal.modal('hide');    // Reset form
                        resetstandardOperatingProcedureForm();
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


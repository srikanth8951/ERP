/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: region js
 */

var appregion = {};

$(function () {
    const listRegion = $('#region--deatils--area');
    const lengthContainer = listRegion.find('[data-jy-length="record"]');
    const searchContainer = listRegion.find('[data-jy-search="record"]');
    const tableContainer = listRegion.find('[data-container="regionListArea"]');

    const listContainer = tableContainer.find('[data-container="regionTlistArea"]');
    const listPagination = tableContainer.find('[data-pagination="regionTlistArea"]');
    const regionForm = $('#regionForm');
    var regionFormValidator;
    const regionModal = $('#regionModal');
    const btnResetregionForm = $('#btn-reset-region-form');
    listPagination.find('.list-pagination').html('');
    listPagination.find('.list-pagination-label').html('');

    // Add region
    $('#btn-add-region').click(function (e) {
        e.preventDefault();

        btnResetregionForm.show();    // Show reset button
        regionForm.attr('action', formApiUrl('admin/region/add'));
        regionModal.find('.modal-header .modal-title').html('Add region');
        regionModal.modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
    });

    window.resetregionForm = function (resetAction = true) {

        if (resetAction == true) {
            regionForm.attr('action', '');    // Form Attribute
        }

        regionForm[0].reset(); // Form
        regionForm.find('[data-toggle="select2"]')
            .prop('disabled', false)
            .val(null)
            .trigger('change'); // Select2
        regionFormValidator.resetForm();   // Jquery validation 
    }

    // Form reset button
    btnResetregionForm.click(function (e) {
        e.preventDefault();
        resetregionForm(false);
    });

    // Modal Form close
    regionModal.find('[data-dismiss="modal"]').click(function () {
        resetregionForm();
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
                    if (res.regions) {
                        listContainer.html('');
                        var details = res.regions;
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
                                    '<td>' + listVal.name + '</td>' +
                                    '<td>' + listVal.code + '</td>' +
                                    '<td>' +
                                    '<a href="javascript:void(0)" data-region="' + listVal.region_id + '" class="text-white btn btn-sm btn-dark waves-effect waves-light btn-edit-region mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit region"><i class="mdi mdi-pencil"></i></a>' +

                                    '<a href="javascript:void(0)" data-region="' + listVal.region_id + '" class="text-white btn btn-sm btn-danger waves-effect waves-light btn-delete-region mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="mdi mdi-delete"></i></a>' +
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
                                    var page_link = formApiUrl('admin/region/list', { start: ((parseInt(pagination.length) * (pageNumber - 1)) + 1)});
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
        loadDetails(formApiUrl('admin/region/list'));  // Load region details
    });

    searchContainer.submit(function (e) {
        e.preventDefault();
        loadDetails(formApiUrl('admin/region/list'));  // Load region details
    });

    // region Form
    regionFormValidator = regionForm.validate({
        onkeyup: function (element) {
            $(element).valid();
        },
        onclick: function (element) {
            $(element).valid();
        },
        rules: {
            region_name: {
                required: true,
                // minlength: 3
            },
            region_code: {
                required: true,
            }
        },
        messages: {
            region_name: {
                required: 'Specify region name',
                // minlength: 'Specify atleast 3 characters'
            },
            region_code: {
                required: 'Specify region code'
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

    function saveregion() {
        let loadSwal;
        var formData = new FormData(regionForm[0]);

        $.ajax({
            url: regionForm.attr('action'),
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
                loadDetails(formApiUrl('admin/region/list'));  // Load region details
                toastr.success(res.message);
                regionModal.modal('hide');    // Hide modal
                resetregionForm();    // Reset form
            } else if (res.status == 'error') {
                toastr.error(res.message);
            } else {
                toastr.error('No response status!', 'Error');
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            toastr.error(`${textStatus} <br />${errorThrown}`, 'Error');
        });
    }

    regionForm.submit(function (e) {
        e.preventDefault();
        if (regionFormValidator.valid()) {
            saveregion();
        }

    });

    // Edit region
    $(listContainer).on('click', '.btn-edit-region', function (e) {
        e.preventDefault();

        btnResetregionForm.hide();    // Hide button

        var region_id = $(this).attr('data-region');
        $.ajax({
            url: formApiUrl('admin/region/detail', { region_id: region_id }),
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
                if (res.region) {
                    var region = res.region;

                    // Set region Info
                    regionForm.attr('action', formApiUrl('admin/region/edit', { region_id: region.region_id }));
                    regionForm.find('[name="region_name"]').val(region.name);
                    regionForm.find('[name="region_code"]').val(region.code);
                    regionModal.find('.modal-header .modal-title').html('Edit region');

                    // Show region modal
                    regionModal.modal({
                        backdrop: 'static',
                        keyboard: false,
                        show: true
                    });
                } else {
                    toastr.error('No region available');
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

    // Delete region
    $(listContainer).on('click', '.btn-delete-region', function (e) {
        e.preventDefault();
        var region_id = $(this).attr('data-region');
        Swal.fire({
            icon: 'question',
            title: 'Are you sure to delete region',
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
                    url: formApiUrl('admin/region/delete', { region_id: region_id }),
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
                        loadDetails(formApiUrl('admin/region/list'));  // Load region details
                        toastr.success(res.message);
                        regionModal.modal('hide');    // Reset form
                        resetregionForm();
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


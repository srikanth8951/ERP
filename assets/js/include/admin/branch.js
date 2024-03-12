/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: branch js
 */

var appbranch = {};

$(function () {
    const listBranch = $('#branch--deatils--area');
    const lengthContainer = listBranch.find('[data-jy-length="record"]');
    const searchContainer = listBranch.find('[data-jy-search="record"]');
    const tableContainer = listBranch.find('[data-container="branchListArea"]');

    const listContainer = tableContainer.find('[data-container="branchTlistArea"]');
    const listPagination = tableContainer.find('[data-pagination="branchTlistArea"]');
    const branchForm = $('#branchForm');
    var branchFormValidator;
    const branchModal = $('#branchModal');
    const btnResetbranchForm = $('#btn-reset-branch-form');
    listPagination.find('.list-pagination').html('');
    listPagination.find('.list-pagination-label').html('');

    // Add branch
    $('#btn-add-branch').click(function (e) {
        e.preventDefault();

        loadAutocompleteRegions(); // Load autocomplete for region
        btnResetbranchForm.show();    // Show reset button
        branchForm.attr('action', formApiUrl('admin/branch/add'));
        branchModal.find('.modal-header .modal-title').html('Add branch');
        branchModal.modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
    });

    window.resetbranchForm = function (resetAction = true) {

        if (resetAction == true) {
            branchForm.attr('action', '');    // Form Attribute
        }

        branchForm[0].reset(); // Form
        branchForm.find('[data-toggle="select2"]')
            .prop('disabled', false)
            .val(null)
            .trigger('change'); // Select2
        branchFormValidator.resetForm();   // Jquery validation 
    }

    // Form reset button
    btnResetbranchForm.click(function (e) {
        e.preventDefault();
        resetbranchForm(false);
    });

    // Modal Form close
    branchModal.find('[data-dismiss="modal"]').click(function () {
        resetbranchForm();
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
                    if (res.branches) {
                        listContainer.html('');
                        var details = res.branches;
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
                                    "<td>" + listVal.region_name + "</td>" +
                                    '<td>' +
                                    '<a href="javascript:void(0)" data-branch="' + listVal.branch_id + '" class="text-white btn btn-sm btn-dark waves-effect waves-light btn-edit-branch mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit branch"><i class="mdi mdi-pencil"></i></a>' +

                                    '<a href="javascript:void(0)" data-branch="' + listVal.branch_id + '" class="text-white btn btn-sm btn-danger waves-effect waves-light btn-delete-branch mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="mdi mdi-delete"></i></a>' +
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
                                    var page_link = formApiUrl('admin/branch/list', { start: ((parseInt(pagination.length) * (pageNumber - 1)) + 1)});
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
        loadDetails(formApiUrl('admin/branch/list'));  // Load branch details
    });

    searchContainer.submit(function (e) {
        e.preventDefault();
        loadDetails(formApiUrl('admin/branch/list'));  // Load branch details
    });

    // region Autocomplete
    window.loadAutocompleteRegions = function (options = {}) {
        var selected;
        var regionSelectbox = branchForm.find('[name="region_id"]');

        if (parseValue(options.selected) != '') {
            selected = (Object.keys(options.selected).length > 0) ? Object.values(options.selected) : [];
        } else {
            selected = [];
        }

        regionSelectbox.html("").trigger("change"); // Reset selectbox
        $.ajax({
            url: formApiUrl("admin/region/autocomplete", options),
            type: "get",
            dataType: "json",
            headers: {
                Authorization: `Bearer ${wapLogin.getToken()}`,
            }
        }).done((res) => {
            regionSelectbox.append(new Option("Select", "", false, false)); // Load initial select
            if (res.status == "success") {
                if (res.regions) {
                    var regions = res.regions;
                    var regionOption;

                    $.each(regions, function (bi, region) {
                        if (selected.find((value) => {
                            return value == region.id
                        })) {
                            regionOption = new Option(region.name, region.id, true, true);
                        } else {
                            regionOption = new Option(region.name, region.id, false, false);
                        }

                        regionSelectbox.append(regionOption);
                    });
                    regionSelectbox.trigger("change");
                }
            } else if (res.status == "error") {
                console.log(res.message);
            } else {
                console.log("region Autocomlete: Something went wrong!");
            }
        })
            .fail((xhr, ajaxOptions, errorThrown) => {
                console.log(xhr.responseText + " " + xhr.responseText);
            });
    };

    // branch Form
    branchFormValidator = branchForm.validate({
        onkeyup: function (element) {
            $(element).valid();
        },
        onclick: function (element) {
            $(element).valid();
        },
        rules: {
            region_id: {
                required: true
            },
            branch_name: {
                required: true,
                // minlength: 3
            },
            branch_code: {
                required: true,
            }
        },
        messages: {
            region_id: {
                required: 'Select region'
            },
            branch_name: {
                required: 'Specify branch name',
                // minlength: 'Specify atleast 3 characters'
            },
            branch_code: {
                required: 'Specify branch code'
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

    function savebranch() {
        let loadSwal;
        var formData = new FormData(branchForm[0]);

        $.ajax({
            url: branchForm.attr('action'),
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
                loadDetails(formApiUrl('admin/branch/list'));  // Load branch details
                toastr.success(res.message);
                branchModal.modal('hide');    // Hide modal
                resetbranchForm();    // Reset form
            } else if (res.status == 'error') {
                toastr.error(res.message);
            } else {
                toastr.error('No response status!', 'Error');
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            toastr.error(`${textStatus} <br />${errorThrown}`, 'Error');
        });
    }

    branchForm.submit(function (e) {
        e.preventDefault();
        if (branchFormValidator.valid()) {
            savebranch();
        }

    });

    // Edit branch
    $(listContainer).on('click', '.btn-edit-branch', function (e) {
        e.preventDefault();

        btnResetbranchForm.hide();    // Hide button

        var branch_id = $(this).attr('data-branch');
        $.ajax({
            url: formApiUrl('admin/branch/detail', { branch_id: branch_id }),
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
                if (res.branch) {
                    var branch = res.branch;

                    // Set branch Info
                    branchForm.attr('action', formApiUrl('admin/branch/edit', { branch_id: branch.branch_id }));

                    // Regions selectbox with selected value
                    loadAutocompleteRegions({
                        'selected': [branch.region_id]
                    });


                    branchForm.find('[name="branch_name"]').val(branch.name);
                    branchForm.find('[name="branch_code"]').val(branch.code);
                    branchModal.find('.modal-header .modal-title').html('Edit branch');

                    // Show branch modal
                    branchModal.modal({
                        backdrop: 'static',
                        keyboard: false,
                        show: true
                    });
                } else {
                    toastr.error('No branch available');
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

    // Delete branch
    $(listContainer).on('click', '.btn-delete-branch', function (e) {
        e.preventDefault();
        var branch_id = $(this).attr('data-branch');
        Swal.fire({
            icon: 'question',
            title: 'Are you sure to delete branch',
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
                    url: formApiUrl('admin/branch/delete', { branch_id: branch_id }),
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
                        loadDetails(formApiUrl('admin/branch/list'));  // Load branch details
                        toastr.success(res.message);
                        branchModal.modal('hide');    // Reset form
                        resetbranchForm();
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
            url: formApiUrl('admin/branch/downloadSample'),
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
                    let fileName = 'branch-' + moment().format('DD/MM/YYYY') + '.xlsx';
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


/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: request js
 */

 var apprequest = {};

 $(function () {
     const listRequest = $('#request--deatils--area');
     const lengthContainer = listRequest.find('[data-jy-length="record"]');
     const searchContainer = listRequest.find('[data-jy-search="record"]');
     const tableContainer = listRequest.find('[data-container="requestListArea"]');
 
     const listContainer = tableContainer.find('[data-container="requestTlistArea"]');
     const listPagination = tableContainer.find('[data-pagination="requestTlistArea"]');
     listPagination.find('.list-pagination').html('');
     listPagination.find('.list-pagination-label').html('');
 
 
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
                     if (res.product_requests) {
                         listContainer.html('');
                         var details = res.product_requests;
                         var pagination = res.pagination;
                         let actionBtnApprove = '';
                         let actionBtnReject = '';
                         if (details.length && pagination.total > 0) {
                             let status_badge_class = '';
                             $.each(details, function (listIn, listVal) {

                                 if (listVal.status.id == 1 || listVal.status.id == 3 || listVal.status.id == 5 || listVal.status.id == 4 || listVal.status.id == 6) {
                                    if (listVal.status.id == 1) {
                                        actionBtnApprove = '<button class="text-white btn btn-sm btn-success waves-effect waves-light mr-1" title="Approve" id="btn-request-approval" data-request-id="' + listVal.request_id + '"><i class="mdi mdi-check"></i></button>';
                                        actionBtnReject = '<button class="text-white btn btn-sm btn-danger waves-effect waves-light mr-1" title="Reject" id="btn-request-reject" data-request-id="' + listVal.request_id + '"><i class="mdi mdi-close"></i></button>';
                                    } else if (listVal.status.id != 3 && listVal.status.id != 5 && listVal.status.id != 4 && listVal.status.id != 6) {
                                        actionBtnApprove = '<button class="text-white btn btn-sm btn-success waves-effect waves-light mr-1" title="Approve" id="btn-request-approval" data-request-id="' + listVal.request_id + '"><i class="mdi mdi-check"></i></button>';
                                        actionBtnReject = '<button class="text-white btn btn-sm btn-danger waves-effect waves-light mr-1" title="Reject" id="btn-request-reject" data-request-id="' + listVal.request_id + '"><i class="mdi mdi-close"></i></button>';
                                    } else {
                                        actionBtnApprove = '';
                                        actionBtnReject = '';
                                     }
                                 } else {
                                    actionBtnApprove = "";
                                    actionBtnReject = "";
                                }

                                 let url = formUrl('employee/asd/store_request/edit/' + listVal.request_id)
                                 listContainer.append('<tr>' +
                                     '<td>' + listVal.request_number + '</td>' +
                                     '<td>' + listVal.title + '</td>' +
                                     '<td>' + listVal.status.name + '</td>' +
                                     '<td>' + listVal.enginner_name + '</td>' +
                                     '<td>' + listVal.created_datetime + '</td>' +
                                     '<td>' +
                                     '<a href="' + formUrl('employee/asd/store_request/view/' + listVal.request_id) + '" class="text-white btn btn-sm btn-info waves-effect waves-light mr-1" title="View" data-toggle="tooltip" data-placement="top" title=""><i class="mdi mdi-eye"></i></a>' +
                                        actionBtnApprove +
                                        actionBtnReject +
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
                                     var page_link = formApiUrl('employee/asd/store_product/request/list', { start: ((parseInt(pagination.length) * (pageNumber - 1)) + 1)});
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
         loadDetails(formApiUrl('employee/asd/store_product/request/list'));  // Load request details
     });
 
     searchContainer.submit(function (e) {
         e.preventDefault();
         loadDetails(formApiUrl('employee/asd/store_product/request/list'));  // Load request details
     });

     //Request Approval
     $(listContainer).on('click', "#btn-request-approval", function (e) {
        e.preventDefault();
        var request_id = $(this).attr('data-request-id');
    
        Swal.fire({
            icon: 'question',
            title: 'Are you sure to Approve ',
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
                    url: formApiUrl('employee/asd/store_product/request/approval', { request_id: request_id }),
                    type: 'post',
                    dataType: 'json',
                    data: { status: status },
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
                        loadDetails(formApiUrl('employee/asd/store_product/request/list'));  // Load employee details
                        toastr.success(res.message);
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

     //Request Reject
     $(listContainer).on('click', '#btn-request-reject', function (e) {
        e.preventDefault();
        var request_id = $(this).attr('data-request-id');
       
        Swal.fire({
            icon: 'question',
            title: 'Are you sure to Reject ',
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
                    url: formApiUrl('employee/asd/store_product/request/reject', { request_id: request_id }),
                    type: 'post',
                    dataType: 'json',
                    data: { status: status },
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
                        loadDetails(formApiUrl('employee/asd/store_product/request/list'));  // Load employee details
                        toastr.success(res.message);
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
 
 
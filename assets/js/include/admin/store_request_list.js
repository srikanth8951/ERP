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
                         if (details.length && pagination.total > 0) {
                             let status_badge_class = '';
                             $.each(details, function (listIn, listVal) {
                                 if (listVal.status == 1) {
                                     status_badge_class = 'badge-success';
                                 } else {
                                     status_badge_class = 'badge-danger';
                                 }
                                 let url = formUrl('admin/store/requests/edit/' + listVal.request_id)
                                 listContainer.append('<tr>' +
                                     '<td>' + listVal.request_number + '</td>' +
                                     '<td>' + listVal.title + '</td>' +
                                     '<td>' + listVal.status.name + '</td>' +
                                     '<td>' + listVal.enginner_name + '</td>' +
                                     '<td>' + listVal.created_datetime + '</td>' +
                                     '<td>' +
                                     '<a href="'+formUrl('admin/store/requests/view/' + listVal.request_id)+'" class="text-white btn btn-sm btn-danger waves-effect waves-light mr-1" data-toggle="tooltip" data-placement="top" title="">View</a>' +
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
                                     var page_link = formApiUrl('admin/store/product/request/list', { start: ((parseInt(pagination.length) * (pageNumber - 1)) + 1)});
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
         loadDetails(formApiUrl('admin/store/product/request/list'));  // Load request details
     });
 
     searchContainer.submit(function (e) {
         e.preventDefault();
         loadDetails(formApiUrl('admin/store/product/request/list'));  // Load request details
     });

 });
 
 
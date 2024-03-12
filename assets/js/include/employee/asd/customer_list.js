/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: Customer js
 */

 var appCustomer= {};

 $(function () {
     const searchForm = $('#searchForm');
     const listArea = $('#customer--deatils--area');
     const listContainer = listArea.find('[data-container="CustomerArea"]');
     const listPagination = listArea.find('[data-pagination="CustomerArea"]');
     listPagination.find(".list-pagination").html("");
     listPagination.find(".list-pagination-label").html("");
 
     window.loadCustomerDetail = function () {
         listContainer.html('');
         listPagination.find(".list-pagination").html("");
         listPagination.find(".list-pagination-label").html("");
         listContainer.append(`<div class="col-md-12">
             <div class="card m-b-20 card-body" style="padding: 0.75rem;">
                 <div class="row align-items-center ">
                     <div class="col text-center"><h6>No Customer available!</h6></div>
                 </div>
             </div>
         </div>`);
     }
 
     window.loadCustomerDetails = function (href) {
         let loadSwal; let newUrl = href;
         var Url = new URL(href);
         if (parseValue(searchForm.find('[name="search"]').val()) != '') {
             Url.searchParams.set('search', searchForm.find('[name="search"]').val());
             newUrl = Url.toString();
         }
         
         $.ajax({
             url: newUrl,
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
             success: function (res) {
                 if (res.status == 'success') {
                     if (res.customers) {
                         listContainer.html('');
                         var details = res.customers.data;
                         var pagination = res.customers.pagination;
                         if (details.length && pagination.total > 0) {
                             let status_badge_class = '';
                             $.each(details, function (listIn, listVal) {
                                 if (listVal.status == 1) {
                                     status_badge_class = 'badge-success';
                                 } else {
                                     status_badge_class = 'badge-danger';
                                 }
 
                                 var customerViewLink = formUrl('employee/asd/customer/view/' + listVal.customer_id);
                                 listContainer.append(`<div class="col-md-12">
                                     <div class="card m-b-20 card-body" style="padding: 0.75rem;">
                                         <div class="row align-items-center ">
                                             <div class="col-md-3 clickable" data-link="${customerViewLink}"><h6>${listVal.company_name}</h6></div>
                                             <div class="col-md-3 clickable" data-link="${customerViewLink}"> Name : ${listVal.billing_address_contact_name}</div>
                                             <div class="col-md-3 clickable" data-link="${customerViewLink}"> Email : ${listVal.billing_address_email}</div>
                                             <div class="col-md-3"> 
                                                <div class="text-center">
                                                    <a href="${customerViewLink}"><i class="fa fa-address-card-o text-info"></i></a>
                                                </div>
                                             </div>
                                         </div>
                                     </div>
                                 </div>`);
                             });
                            $('.clickable').click(function() { 
                                location.href = $(this).data('link');
                            });
                             listContainer.find('[data-toggle="tooltip"]').tooltip();    // Load tooltip
                             listPagination.find(".list-pagination-label")
                                 .html(`Showing ${pagination.start} to ${(parseInt(pagination.start) -1) + pagination.records} of ${pagination.total}`);
                             listPagination.find(".list-pagination").pagination({
                                 items: parseInt(pagination.total),
                                 itemsOnPage: parseInt(pagination.length),
                                 currentPage: Math.ceil(parseInt(pagination.start) / parseInt(pagination.length)),
                                 displayedPages: 3,
                                 navStyle: 'pagination',
                                 listStyle: 'page-item',
                                 linkStyle: 'page-link',
                                 onPageClick: function (pageNumber, event) {
                                     var page_link = formApiUrl('employee/asd/customer/list', { start: ((parseInt(pagination.length) * (pageNumber - 1)) + 1)});
                                     loadCustomerDetails(page_link);
                                 }
                             });
 
                         } else {
                             loadCustomerDetail();
                         }
                     } else {
                         loadCustomerDetail();
                     }
                 } else if (res.status == 'error') {
                    //  toastr.error(res.message);
                     loadCustomerDetail();
                 } else {
                     toastr.error('No response status!', 'Error');
                     loadCustomerDetail();
                 }
             },
             error: function (xhr, textStatus, errorThrown) {
                 toastr.error(`${textStatus} <br />${errorThrown}`, 'Error');
                 loadCustomerDetail();
             },
             complete: function () {
                 loadSwal.close();
             }
         });
     }
 
     searchForm.submit(function (e) {
         e.preventDefault();
         loadCustomerDetails(formApiUrl('employee/asd/customer/list'));  // Load customer details
     });

 });
 
 
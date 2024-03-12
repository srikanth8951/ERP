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

                                 var status;
                                 if(listVal.status == 1){
                                     status = `checked`;
                                 } else {
                                     status = ``;
                                 }
 
                                 var customerViewLink = formUrl('admin/customer/view/' + listVal.customer_id);
                                 var customerEditLink = formUrl('admin/customer/edit/' + listVal.customer_id);
                                 listContainer.append(`<div class="col-md-12">
                                     <div class="card m-b-20 card-body" style="padding: 0.75rem;">
                                         <div class="row align-items-center ">
                                             <div class="col-md-3 clickable" data-link="${customerViewLink}"><h6>${listVal.company_name}</h6></div>
                                             <div class="col-md-3 clickable" data-link="${customerViewLink}"> Name : ${listVal.billing_address_contact_name}</div>
                                             <div class="col-md-3 clickable" data-link="${customerViewLink}"> Email : ${listVal.billing_address_email}</div>
                                             <div class="col-md-2"> <input type="checkbox" data-customer="${listVal.customer_id}" id="switch${listVal.customer_id}" class="btn-switch" switch="bool" value="${listVal.status}" ${status}/>
                                             <label for="switch${listVal.customer_id}" class="m-0"></label></div>
                                             <div class="col-md-1"> 
                                             <div class="float-right">
                                                 <a href="${customerViewLink}"><i class="fa fa-address-card-o text-info"></i></a>
                                                 <a href="${customerEditLink}" class=""><i class="fa fa-pencil-square-o text-success" aria-hidden="true"></i></a>
                                                 <a href="javascript:void(0);" class="btn-delete-customer" data-customer="${listVal.customer_id}"><i class="fa fa-trash text-danger"></i></a>
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
                                     var page_link = formApiUrl('admin/customer/list', { start: ((parseInt(pagination.length) * (pageNumber - 1)) + 1)});
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
         loadCustomerDetails(formApiUrl('admin/customer/list'));  // Load customer details
     });

     //Status value
     $(listContainer).on('click', '.btn-switch', function (e) {
        e.preventDefault();
        var customer_id = $(this).attr('data-customer');
        let status, status_lable;
         
        if($(this).val() == 1){
            status = 0;
            status_lable = 'Inactive'
        } else {
            status = 1;
            status_lable = 'Active'
        }
        Swal.fire({
            icon: 'question',
            title: 'Are you sure to change customer Status to ' + status_lable,
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
                    url: formApiUrl('admin/customer/status/update', { customer_id: customer_id }),
                    type: 'post',
                    dataType: 'json',
                    data: {status: status},
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
                        loadCustomerDetails(formApiUrl('admin/customer/list'));  // Load employee details
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
 
     // Delete customer
     $(listContainer).on('click', '.btn-delete-customer', function (e) {
         e.preventDefault();
         var customer_id = $(this).attr('data-customer');
         Swal.fire({
             icon: 'question',
             title: 'Are you sure to delete customer',
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
                     url: formApiUrl('admin/customer/delete', { customer_id: customer_id }),
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
                         loadCustomerDetails(formApiUrl('admin/customer/list'));  // Load customer details
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

     $('#btn-download-upload-sample').click(function (e) {
        e.preventDefault();
        let loadSwal;
        const invForm = $(this);

        $.ajax({
            url: formApiUrl('admin/customer/downloadSample'),
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
                    let fileName = 'customer-' + moment().format('DD/MM/YYYY') + '.xlsx';
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
 
 
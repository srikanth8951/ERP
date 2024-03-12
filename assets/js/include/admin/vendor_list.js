/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: Vendor js
 */

 var appVendor= {};

 $(function () {
     const searchForm = $('#searchForm');
     const listArea = $('#vendor--deatils--area');
     const listContainer = listArea.find('[data-container="VendorArea"]');
     const listPagination = listArea.find('[data-pagination="VendorArea"]');
     listPagination.find(".list-pagination").html("");
     listPagination.find(".list-pagination-label").html("");
 
     window.loadVendorDetail = function () {
         listContainer.html('');
         listPagination.find(".list-pagination").html("");
         listPagination.find(".list-pagination-label").html("");
         listContainer.append(`<div class="col-md-12">
             <div class="card m-b-20 card-body" style="padding: 0.75rem;">
                 <div class="row align-items-center ">
                     <div class="col text-center"><h6>No Vendor available!</h6></div>
                 </div>
             </div>
         </div>`);
     }
 
     window.loadVendorDetails = function (href) {
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
                     if (res.vendors) {
                         listContainer.html('');
                         var details = res.vendors.data;
                         var pagination = res.vendors.pagination;
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
 
                                 var vendorViewLink = formUrl('admin/vendor/view/' + listVal.vendor_id);
                                 var vendorEditLink = formUrl('admin/vendor/edit/' + listVal.vendor_id);
                                 listContainer.append(`<div class="col-md-12">
                                     <div class="card m-b-20 card-body" style="padding: 0.75rem;">
                                         <div class="row align-items-center ">
                                             <div class="col-md-3 clickable" data-link="${vendorViewLink}"><h6>${listVal.organization_name}</h6></div>
                                             <div class="col-md-3 clickable" data-link="${vendorViewLink}"> Name : ${listVal.contact_name}</div>
                                             <div class="col-md-3 clickable" data-link="${vendorViewLink}"> Email : ${listVal.email}</div>
                                             <div class="col-md-2"> <input type="checkbox" data-vendor="${listVal.vendor_id}" id="switch${listVal.vendor_id}" class="btn-switch" switch="bool" value="${listVal.status}" ${status}/>
                                             <label for="switch${listVal.vendor_id}" class="m-0"></label></div>
                                             <div class="col-md-1"> 
                                             <div class="float-right">
                                                 <a href="${vendorViewLink}"><i class="fa fa-address-card-o text-info"></i></a>
                                                 <a href="${vendorEditLink}" class=""><i class="fa fa-pencil-square-o text-success" aria-hidden="true"></i></a>
                                                 <a href="javascript:void(0);" class="btn-delete-vendor" data-vendor="${listVal.vendor_id}"><i class="fa fa-trash text-danger"></i></a>
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
                                     var page_link = formApiUrl('admin/vendor/list', { start: ((parseInt(pagination.length) * (pageNumber - 1)) + 1)});
                                     loadVendorDetails(page_link);
                                 }
                             });
 
                         } else {
                             loadVendorDetail();
                         }
                     } else {
                         loadVendorDetail();
                     }
                 } else if (res.status == 'error') {
                    //  toastr.error(res.message);
                     loadVendorDetail();
                 } else {
                     toastr.error('No response status!', 'Error');
                     loadVendorDetail();
                 }
             },
             error: function (xhr, textStatus, errorThrown) {
                 toastr.error(`${textStatus} <br />${errorThrown}`, 'Error');
                 loadVendorDetail();
             },
             complete: function () {
                 loadSwal.close();
             }
         });
     }
 
     searchForm.submit(function (e) {
         e.preventDefault();
         loadVendorDetails(formApiUrl('admin/vendor/list'));  // Load vendor details
     });

     //Status value
     $(listContainer).on('click', '.btn-switch', function (e) {
        e.preventDefault();
        var vendor_id = $(this).attr('data-vendor');
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
            title: 'Are you sure to change vendor Status to ' + status_lable,
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
                    url: formApiUrl('admin/vendor/status/update', { vendor_id: vendor_id }),
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
                        loadVendorDetails(formApiUrl('admin/vendor/list'));  // Load employee details
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
 
     // Delete vendor
     $(listContainer).on('click', '.btn-delete-vendor', function (e) {
         e.preventDefault();
         var vendor_id = $(this).attr('data-vendor');
         Swal.fire({
             icon: 'question',
             title: 'Are you sure to delete vendor',
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
                     url: formApiUrl('admin/vendor/delete', { vendor_id: vendor_id }),
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
                         loadVendorDetails(formApiUrl('admin/vendor/list'));  // Load vendor details
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
 
 
/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: employee js
 */

 var appEmpSupervisor= {};

 $(function () {
     const searchForm = $('#searchForm');
     const listArea = $('#empsupervisor--deatils--area');
     const listContainer = listArea.find('[data-container="empSupervisorArea"]');
     const listPagination = listArea.find('[data-pagination="empSupervisorArea"]');
     listPagination.find(".list-pagination").html("");
     listPagination.find(".list-pagination-label").html("");
 
     window.loadEmptyDetail = function () {
         listContainer.html('');
         listPagination.find(".list-pagination").html("");
         listPagination.find(".list-pagination-label").html("");
         listContainer.append(`<div class="col-md-12">
             <div class="card m-b-20 card-body" style="padding: 0.75rem;">
                 <div class="row align-items-center ">
                     <div class="col text-center"><h6>No Employee available!</h6></div>
                 </div>
             </div>
         </div>`);
     }
 
     window.loadEmpDetails = function (href) {
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
                     if (res.employees) {
                         listContainer.html('');
                         var details = res.employees.data;
                         var pagination = res.employees.pagination;
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
 
                                 var employeeViewLink = formUrl('admin/supervisor/view/' + listVal.employee_id);
                                 var employeeEditLink = formUrl('admin/supervisor/edit/' + listVal.employee_id);
                                 listContainer.append(`<div class="col-md-12">
                                     <div class="card m-b-20 card-body" style="padding: 0.75rem;">
                                         <div class="row align-items-center ">
                                             <div class="col-md-3"><h6>${listVal.first_name}  ${listVal.last_name}</h6></div>
                                             <div class="col-md-3"> Email : ${listVal.email}</div>
                                             <div class="col-md-3"> Mobile : ${listVal.mobile}</div>
                                             <div class="col-md-2"> <input type="checkbox" data-employee="${listVal.employee_id}" id="switch${listVal.employee_id}" class="btn-switch" switch="bool" value="${listVal.status}" ${status}/>
                                             <label for="switch${listVal.employee_id}" class="m-0"></label></div>
                                             <div class="col-md-1"> 
                                             <div class="float-right">
                                                 <a href="${employeeViewLink}"><i class="fa fa-address-card-o text-info"></i></a>
                                                 <a href="${employeeEditLink}"><i class="fa fa-pencil-square-o text-success" aria-hidden="true"></i></a>
                                                 <a href="javascript:void(0);" class="btn-delete-employee-supervisor" data-employee="${listVal.employee_id}"><i class="fa fa-trash text-danger"></i></a>
                                             </div>
                                             </div>
                                         </div>
                                     </div>
                                 </div>`);
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
                                     var page_link = formApiUrl('admin/employee/supervisor/list', { start: ((parseInt(pagination.length) * (pageNumber - 1)) + 1)});
                                     loadEmpDetails(page_link);
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
                     toastr.error('No response status!', 'Error');
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
 
     searchForm.submit(function (e) {
         e.preventDefault();
         loadEmpDetails(formApiUrl('admin/employee/supervisor/list'));  // Load employee details
     });

     //Status value
     $(listContainer).on('click', '.btn-switch', function (e) {
        e.preventDefault();
        var employee_id = $(this).attr('data-employee');
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
            title: 'Are you sure to change user status to ' + status_lable,
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
                    url: formApiUrl('admin/employee/supervisor/status/update', { employee_id: employee_id }),
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
                        loadEmpDetails(formApiUrl('admin/employee/supervisor/list'));  // Load employee details
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
 
     // designation Autocomplete
     window.loadAutocompleteDesignations = function () {
         var options = {};
         if (appUser.user_type.code == 'designation') {
             let noptions = { designation_id: appUser.designation_id };
             options = $.extend({}, options, noptions);
         }
         designationSelectbox.html('').trigger('change');   // Reset selectbox
         $.ajax({
             url: formApiUrl('admin/designation/autocomplete', options),
             type: 'get',
             dataType: 'json',
         }).done((res) => {
             designationSelectbox.append(new Option('Select', '', false, false)); // Load initial select
             if (res.status == 'success') {
                 if (res.designations) {
                     var designations = res.designations;
 
                     $.each(designations, function (bi, designation) {
                         var designationOption = new Option(designation.name, designation.id, false, false)
                         designationSelectbox.append(designationOption);
                     });
                     designationSelectbox.trigger('change');
                 }
 
             } else if (res.status == 'error') {
                 console.log(res.message);
             } else {
                 console.log('designation Autocomlete: Something went wrong!');
             }
         }).fail((xhr, statusText, errorThrown) => {
             console.log(statusText + ' ' + errorThrown);
         });
     }
 
     // Delete employee
     $(listContainer).on('click', '.btn-delete-employee-supervisor', function (e) {
         e.preventDefault();
         var employee_id = $(this).attr('data-employee');
         Swal.fire({
             icon: 'question',
             title: 'Are you sure to delete employee',
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
                     url: formApiUrl('admin/employee/supervisor/delete', { employee_id: employee_id }),
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
                         loadEmpDetails(formApiUrl('admin/employee/supervisor/list'));  // Load employee details
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
 
 
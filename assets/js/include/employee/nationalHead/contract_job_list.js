/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: ContractJob js
 */

 var appContractJob = {};

 $(function () {
     const searchForm = $('#searchForm');
     const listArea = $('#contractjob--deatils--area');
     const listContainer = listArea.find('[data-container="contractJobArea"]');
     const listPagination = listArea.find('[data-pagination="contractJobArea"]');
     listPagination.find(".list-pagination").html("");
     listPagination.find(".list-pagination-label").html("");
 
     window.loadContractJobDetail = function () {
         listContainer.html('');
         listPagination.find(".list-pagination").html("");
         listPagination.find(".list-pagination-label").html("");
         listContainer.append(`<div class="col-md-12">
              <div class="card m-b-20 card-body" style="padding: 0.75rem;">
                  <div class="row align-items-center ">
                      <div class="col text-center"><h6>No Job available!</h6></div>
                  </div>
              </div>
          </div>`);
     }
 
     window.loadContractJobDetails = function (href) {
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
                     if (res.contract_jobs) {
                         listContainer.html('');
                         var details = res.contract_jobs.data;
                         var pagination = res.contract_jobs.pagination;
                         if (details.length && pagination.total > 0) {
                             let status_badge_class = '';
                             $.each(details, function (listIn, listVal) {
                                 var contractJobActions = ''; var contractJobStatusOption = '';
                                 if (listVal.status == 1) {
                                     status_badge_class = 'badge-success';
                                 } else {
                                     status_badge_class = 'badge-danger';
                                 }
 
                                 var status;
                                 if (listVal.status == 1) {
                                     status = `checked`;
                                 } else {
                                     status = ``;
                                 }
 
                                 // Calculate expiry days
                                 let to_date = moment(listVal.period_todate);
                                 let current_date = moment();
                                 let expiry_days = to_date.diff(current_date, 'seconds');
                                 console.log(expiry_days);
                                 let contractJobStatus = '';
                                 let contractJobStatusColor = '';
 
                                 var contractJobViewLink = formUrl('employee/nationalHead/contract_job/view/' + listVal.contract_job_id);
                                 contractJobActions = `<a href="${contractJobViewLink}" data-toggle="tooltip" title="View Contract/Job" class="mr-2 ml-md-2 mr-md-0 btn-view-contractjob btn btn-sm btn-outline-success waves-light waves-effect"><i class="fa fa-eye" aria-hidden="true"></i></a>`;
                                 
                                 // Check job is parent or not
                                //  if(parseValue(listVal.is_parent) == '') {
                                //      if(expiry_days < 1) {
                                //          contractJobRenewLink = formUrl('employee/nationalHead/renew/' + listVal.contract_job_id);
                                //          contractJobActions += `<a href="${contractJobRenewLink}" data-toggle="tooltip" title="Renew Contract/Job" class="mr-2 ml-md-2 mr-md-0 btn-renew-contractjob btn btn-sm btn-outline-orange waves-light waves-effect"><i class="fa fa-recycle" aria-hidden="true"></i></a>`;
                                //      } else {
                                //          contractJobStatusOption = `<input type="checkbox" data-contractjob="${listVal.contract_job_id}" id="switch${listVal.contract_job_id}" class="btn-switch-contractjob-status" switch="bool" value="${listVal.status}" ${status}/>
                                //          <label for="switch${listVal.contract_job_id}" class="m-0"></label>`;
 
                                //          contractJobUpdateLink = formUrl('employee/nationalHead/update/' + listVal.contract_job_id);
                                //          contractJobActions += `<a href="${contractJobUpdateLink}" data-toggle="tooltip" title="Update Contract/Job" class="mr-2 ml-md-2 mr-md-0 btn-update-contractjob btn btn-sm btn-outline-indigo waves-light waves-effect"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>`;
                                //      }    
                                     
                                //  }
 
                                 if (listVal.contract_status_name == "In Contract") {
                                     contractJobStatusColor = 'text-success'
                                 } else if (listVal.contract_status_name == "Expired") {
                                     contractJobStatusColor = 'text-danger'
                                 }
 
                                 listContainer.append(`<div class="col-md-12">
                                      <div class="card m-b-20 card-body" style="padding: 0.75rem;">
                                         <div class="row align-items-center">
                                             <div class="col-md-10">
                                                 <h3 class="card-title font-20 mt-0">${listVal.job_title}</h3>
                                                 <div class="row">
                                                     <div class="col-md-4">
                                                         Job No : ${listVal.job_number} <br />
                                                         SAP Ref No : ${listVal.sap_job_number}
                                                     </div>
                                                     <div class="col-md-4">
                                                         Nature : ${listVal.contract_nature_name} <br />
                                                         Contract Type : ${listVal.contract_type_name}
                                                     </div>
                                                     <div class="col-md-4">
                                                         PO No. : ${listVal.purchase_order_number} <br />
                                                         Status : <span class="${contractJobStatusColor}">${listVal.contract_status_name}</span>
                                                     </div>
                                                 </div>
                                             </div>
                                             <div class="col-md-2 text-right">
                                             <!--<div class="mb-2"> 
                                                     ${contractJobStatusOption}
                                                 </div> -->
                                                 <div class="mb-2">
                                                     ${contractJobActions}
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
                                     var page_link = formApiUrl('employee/nationalHead/contract_job/list', { start: ((parseInt(pagination.length) * (pageNumber - 1)) + 1)});
                                     loadContractJobDetails(page_link);
                                 }
                             });
 
                         } else {
                             loadContractJobDetail();
                         }
                     } else {
                         loadContractJobDetail();
                     }
                 } else if (res.status == 'error') {
                     toastr.error(res.message);
                     loadContractJobDetail();
                 } else {
                     toastr.error('No response status!', 'Error');
                     loadContractJobDetail();
                 }
             },
             error: function (xhr, textStatus, errorThrown) {
                 toastr.error(`${textStatus} <br />${errorThrown}`, 'Error');
                 loadContractJobDetail();
             },
             complete: function () {
                 loadSwal.close();
             }
         });
     }
 
     searchForm.submit(function (e) {
         e.preventDefault();
         loadContractJobDetails(formApiUrl('employee/nationalHead/contract_job/'));  // Load contract job details
     });
 
     //Status value
     $(listContainer).on('click', '.btn-switch-contractjob-status', function (e) {
         e.preventDefault();
         var contractjob_id = $(this).attr('data-contractjob');
         let status, status_lable;
 
         if ($(this).val() == 1) {
             status = 0;
             status_lable = 'Inactive'
         } else {
             status = 1;
             status_lable = 'Active'
         }
         Swal.fire({
             icon: 'question',
             title: 'Are you sure to change job Status to ' + status_lable,
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
                     url: formApiUrl('employee/nationalHead/status/update', { contract_job_id: contractjob_id }),
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
                         loadContractJobDetails(formApiUrl('employee/nationalHead/contract_job/'));  // Load employee details
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
 
 
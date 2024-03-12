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
 
                                 var contractJobViewLink = formUrl('employee/areaHead/contract_job_log/view/' + listVal.contract_job_id + '/' + contract_job_id);
                                 contractJobActions = `<a href="${contractJobViewLink}" data-toggle="tooltip" title="View Contract/Job" class="mr-2 ml-md-2 mr-md-0 btn-view-contractjob btn btn-sm btn-outline-success waves-light waves-effect"><i class="fa fa-eye" aria-hidden="true"></i></a>`;
 
                                 listContainer.append(`<div class="col-md-12">
                                      <div class="card m-b-20 card-body" style="padding: 0.75rem;">
                                         <div class="row align-items-center">
                                             <div class="col-md-10">
                                                 <h3 class="card-title font-20 mt-0">${listVal.job_title} <span class="small">(Updated On: ${moment(listVal.updated_datetime).format("DD-MM-YYYY")})</span></h3>
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
                                                         Status : <span class="text-success">${listVal.contract_status_name}</span>
                                                     </div>
                                                 </div>
                                             </div>
                                             <div class="col-md-2 text-right">
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
                                     var page_link = formApiUrl('employee/areaHead/contract_job_log/log/list', { start: ((parseInt(pagination.length) * (pageNumber - 1)) + 1)});
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
         loadContractJobDetails(formApiUrl('employee/areaHead/contract_job/log/list', { contract_job_id: contract_job_id }));  // Load contract job details
     });
 
 });
 
 
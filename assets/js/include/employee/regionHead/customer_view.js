/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: employee js
 */

 var appEmpCustomer = {};

 $(function () {
 
     const listArea = $('#empcustomer--deatils--area');
     const newContainer = listArea.find('#empCustomerNewArea');
     const detailContainer = listArea.find('#empCustomerDetailArea');
     
     window.loadEmpDetailView = function (type = '', customer = {}) {
         if (type == 'new') {
             newContainer.fadeIn('slow');
             detailContainer.hide();
         } else if(type == 'detail') {
             detailContainer.fadeIn('slow');
             newContainer.hide();
             console.log(Object.keys(customer).length);
             if (Object.keys(customer).length > 0) {
                 detailContainer.find('[data-employee-detail="customer"]').html(`<div class="card m-b-20">
                     <div class="card-body">
                         <div class="row">
                             <div class="col-5">
                                 <img class="img_cls" src="${formUrl('assets/images/users/avatar.png')}">
                             </div>
                             <div class="col-7 align-self-end">
                                 <b>${customer.company_name}</b><br>
                                 EMP-1001<br>
                                 Customer 
                             </div>
                         </div>
                         <hr />
                         <div class="row">
                             <div class="col-1">
                                 <i class="fa fa-envelope"></i>
                             </div>
                             <div class="col-11">
                                 <div class="pl-2">${customer.billing_address_email ? customer.billing_address_email : ''}</div>
                             </div>
                         </div>
                         <hr />
                         <div class="row">
                             <div class="col-1">
                                 <i class="fa fa-mobile"></i>
                             </div>
                             <div class="col-11">
                                 <div class="pl-2">+91 ${customer.billing_address_mobile ? customer.billing_address_mobile : ''}</div>
                             </div>
                         </div>
                         <hr />
                         <div class="row">
                             <div class="col-1">
                                 <i class="fa fa-map-marker"></i>
                             </div>
                             <div class="col-11">
                                 <div class="pl-2">${customer.billing_address_city_name ? customer.billing_address_city_name : ''}</div>
                             </div>
                         </div>
                         <hr />
                     </div>
                 </div>`);
             }
         } else {
             listArea.append(`<div id="emptyCustomerDetailArea">
                 <div class="card m-b-20 card-body" style="padding: 0.75rem;">
                     <div class="row align-items-center ">
                         <div class="col text-center"><h6>No Customer available!</h6></div>
                     </div>
                 </div>
             </div>`);
         }
         
     }
 
     window.loadEmpDetail = function (href) {
         let loadSwal;
 
         $.ajax({
             url: href,
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
                     
                     if (typeof res.customer.data != 'undefined' && Object.keys(res.customer.data).length > 0) {
                         loadEmpDetailView('detail', res.customer.data);
                     } else {
                         loadEmpDetailView('new');
                         toastr.info('No employee detail');
                     }
                     
                 } else if (res.status == 'error') {
                     toastr.error(res.message);
                     loadEmpDetailView('new');
                 } else {
                     toastr.error('No response status!', 'Error');
                     loadEmpDetailView();
                 }
             },
             error: function (xhr, textStatus, errorThrown) {
                 toastr.error(`${textStatus} <br />${errorThrown}`, 'Error');
                 loadEmpDetailView();
             },
             complete: function () {
                 loadSwal.close();
             }
         });
     }
    
 });
 
 
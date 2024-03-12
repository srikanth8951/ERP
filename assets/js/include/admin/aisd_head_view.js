/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: employee js
 */

 var appEmpManager = {};

 $(function () {
 
     const listArea = $('#empaisdhead--deatils--area');
     const newContainer = listArea.find('#empAISDHeadNewArea');
     const detailContainer = listArea.find('#empAISDHeadDetailArea');
     
     window.loadEmpDetailView = function (type = '', employee = {}) {
         if (type == 'new') {
             newContainer.fadeIn('slow');
             detailContainer.hide();
         } else if(type == 'detail') {
             detailContainer.fadeIn('slow');
             newContainer.hide();
             console.log(Object.keys(employee).length);
             if (Object.keys(employee).length > 0) {
                 detailContainer.find('[data-employee-detail="aisdHead"]').html(`<div class="card m-b-20">
                     <div class="card-body">
                         <div class="row">
                             <div class="col-5">
                                 <img class="img_cls" src="${formUrl('assets/images/users/avatar.png')}">
                             </div>
                             <div class="col-7 align-self-end">
                                 <b>${employee.first_name} ${employee.last_name}</b><br>
                                 EMP-1001<br>
                                 AISD Head
                             </div>
                         </div>
                         <hr />
                         <div class="row">
                             <div class="col-1">
                                 <i class="fa fa-envelope"></i>
                             </div>
                             <div class="col-11">
                                 <div class="pl-2">${employee.email}</div>
                             </div>
                         </div>
                         <hr />
                         <div class="row">
                             <div class="col-1">
                                 <i class="fa fa-mobile"></i>
                             </div>
                             <div class="col-11">
                                 <div class="pl-2">+91 ${employee.mobile}</div>
                             </div>
                         </div>
                         <hr />
                         <div class="row">
                             <div class="col-1">
                                 <i class="fa fa-map-marker"></i>
                             </div>
                             <div class="col-11">
                                 <div class="pl-2">${employee.city_name ? employee.city_name : ''}</div>
                             </div>
                         </div>
                         <hr />
                         <div class="row align-items-center justify-content-between">
                            <div class="col">
                                <a href="${formUrl('admin/aisd_head/edit/' + employee.employee_id)}" id="btn-edit-employee-aisdhead" class="btn btn-sm btn-outline-primary waves-effect waves-light"><i class="fa fa-pencil"></i>&nbsp;Edit</a>
                            </div>
                            <div class="col text-right">
                                <button id="btn-delete-employee-aisdhead" data-employee="${employee.employee_id}" class="btn btn-sm btn-outline-danger waves-effect waves-light"><i class="fa fa-trash"></i>&nbsp;Remove</button>
                            </div>
                        </div>
                     </div>
                 </div>`);
             }
         } else {
             listArea.append(`<div id="emptyAISDHeadDetailArea">
                 <div class="card m-b-20 card-body" style="padding: 0.75rem;">
                     <div class="row align-items-center ">
                         <div class="col text-center"><h6>No Employee available!</h6></div>
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
                     
                     if (typeof res.employee.data != 'undefined' && Object.keys(res.employee.data).length > 0) {
                         loadEmpDetailView('detail', res.employee.data);
                     } else {
                         loadEmpDetailView('new');
                         toastr.info('No employee detail');
                     }
                     
                 } else if (res.status == 'error') {
                    //  toastr.error(res.message);
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


     // Delete employee
    $(detailContainer).on('click', '#btn-delete-employee-aisdhead', function (e) {
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
                    url: formApiUrl('admin/employee/aisd_head/delete', { employee_id: employee_id }),
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
                        loadEmpDetail(formApiUrl('admin/employee/aisd_head'));  // Load employee details
                        toastr.success(res.message);
                        window.location.reload();
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
 
 
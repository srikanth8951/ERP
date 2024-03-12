/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: asset view js
 */

 var appAsset = {};

 $(function () {
 
     const listArea = $('#asset--deatils--area');
     const detailContainer = listArea.find('[data-container="assetDetailArea"]');
     
     window.loadAssetDetailView = function (asset = {}) {
        
        detailContainer.fadeIn('slow');
        console.log(Object.keys(asset).length);
        if (Object.keys(asset).length > 0) {
            detailContainer.html(`<div class="card card-body">
                <div class="row">
                    <h6 class="col-12"><u>Job</u></h6>
                    <div class="col-12 form-group" id="asset--jobs--area">
                        <span class="d-block text-muted">---</span>
                    </div>
                </div>
                <div class="row">
                    <h6 class="col-12"><u>Asset</u></h6>
                    <div class="col-md-4 form-group">
                        <label>Asset Name</label>
                        <span class="d-block text-muted">${asset.name}</span>
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Group</label>
                        <span class="d-block text-muted">${asset.group_name}</span>
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Sub Group</label>
                        <span class="d-block text-muted">${asset.sub_group_name}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12"><h6><u>Asset Information</u></h6></div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Type of Compressor</label>
                                <span class="d-block text-muted">${parseValue(asset.compressor_type) ? asset.compressor_type : '-'}</span>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Make</label>
                                <span class="d-block text-muted">${parseValue(asset.make) ? asset.make : '-'}</span>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Model</label>
                                <span class="d-block text-muted">${parseValue(asset.model) ? asset.model : '-'}</span>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Sl.No</label>
                                <span class="d-block text-muted">${parseValue(asset.serial_number) ? asset.serial_number : '-'}</span>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Capacity</label>
                                <span class="d-block text-muted">${asset.capacity}</span>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>UOM</label>
                                <span class="d-block text-muted">${asset.measurement_unit}</span>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Quantity</label>
                                <span class="d-block text-muted">${asset.quantity}</span>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Location</label>
                                <span class="d-block text-muted">${asset.location}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`);

            let jobContent = '';
            if (asset.jobs.length > 0) {
                asset.jobs.forEach(function (job) {
                    jobContent += `<span class="d-block text-muted"><label>Name/No :</label> ${job.job_title} / ${job.job_number}</span>`;
                });
            } else {
                jobContent = `<span class="d-block text-muted">---</span>`
            }

            detailContainer.find('#asset--jobs--area').html(jobContent);
        } else {
            detailContainer.html(`<div id="emptyAssetDetailArea">
                <div class="card m-b-20 card-body" style="padding: 0.75rem;">
                    <div class="row align-items-center ">
                        <div class="col text-center"><h6>No Asset available!</h6></div>
                    </div>
                </div>
            </div>`);
         }
         
     }
 
     window.loadAssetDetail = function (optionsz) {
         let loadSwal;
         let optionz = Object.assign({}, { asset_id: assetId }, optionsz);

         $.ajax({
             url: formApiUrl('employee/asd/asset/detail', optionz),
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
                     
                     if (typeof res.asset != 'undefined' && Object.keys(res.asset).length > 0) {
                         loadAssetDetailView(res.asset);
                     } else {
                         loadAssetDetailView();
                         toastr.info('No asset detail');
                     }
                     
                 } else if (res.status == 'error') {
                     toastr.error(res.message);
                     loadAssetDetailView();
                 } else {
                     toastr.error('No response status!', 'Error');
                     loadAssetDetailView();
                 }
             },
             error: function (xhr, textStatus, errorThrown) {
                 toastr.error(`${textStatus} <br />${errorThrown}`, 'Error');
                 loadAssetDetailView();
             },
             complete: function () {
                 loadSwal.close();
             }
         });
     }
    
 });
 
 
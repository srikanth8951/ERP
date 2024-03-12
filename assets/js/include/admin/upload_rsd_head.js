
    $(function(){
        const rsdHeadUploadForm = $('#uploadForm');
        const rsdHeadModal = $('#rsdHeadUploadModal');
           //  form validation
        rsdHeadUploadForm.validate({
            onkeyup: function(element) {$(element).valid()}, 
            onkeydown : function(element) {$(element).valid()}, 
            onpaste : function(element) {$(element).valid()}, 
            oncontextmenu  : function(element) {$(element).valid()}, 
            oninput  : function(element) {$(element).valid()}, 
            rules: {
                file: {
                    required: true
                }               
            },
            messages: {
                file: {
                    required: "Please choose file"
                }     
            }
        }); 

        // Upload modal close
        rsdHeadModal.find('.close').click(function() {
            rsdHeadUploadForm.find('input[type="file"]').filestyle('clear');
            rsdHeadUploadForm[0].reset();
        });

        $("#btn-upload-region-head").on("click",function(e){ 
            e.preventDefault();
            var loadSwal;
                        
            var file =window.document.getElementById("input#file");
            var formData = new FormData(rsdHeadUploadForm[0]);

            formData.append("File", file);
            
            if(rsdHeadUploadForm.valid()){
                rsdHeadUploadForm.attr('action', formApiUrl('admin/Employee/RegionalServiceDeliveryHead/upload'));
                rsdHeadModal.modal({backdrop: 'static', keyboard: false, show: true});
                $.ajax({
                
                    type : "POST",
                    url  : rsdHeadUploadForm.attr('action'),           
                    enctype: 'multipart/form-data',
                    dataType : "JSON",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data : formData,
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
                    success: function(res) { 
                        if(res.status == 'success'){
                            var j=1;
                            $('#rsdHeadUploadModal').modal('hide'); 
                            $('#rsdHeadListModal').modal({ backdrop: 'static', keyboard: false, show:true });
                            var rtable = $("#rsdHeadTableId");
                            rtable.html('<thead><tr><th>Sl.No</th><th>Name</th><th>Email</th><th>Mobile</th></tr></thead>');

                            $.each(res.upload, function (key, regval) 
                            {    
                                rtable.append('<tbody><tr class="rupload"> <td>' + j + '</td> <td>' + regval.first_name +' '+regval.last_name + '</td> <td>' + regval.email + '</td> <td>' + regval.mobile + ' </td></tr></tbody>'); 

                                if(regval.is_exist == 4 && regval.status == 2){
                                    $(".rupload td").css('color', 'red');
                                    $('#duplicate-msg').text("Duplicate rsdHead Code Will Not Be Saved");
                                }   
                                j++;        
                            });
                        } else if (res.status == 'error')  {
                            
                            if (typeof res.message == 'object' && Object.keys(res.message).length > 0) {
                                
                                for(const [key, value] of Object.entries(res.message)) {
                                    console.log(value);
                                    toastr.error(value);
                                }
                            } else {
                                toastr.error(res.message);
                            }
                        }  else {
                            toastr.error('No response status');
                        }

                    },
                    error: function(data) {
                        //Your Error Message
                        console.log('RESSS IS'+data.error)
                    },
                    complete: function() {
                        // $('#tableId').ajax.reload();
                        loadSwal.close();
                    }
                
            });//end ajax
        }
            
        });

        //on submit
        $('#region-head-submit').on("click",function(e){
            e.preventDefault();   
            var loadSwal;    

            $.ajax({
                type : "POST",
                url  : formApiUrl('admin/Employee/RegionalServiceDeliveryHead/saveEmployee'),           
                dataType : "JSON",
                processData: false,
                contentType: false,
                cache: false,
                async: false,       
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
                success: function(res) { 
                    $('#rsdHeadUploadModal').modal('hide'); 
                    rsdHeadUploadForm.find('input[type="file"]').filestyle('clear'); // Clear selected files
                    rsdHeadUploadForm[0].reset(); // Clear form
                    $('#rsdHeadListModal').modal('hide'); 

                    if(res.status == 'success'){
                        toastr.success(res.message);
                        loadEmpDetails(formApiUrl('admin/employee/rsd_head/list'));  // Load rsdHead details
                    } else if (res.status == 'error')  {
                        toastr.error(res.message);
                    }  else {
                        toastr.error('No response status');
                    }
                },
                error: function(data) {
                    //Your Error Message
                    toastr.error("Something went wrong: Contact Support");
                },
                complete: function() {
                    loadSwal.close();
                }
                
            });//end ajax
        });

         //cancel
         $('.btn-cancel').click(function(e){
            e.preventDefault();
            $.ajax({
                type : "POST",
                url  : formApiUrl('admin/Employee/RegionalServiceDeliveryHead/cancel'),           
                dataType : "JSON", 
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
                success: function(res) {
                    if(res.status == 'success'){
                        $('#rsdHeadUploadModal').modal('hide'); 
                        toastr.success(res.message);
                        loadEmpDetails(formApiUrl('admin/employee/rsd_head/list'));  // Load rsdHead details
                    } else if (res.status == 'error')  {
                        toastr.error(res.message);
                    }  else {
                        toastr.error('No response status');
                    }
                },
                error: function(data) {
                    toastr.error("Something went wrong: Contact Support");
                },
                complete: function() {
                    loadSwal.close();
                }
                
            });//end ajax
        });

});

    
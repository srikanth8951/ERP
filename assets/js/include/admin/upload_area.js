
    $(function(){
        const areaUploadForm = $('#uploadForm');
        const areaModal = $('#areaUploadModal');
           //  form validation
        areaUploadForm.validate({
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
        areaModal.find('.close').click(function() {
            areaUploadForm.find('input[type="file"]').filestyle('clear');
            areaUploadForm[0].reset();
        });

        $("#btn-upload-area").on("click",function(e){ 
            e.preventDefault();
            var loadSwal;
                        
            var file =window.document.getElementById("input#file");
            var formData = new FormData(areaUploadForm[0]);

            formData.append("File", file);
            
            if(areaUploadForm.valid()){
                areaUploadForm.attr('action', formApiUrl('admin/Area/upload'));
                areaModal.modal({backdrop: 'static', keyboard: false, show: true});
                $.ajax({
                
                    type : "POST",
                    url  : areaUploadForm.attr('action'),           
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
                            $('#areaUploadModal').modal('hide'); 
                            $('#areaListModal').modal({ backdrop: 'static', keyboard: false, show:true });
                            var rtable = $("#areaTableId");
                            rtable.html('<thead><tr><th>Sl.No</th><th>Region</th><th>Branch</th><th>Name</th><th>Code</th></tr></thead>');

                            $.each(res.upload_areas, function (key, regval) 
                            {    
                                rtable.append('<tbody><tr class="rupload"> <td>' + j + '</td> <td>' + regval.region_name + '</td> <td>' + regval.branch_name + '</td> <td>' + regval.name + ' </td>   <td>' + regval.code + '</td></tr></tbody>'); 

                                if(regval.is_exist == 4 && regval.status == 2){
                                    $(".rupload td").css('color', 'red');
                                    $('#duplicate-msg').text("Duplicate area Code Will Not Be Saved");
                                
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
        $('#area-submit').on("click",function(e){
            e.preventDefault();   
            var loadSwal;    

            $.ajax({
                type : "POST",
                url  : formApiUrl('admin/Area/savearea'),           
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
                    $('#areaUploadModal').modal('hide'); 
                    areaUploadForm.find('input[type="file"]').filestyle('clear'); // Clear selected files
                    areaUploadForm[0].reset(); // Clear form
                    $('#areaListModal').modal('hide'); 

                    if(res.status == 'success'){
                        toastr.success(res.message);
                        loadDetails(formApiUrl('admin/area/list'));  // Load area details
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
            var loadSwal;
            e.preventDefault();
            $.ajax({
                type : "POST",
                url  : formApiUrl('admin/Area/cancel'),           
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
                        $('#areaUploadModal').modal('hide'); 
                        toastr.success(res.message);
                        loadDetails(formApiUrl('admin/area/list'));  // Load area details
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

    
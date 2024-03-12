
    $(function(){
        const assetUploadForm = $('#uploadForm');
        const assetModal = $('#assetUploadModal');
           //  form validation
        assetUploadForm.validate({
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
        assetModal.find('.close').click(function() {
            assetUploadForm.find('input[type="file"]').filestyle('clear');
            assetUploadForm[0].reset();
        });

        $("#btn-upload-asset").on("click",function(e){ 
            e.preventDefault();
            var loadSwal;
                        
            var file =window.document.getElementById("input#file");
            var formData = new FormData(assetUploadForm[0]);

            formData.append("File", file);
            
            if(assetUploadForm.valid()){
                assetUploadForm.attr('action', formApiUrl('admin/asset/upload'));
                assetModal.modal({backdrop: 'static', keyboard: false, show: true});
                $.ajax({
                
                    type : "POST",
                    url  : assetUploadForm.attr('action'),           
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
                            $('#assetUploadModal').modal('hide'); 
                            $('#assetListModal').modal({ backdrop: 'static', keyboard: false, show:true });
                            var rtable = $("#assetTableId");
                            rtable.html('<thead><tr><th>Sl.No</th><th>Name</th><th>Group</th><th>Sub Group</th><th>Location</th></tr></thead>');

                            $.each(res.upload, function (key, regval) 
                            {    
                                rtable.append('<tbody><tr class="rupload"> <td>' + j + '</td> <td>' + regval.name + '</td> <td>' + regval.group_name + '</td> <td>' + regval.sub_group_name + ' </td> <td>' + regval.location + ' </td></tr></tbody>'); 

                                if(regval.is_exist == 4 && regval.status == 2){
                                    $(".rupload td").css('color', 'red');
                                    $('#duplicate-msg').text("Duplicate Asset Details Will Not Be Saved");
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
        $('#asset-submit').on("click",function(e){
            e.preventDefault();   
            var loadSwal;    

            $.ajax({
                type : "POST",
                url  : formApiUrl('admin/asset/saveAssets'),           
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
                    $('#assetUploadModal').modal('hide'); 
                    assetUploadForm.find('input[type="file"]').filestyle('clear'); // Clear selected files
                    assetUploadForm[0].reset(); // Clear form
                    $('#assetListModal').modal('hide'); 

                    if(res.status == 'success'){
                        toastr.success(res.message);
                       // loadEmpDetails(formApiUrl('admin/employee/rsd_head/list'));  // Load rsdHead details
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
                url  : formApiUrl('admin/asset/cancel'),           
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
                        $('#assetUploadModal').modal('hide'); 
                        toastr.success(res.message);
                        assetUploadForm.find('input[type="file"]').filestyle('clear');
                        assetUploadForm[0].reset();
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

        $('#btn-download-upload-sample').click(function (e) {
            e.preventDefault();
            let loadSwal;
            const invForm = $(this);
        
            $.ajax({
                url: formApiUrl('admin/asset/downloadSample'),
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
                        let fileName = 'asset-' + moment().format('DD/MM/YYYY') + '.xlsx';
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

    
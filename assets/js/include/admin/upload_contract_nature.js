
    $(function(){
        const ContractNatureUploadForm = $('#uploadForm');
        const ContractNatureModal = $('#contractNatureUploadModal');
           //  form validation
        ContractNatureUploadForm.validate({
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
        ContractNatureModal.find('.close').click(function() {
            ContractNatureUploadForm.find('input[type="file"]').filestyle('clear');
            ContractNatureUploadForm[0].reset();
        });

        $("#btn-upload-contractNature").on("click",function(e){ 
            e.preventDefault();
            var loadSwal;
                        
            var file =window.document.getElementById("input#file");
            var formData = new FormData(ContractNatureUploadForm[0]);

            formData.append("File", file);
            
            if(ContractNatureUploadForm.valid()){
                ContractNatureUploadForm.attr('action', formApiUrl('admin/ContractNature/upload'));
                ContractNatureModal.modal({backdrop: 'static', keyboard: false, show: true});
                $.ajax({
                
                    type : "POST",
                    url  : ContractNatureUploadForm.attr('action'),           
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
                            $('#contractNatureUploadModal').modal('hide'); 
                            $('#contractNatureListModal').modal({ backdrop: 'static', keyboard: false, show:true });
                            var rtable = $("#contractNatureTableId");
                            rtable.html('<thead><tr><th>Sl.No</th><th>Name</th><th>Code</th></tr></thead>');

                            $.each(res.upload_nature, function (key, regval) 
                            {    
                                rtable.append('<tbody><tr class="rupload"> <td>' + j + '</td> <td>' + regval.name + ' </td>   <td>' + regval.code + '</td></tr></tbody>'); 

                                if(regval.is_exist == 4 && regval.status == 2){
                                    $(".rupload td").css('color', 'red');
                                    $('#duplicate-msg').text("Duplicate Nature of Contracts Code Will Not Be Saved");
                                
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
        $('#contractNature-submit').on("click",function(e){
            e.preventDefault();   
            var loadSwal;    

            $.ajax({
                type : "POST",
                url  : formApiUrl('admin/ContractNature/saveContractNature'),           
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
                    $('#contractNatureUploadModal').modal('hide'); 
                    ContractNatureUploadForm.find('input[type="file"]').filestyle('clear'); // Clear selected files
                    ContractNatureUploadForm[0].reset(); // Clear form
                    $('#contractNatureListModal').modal('hide'); 

                    if(res.status == 'success'){
                        toastr.success(res.message);
                        loadDetails(formApiUrl('admin/contract_nature/list'));  // Load ContractNature details
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
                url  : formApiUrl('admin/ContractNature/cancel'),           
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
                        $('#contractNatureUploadModal').modal('hide'); 
                        toastr.success(res.message);
                        loadDetails(formApiUrl('admin/contract_nature/list'));  // Load ContractNature details
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

    
$(function () {
    const assetUploadModal = $('#assetUploadModal');
    const assetUploadForm = $('#assetUploadForm');
    
    /** 
     * Asset Upload 
     **/
     $('#btn-asset-upload').click(function (e) {
        e.preventDefault();

        assetUploadModal.modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
    });


    // Reset on modal close
    assetUploadModal.find('[data-dismiss="modal"]').click(function () {
        assetUploadForm[0].reset();
    });

    /** 
     * Convert xlsx file to json
     * */
    var wsDatas = [];
    var assetGroups = [];
    var assetSubGroups = [];

    // Get asset groups and stored in @param assetGroups
    function getAssetGroups() {
        return new Promise((resolve, reject) => {
            if (assetGroups.length <= 0) {
                $.ajax({
                    url: formApiUrl("admin/asset/group/autocomplete"),
                    type: "get",
                    dataType: "json",
                    async: false,
                    headers: {
                        Authorization: `Bearer ${wapLogin.getToken()}`,
                    },
                }).done((res) => {
                    if (res.status == "success") {
                        if (res.asset_groups) {
                            assetGroups = res.asset_groups;
                        }
                    }

                    resolve(true);
                });
            } else {
                resolve(true);
            }
        });
    }

    // Get asset sub groups and stored in @param assetSubGroups
    function getAssetSubGroups() {
        return new Promise((resolve, reject) => {
            if (assetSubGroups.length <= 0) {
                $.ajax({
                    url: formApiUrl("admin/asset/group/autocomplete", { parent: true }),
                    type: "get",
                    dataType: "json",
                    async: false,
                    headers: {
                        Authorization: `Bearer ${wapLogin.getToken()}`,
                    },
                }).done((res) => {
                    if (res.status == "success") {
                        if (res.asset_groups) {
                            assetSubGroups = res.asset_groups;
                        }
                    }

                    resolve(true);
                });
            } else {
                resolve(true);
            }
        });
    }

    // Get asset group id by name  @return id
    function getAssetGroupIdByName(name) {
        return new Promise((resolve, reject) => {

            let code = name.replace(' ', '_').toLowerCase();
            let id = 0;

            if (assetGroups.length > 0) {
                for (let group of assetGroups) {
                    if (code == group.name.replace(' ', '_').toLowerCase()) {
                        resolve(group.id);
                    }
                }
            }

            resolve(id);
        });
    }

    // Get asset sub group id by name @return id
    function getAssetSubGroupIdByName(name) {
        return new Promise((resolve, reject) => {

            let code = name.replace(' ', '_').toLowerCase();
            let id = 0;

            if (assetSubGroups.length > 0) {
                for (let group of assetSubGroups) {
                    if (code == group.name.replace(' ', '_').toLowerCase()) {
                        resolve(group.id);
                    }
                }
            }

            resolve(id);
        });
    }

    // Get measurement code by name @return code
    function getMeasurementUnitCodeByName(name) {
        return new Promise((resolve, reject) => {
            let code = name.replace(' ', '_').toLowerCase();
            let unitCode = '';

            for (let unit of getMeasurementUnits()) {
                if (code == unit.name.replace(' ', '_').toLowerCase()) {
                    resolve(unit.code);
                }
            }

            resolve(unitCode);
        });
    }

    // Excel File upload
    assetUploadForm.on('change', 'input.upload-file', function (e) {
        assetUploadModal.find('.btn-add-assets').attr('disabled', true);

        var file = e.target.files[0];

        // Check file extension
        let acceptedFiles = e.target.accept.split(',').map(function (aFile) {
            return aFile.substring(1, aFile.length);
        });
        let fileExt = file.name.split('.').pop();

        if (acceptedFiles.includes(fileExt)) {
            var reader = new FileReader();

            reader.onload = function (e) {
                var workbook = XLSX.read(e.target.result);
                let ws = workbook.SheetNames[0];

                wsDatas = XLSX.utils.sheet_to_json(workbook.Sheets[ws], {
                    header: ["name", "group", "subGroup", "isCompressorRequired", "makeCompressor", "totalCompressor", "make", "model", "serialNumber", "capacity", "uom", "quantity", "location", "checklists"]
                });

                assetUploadModal.find('.btn-add-assets').attr('disabled', false);
            }

            reader.readAsArrayBuffer(file);
        } else {
            toastr.info('Please select valid file');
        }

    });

    // Submit after file upload
    assetUploadForm.submit(function (e) {
        e.preventDefault();
        assetUploadModal.find('.btn-add-assets')
            .hide()
            .attr('disabled', true)
            .next('.btn-loading').show();
        delete wsDatas[0];

        setTimeout(async function () {
            if (wsDatas.length > 0) {
                assetUploadModal.modal('hide');
                await getAssetGroups();
                await getAssetSubGroups();

                wsDatas.forEach(async function (wsData, dindex) {
                    let assetGroupId = 0;
                    let assetSubGroupId = 0;
                    let measurementUnitCode = 0;

                    await getAssetGroupIdByName(wsData.group)
                        .then((id) => {
                            assetGroupId = id;
                        }).catch(() => {
                            assetGroupId = 0;
                        });
                    await getAssetSubGroupIdByName(wsData.subGroup)
                        .then((id) => {
                            assetSubGroupId = id;
                        }).catch(() => {
                            assetSubGroupId = 0;
                        });
                    await getMeasurementUnitCodeByName(wsData.uom)
                        .then((code) => {
                            measurementUnitCode = code;
                        }).catch(() => {
                            measurementUnitCode = '';
                        });

                    await loadAssetView({
                        type: 'upload',
                        detail: {
                            name: wsData.name.toString(),
                            group_id: assetGroupId,
                            sub_group_id: assetSubGroupId,
                            required_compressor: (wsData.isCompressorRequired.toLowerCase() == 'yes') ? 1 : 0,
                            make_compressor: (wsData.isCompressorRequired.toLowerCase() == 'yes') ? wsData.makeCompressor.toString() : '',
                            total_compressor: (wsData.isCompressorRequired.toLowerCase() == 'yes') ? wsData.totalCompressor : '',
                            make: wsData.make.toString(),
                            model: wsData.model.toString(),
                            capacity: wsData.capacity,
                            quantity: wsData.quantity,
                            location: wsData.location.toString(),
                            serial_number: wsData.serialNumber,
                            measurement_unit: measurementUnitCode,
                            checklists: wsData.checklists.toString()
                        }
                    });
                });
            } else {
                toastr.error('No excel data');
            }

            assetUploadModal.find('.btn-add-assets')
                .show()
                .attr('disabled', false)
                .next('.btn-loading').hide();
            
            // Reset Worksheet data after the process
            wsDatas = [];
            assetUploadForm[0].reset();
        }, 1000);

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
})
/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: contract job asset js
 */

var appcontractjobasset = {};

$(function () {
    const assetAriaBlock = $('#asset_div');
    const assetModal = $('#assetModal');
    window.assetCount = 0;

    function loadEmptyAssetView() {
        let assetContent = `<div class="row asset_row" id="asset-row-empty">
            <div class="col-lg-12">
                <div class="card m-b-20">
                    <div class="card-body">
                        <p class="mb-0">No asset available</p>
                    </div>
                </div>
            </div>
        </div>`;
        assetAriaBlock.html(assetContent);
    }

    async function loadAssetFormView(ucount, detail, type) {
        let assetJobElement = $('#asset-row' + ucount);

        if (parseValue(detail) != '' && Object.keys(detail).length > 0) {

            if (emp_form_type == 'view') {
                assetJobElement.find('input, textarea, select, button').prop('disabled', true)
            }

            assetJobElement.find('#assets' + ucount + '-name').val(detail.name);

            assetJobElement.find('#assets' + ucount + '-compressor-type').val(detail.make_compressor);
            assetJobElement.find('#assets' + ucount + '-total-compressor').val(detail.total_compressor).trigger("change");
            assetJobElement.find('#assets' + ucount + '-make').val(detail.make);
            assetJobElement.find('#assets' + ucount + '-model').val(detail.model);
            assetJobElement.find('#assets' + ucount + '-capacity').val(detail.capacity);
            assetJobElement.find('#assets' + ucount + '-quantity').val(detail.quantity);
            assetJobElement.find('#assets' + ucount + '-location').val(detail.location);
            assetJobElement.find('#assets' + ucount + '-serial-number').val(detail.serial_number);
            assetJobElement.find('#assets' + ucount + '-uom').val(detail.measurement_unit).trigger('change');

            if (detail.make_compressor) {
                $('#assets' + ucount + '-compressor').attr('checked', true);
                $('#assets' + ucount + '-sel_compressor').show();
                $('#assets' + ucount + '-total_compressor').show();
            } else {
                $('#assets' + ucount + '-compressor').attr('checked', false);
                $('#assets' + ucount + '-sel_compressor').hide();
                $('#assets' + ucount + '-total_compressor').hide();
            }

            await loadAutocompleteAssetGroup({
                acount: ucount,
                elementId: `#asset-row${ucount}`,
                topLevel: true,
                selected: [detail.group_id]
            });

            await loadAutocompleteAssetSubGroup({
                acount: ucount,
                elementId: `#asset-row${ucount}`,
                topLevel: true,
                selected: [detail.sub_group_id]
            });

            // load asset checklists
            if (type == 'exist') {
                await loadAssetChecklists(detail.asset_id, ucount);
            } else if (type == 'upload') {
                await getAssetChecklists(detail.checklists, ucount);
            }
            
        } else {
            toastr.info('Error occured while loading asset detail!');
            assetJobElement.find('input, textarea, select').prop('disabled', false);
        }
    }


    // Load asset details
    window.loadAssetDetails = function (contract_job_id) {
        $.ajax({
            url: formApiUrl('admin/contract_job/asset/list'),
            type: "get",
            data: {
                contract_job_id: contract_job_id
            },
            dataType: "json",
            cache: false,
            headers: {
                Authorization: `Bearer ${wapLogin.getToken()}`,
            },
            beforeSend: function () {
                // loadSwal = Swal.fire({
                //     html:
                //         '<div class="my-4 text-center d-inline-block">' +
                //         loaderContent +
                //         "</div>",
                //     customClass: {
                //         popup: "col-6 col-sm-5 col-md-3 col-lg-2",
                //     },
                //     allowOutsideClick: false,
                //     allowEscapeKey: false,
                //     showConfirmButton: false
                // });
            },
            complete: function () {
                // loadSwal.close();
            },
        }).done(function (res) {
            console.log(res);
            if (res.status == "success") {
                $.each(res.contract_job_assets, async function (bi, asset) {
                    // console.log(asset);
                    await loadAssetView({
                        type: "exist",
                        asset_id: asset.asset_id,
                        detail: asset,
                        remove: true,
                    });
                });
            } else if (res.status == "error") {
                if (
                    typeof res.message == "object" &&
                    Object.keys(res.message).length > 0
                ) {
                    for (const [mkey, mvalue] of Object.entries(res.message)) {
                        console.log(mvalue);
                    }
                } else {
                    console.log(res.message);
                }
            } else {
                console.log("No response status", "Error");
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.log(`${textStatus} - ${errorThrown}`);
        });
    }

    // Asset checklist view
    function loadAssetChecklistEmptyView(element) {
        element.find('.asset-checklist-body .checklists-area').html(`<li class="checklist-row-empty d-flex align-items-center justify-content-between border rounded p-2 mt-2">
                <span>No Checklist Available</span>
            </li>`);
    }

    function loadAssetChecklistView(element, data) {
        let buttonAttribute = '';
        if (emp_form_type == 'view') {
            buttonAttribute = 'disabled'
        }
        element.find('.asset-checklist-body .checklists-area .checklist-row-empty').fadeOut().remove();
        element.find('.asset-checklist-body .checklists-area').prepend(`<li class="checklist-row d-flex align-items-center justify-content-between border rounded p-2 mt-2">
            <input type="hidden" name="assets[${data.assetCount}][checklist][${checklistCode}][]" value="${data.id}" />
            <span>${data.name}</span>
            <button type="button" data-asset-count="${data.assetCount}" class="btn-remove-checklist btn btn-sm btn-outline-danger waves-effect" ${buttonAttribute}><i class="mdi mdi-delete"></i></button>
        </li>`);
    }

    // Load asset checklists
    window.loadAssetChecklists = function (asset_id, assetCount) {
        return new Promise((resolve, reject) => {
            let element = $('#asset-row' + assetCount);

            $.ajax({
                url: formApiUrl('admin/contract_job/asset/checklists'),
                type: "get",
                data: {
                    asset_id: asset_id
                },
                dataType: "json",
                cache: false,
                headers: {
                    Authorization: `Bearer ${wapLogin.getToken()}`,
                },
                beforeSend: function () {
                    // loadSwal = Swal.fire({
                    //     html:
                    //         '<div class="my-4 text-center d-inline-block">' +
                    //         loaderContent +
                    //         "</div>",
                    //     customClass: {
                    //         popup: "col-6 col-sm-5 col-md-3 col-lg-2",
                    //     },
                    //     allowOutsideClick: false,
                    //     allowEscapeKey: false,
                    //     showConfirmButton: false
                    // });
                },
                complete: function () {
                    // loadSwal.close();
                },
            }).done(function (res) {
                console.log(res);
                if (res.status == "success") {
                    $.each(res.asset_checklists, function (inx, value) {
                        loadAssetChecklistView(element, {
                            assetCount: assetCount,
                            id: value.checklist_id,
                            name: value.name
                        });
                    });
                    resolve(true);
                } else if (res.status == "error") {
                    if (
                        typeof res.message == "object" &&
                        Object.keys(res.message).length > 0
                    ) {
                        for (const [mkey, mvalue] of Object.entries(res.message)) {
                            console.log(mvalue);
                        }
                    } else {
                        console.log(res.message);
                    }
                    reject(true);
                } else {
                    console.log("No response status", "Error");
                    reject(true);
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log(`${textStatus} - ${errorThrown}`);
                reject(true);
            });
        });
    }

    window.getAssetChecklists = function (checklistIds, assetCount) {
        return new Promise((resolve, reject) => {
            if (parseValue(checklistIds) != '') {
                console.log();
                let element = $('#asset-row' + assetCount);

                $.ajax({
                    url: formApiUrl('admin/checklist/autocomplete'),
                    type: "get",
                    data: {
                        checklist_id: checklistIds.split(',')
                    },
                    dataType: "json",
                    cache: false,
                    headers: {
                        Authorization: `Bearer ${wapLogin.getToken()}`,
                    }
                }).done(function (res) {
                    console.log(res);
                    if (res.status == "success") {
                        $.each(res.checklists, function (inx, value) {
                            loadAssetChecklistView(element, {
                                assetCount: assetCount,
                                id: value.checklist_id,
                                name: value.name
                            });
                        });

                        resolve(true);
                    } else if (res.status == "error") {
                        if (
                            typeof res.message == "object" &&
                            Object.keys(res.message).length > 0
                        ) {
                            for (const [mkey, mvalue] of Object.entries(res.message)) {
                                console.log(mvalue);
                            }
                        } else {
                            console.log(res.message);
                        }

                        reject(true);
                    } else {
                        console.log("No response status", "Error");
                        reject(true);
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    console.log(`${textStatus} - ${errorThrown}`);
                    reject(true);
                });
            } else {
                reject(true);
            }
        });
    }

    window.loadAssetView = async function (options) {
        var data = Object.assign({}, {
            type: 'new',
            remove: true
        }, options);

        console.log(data);

        let resetButtonBlock = '';
        assetCount = assetCount + 1;
        let asset_id = 0;
        if (data.type == 'exist' && typeof data.asset_id != 'undefined') {
            asset_id = data.asset_id;
        }

        if (typeof assetAriaBlock.find('#asset-row-empty') != 'undefined') {
            assetAriaBlock.find('#asset-row-empty').remove();
        }

        if (data.remove == true) {
            resetButtonBlock = `<div class="clearfix">
                <button type="button" class="btn-remove-asset-row btn btn-danger btn-sm float-right">Remove Asset</button>
            </div>`;
        }

        var html = `<div class="row asset-row" id="asset-row${assetCount}">
            <div class="col-lg-12">
                <div class="card m-b-20" style="border-top: 4px solid rgba(112, 112, 112, 0.12);">
                    <div class="card-body">
                        <input type="hidden" name="assets[${assetCount}][type]" value="${data.type}" />
                        <input type="hidden" name="assets[${assetCount}][id]" value="${asset_id}" />
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="assets${assetCount}-name">Asset Category<span class="text-danger"> *</span></label>
                                    <input id="assets${assetCount}-name" class="form-control" type="text" name="assets[${assetCount}][name]" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group ele-jqValid">
                                    <label for="assets${assetCount}-group">Asset Group<span class="text-danger"> *</span></label>
                                    <select id="assets${assetCount}-group" name="assets[${assetCount}][group]" class="form-control select2 asset-group-select" data-toggle="select2" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group ele-jqValid">
                                    <label for="assets${assetCount}-sub-group">Asset Sub-Group<span class="text-danger"> *</span></label>
                                    <select id="assets${assetCount}-sub-group" name="assets[${assetCount}][sub_group]" class="form-control select2 asset-sub-group-select" data-toggle="select2" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex mb-2">
                                            <span>Does the asset have Compressor? &nbsp;</span>
                                            <input type="checkbox" id="assets${assetCount}-compressor" switch="primary" data-asset-count="${assetCount}" />
                                            <label for="assets${assetCount}-compressor" data-on-label="Yes" data-off-label="No"></label>
                                        </div>
                                    </div>

                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" id="assets${assetCount}-sel_compressor" style="display:none;">
                                        <div class="form-group">
                                            <label for="assets${assetCount}-compressor-type">Make of compressor<span class="text-danger">
                                                    *</span></label>
                                            <input id="assets${assetCount}-compressor-type" class="form-control" type="text"
                                                name="assets[${assetCount}][make_compressor]" required>
                                        </div>
                                    </div>

                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" id="assets${assetCount}-total_compressor" style="display:none;">
                                        <div class="form-group ele-jqValid">
                                            <label for="assets${assetCount}-total-compressor">Number of compressor<span class="text-danger">
                                                    *</span></label>
                                            <select id="assets${assetCount}-total-compressor" class="form-control select2" data-toggle="select2"
                                                name="assets[${assetCount}][total_compressor]" required>
                                                <option value="">Select</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="assets${assetCount}-make">Make(asset)</label>
                                            <input type="text" name="assets[${assetCount}][make]" id="assets${assetCount}-make"
                                                class="form-control" placeholder="" aria-describedby="helpId">
                                        </div>
                                    </div>

                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="assets${assetCount}-model">Model</label>
                                            <input id="assets${assetCount}-model" class="form-control" type="text"
                                                name="assets[${assetCount}][model]">
                                        </div>
                                    </div>

                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="assets${assetCount}-serial-number">Sr.No</label>
                                            <input id="assets${assetCount}-serial-number" class="form-control" type="number"
                                                name="assets[${assetCount}][serial_number]">
                                        </div>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="assets${assetCount}-capacity">Capacity<span class="text-danger"> *</span></label>
                                            <input id="assets${assetCount}-capacity" class="form-control" type="text"
                                                name="assets[${assetCount}][capacity]" required>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                        <div class="form-group ele-jqValid">
                                            <label for="assets${assetCount}-uom">UOM<span class="text-danger"> *</span></label>
                                            <select id="assets${assetCount}-uom" class="form-control select2" data-toggle="select2"
                                                name="assets[${assetCount}][measurement_unit]" required>
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="assets${assetCount}-quantity">Quantity<span class="text-danger"> *</span></label>
                                            <input type="text" name="assets[${assetCount}][quantity]" id="assets${assetCount}-quantity"
                                                class="form-control" placeholder="" aria-describedby="helpId" required>
                                        </div>
                                    </div>

                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="assets${assetCount}-location">Asset Location<span class="text-danger"> *</span></label>
                                            <input id="assets${assetCount}-location" class="form-control" type="text"
                                                name="assets[${assetCount}][location]" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card h-100">
                                    <div class="card-header shadow-sm border-0">
                                        <h6 class="m-0"><a class="d-block btn-collapse-with-icon" data-toggle="collapse" href="#assets${assetCount}-checklist-collapsez">Checklist
                                            <div class="pull-right"><i data-collapse-icon class="mdi mdi-chevron-right"></i></div>
                                        </a></h6>
                                    </div>
                                    <div class="collapse" id="assets${assetCount}-checklist-collapsez">
                                        <div class="card-body asset-checklist-body">
                                            <div class="card position-relative shadow-sm mb-2">
                                                <div class="card-header d-flex align-items-center justify-content-between">
                                                    <p class="lead m-0">List</p>
                                                    <button type="button" data-toggle="collapse" data-target="#assets${assetCount}-checklist" class="starter btn btn-sm btn-purple waves-effect waves-light"><i class="mdi mdi-plus"></i>&nbsp;Add</button>
                                                </div>
                                                <div style="position: absolute;top: 0;left: 0;right: 0;z-index: 9;" class="collapse collapse-checklist-area bg-light" id="assets${assetCount}-checklist">
                                                    <div class="card-body bg-light">    
                                                        <div class="d-flex align-items-center w-100">
                                                            <select data-toggle="jy-select2-assign-checklist" data-asset-count="${assetCount}" class="select2"></select>
                                                            <span data-toggle="collapse" data-target="#assets${assetCount}-checklist" class="finisher ml-2 font-20 text-danger"><i class="mdi mdi-close-circle"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <ul class="list-unstyled checklists-area">
                                                <li class="checklist-row-empty d-flex align-items-center justify-content-between border rounded p-2 mt-2">
                                                    <span>No Checklist Available</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer shadow-sm border-0">
                        ${resetButtonBlock}
                    </div>
                </div>
            </div>
        </div>`;

        assetAriaBlock.prepend(html);

        $('#asset-row' + assetCount + ' [data-toggle="select2"]').select2();

        assetAriaBlock.on('change', '#assets' + assetCount + '-compressor', function (e) {

            var assetCount = $(e.currentTarget).attr('data-asset-count');
            if ($(e.currentTarget).attr('checked') == true || $(e.currentTarget).attr('checked') == 'checked') {
                $(e.currentTarget).removeAttr('checked');
                $('#assets' + assetCount + '-sel_compressor').hide();
                $('#assets' + assetCount + '-total_compressor').hide();
            } else {
                $(e.currentTarget).attr('checked', true);
                $('#assets' + assetCount + '-sel_compressor').show();
                $('#assets' + assetCount + '-total_compressor').show();
            }
        });

        //  Collapse Icon
        $('#assets' + assetCount + '-checklist-collapsez').on('show.bs.collapse', function (e) {
            let collapseId = $(e.currentTarget).attr('id');
            
            $('a[href="#'+ collapseId +'"]').find('[data-collapse-icon]').addClass('mdi-chevron-down').removeClass('mdi-chevron-right');
        });

        $('#assets' + assetCount + '-checklist-collapsez').on('hide.bs.collapse', function (e) {
            let collapseId = $(e.currentTarget).attr('id');

            $('a[href="#'+ collapseId +'"]').find('[data-collapse-icon]').addClass('mdi-chevron-right').removeClass('mdi-chevron-down');
        });

        //  Collapse Btn
        $('#assets' + assetCount + '-checklist').on('show.bs.collapse', function (e) {
            let collapseId = $(e.currentTarget).attr('id');
            
            $('[data-target="#'+ collapseId +'"].starter').hide();
        });

        $('#assets' + assetCount + '-checklist').on('hide.bs.collapse', function (e) {
            let collapseId = $(e.currentTarget).attr('id');

            $('[data-target="#'+ collapseId +'"].starter').show();
        });

        // select2
        $('#asset-row' + assetCount + ' [data-toggle="jy-select2-assign-checklist"]').select2({
            // minimumInputLength:2,
            placeholder: "Select a checklist",
            ajax: {
                url: formApiUrl('admin/checklist/autocomplete', { limit: 10, group: checklistCode }),
                dataType: 'json',
                type: 'get',
                delay: 250,
                headers: {
                    Authorization: `Bearer ${wapLogin.getToken()}`,
                },
                data: function (param) {
                    return {
                        search: param.term,
                    }
                },
                processResults: function (data) {
                    return {
                        results: data.checklists.map(function (element, index) {
                            return {
                                id: element['id'],
                                text: element['name']
                            }
                        })
                    };
                },
                cache: true
            }
        });

        // Add checklist after select
        $('#asset-row' + assetCount + ' [data-toggle="jy-select2-assign-checklist"]').on('select2:select', function (e) {
            var data = e.params.data;
            var assetCount = $(e.currentTarget).attr('data-asset-count');
            loadAssetChecklistView($('#asset-row' + assetCount), {
                assetCount: assetCount,
                id: data.id,
                name: data.text
            });

            $('[data-target="#assets' + assetCount + '-checklist"].finisher').trigger('click');
        });

        // Remove checklist
        $('#asset-row' + assetCount).on('click', '.btn-remove-checklist', function (e) {
            e.preventDefault();
            var assetCount = $(e.currentTarget).attr('data-asset-count');
            $(e.currentTarget).parents('li.checklist-row').fadeOut().remove();

            // Check availability of checklist row after deletion, If no row available, add empty row
            if ($('#asset-row' + assetCount).find('.asset-checklist-body .checklists-area li.checklist-row').length < 1) {
                loadAssetChecklistEmptyView($('#asset-row' + assetCount));
            }

        });

        // Get Measurement Units
        getMeasurementUnits().forEach(function (unit, ui) {
            $('#assets' + assetCount + '-uom').append(new Option(unit.name, unit.code));
        });
        $('#assets' + assetCount + '-uom').trigger('change');


        if (data.type == 'exist') {
            loadAssetFormView(assetCount, data.detail, data.type);
        } if (data.type == 'upload') {
            loadAssetFormView(assetCount, data.detail, data.type);
        } else {
            await loadAutocompleteAssetGroup({
                acount: assetCount,
                elementId: `#asset-row${assetCount}`,
                topLevel: true
            });

            await loadAutocompleteAssetSubGroup({
                acount: assetCount,
                elementId: `#asset-row${assetCount}`,
                topLevel: true
            });
        }
        assetAriaBlock.find('[data-toggle="select2"]').select2();

    }

    //  Asset group Autocomplete
    window.loadAutocompleteAssetGroup = function (options = {}) {
        var selected;
        var assetGroupSelectbox = $(options.elementId).find('.asset-group-select');

        if (parseValue(options.selected) != '') {
            selected = (Object.keys(options.selected).length > 0) ? Object.values(options.selected) : [];
        } else {
            selected = [];
        }

        assetGroupSelectbox.html("").trigger("change"); // Reset selectbox
        $.ajax({
            url: formApiUrl("admin/asset/group/autocomplete", { limit: 10 }),
            type: "get",
            dataType: "json",
            headers: {
                Authorization: `Bearer ${wapLogin.getToken()}`,
            },
        }).done((res) => {
            assetGroupSelectbox.append(new Option("Select", "", false, false)); // Load initial select
            if (res.status == "success") {
                if (res.asset_groups) {
                    var assetGroups = res.asset_groups;
                    var assetGroupOption;

                    $.each(assetGroups, function (bi, group) {
                        if (selected.find((value) => {
                            return value == group.id
                        })) {
                            assetGroupOption = new Option(group.name, group.id, true, true);
                        } else {
                            assetGroupOption = new Option(group.name, group.id, false, false);
                        }

                        assetGroupSelectbox.append(assetGroupOption);
                    });
                    assetGroupSelectbox.trigger("change");
                }
            } else if (res.status == "error") {
                console.log(res.message);
            } else {
                console.log("Asset Group Autocomplete: Something went wrong!");
            }
        }).fail((xhr, ajaxOptions, errorThrown) => {
            console.log(xhr.responseText + " " + xhr.responseText);
        });
    };

    //  Asset group Autocomplete
    window.loadAutocompleteAssetSubGroup = function (options = {}) {
        var selected;
        var assetSubGroupSelectbox = $(options.elementId).find('.asset-sub-group-select');

        if (parseValue(options.selected) != '') {
            selected = (Object.keys(options.selected).length > 0) ? Object.values(options.selected) : [];
        } else {
            selected = [];
        }

        assetSubGroupSelectbox.html("").trigger("change"); // Reset selectbox
        $.ajax({
            url: formApiUrl("admin/asset/group/autocomplete", { parent: true,limit: 10 }),
            type: "get",
            dataType: "json",
            headers: {
                Authorization: `Bearer ${wapLogin.getToken()}`,
            },
        }).done((res) => {
            assetSubGroupSelectbox.append(new Option("Select", "", false, false)); // Load initial select
            if (res.status == "success") {
                if (res.asset_groups) {
                    var assetSubGroups = res.asset_groups;
                    var assetSubGroupOption;

                    $.each(assetSubGroups, function (bi, group) {
                        if (selected.find((value) => {
                            return value == group.id
                        })) {
                            assetSubGroupOption = new Option(group.name, group.id, true, true);
                        } else {
                            assetSubGroupOption = new Option(group.name, group.id, false, false);
                        }

                        assetSubGroupSelectbox.append(assetSubGroupOption);
                    });
                    assetSubGroupSelectbox.trigger("change");
                }
            } else if (res.status == "error") {
                console.log(res.message);
            } else {
                console.log("group Autocomlete: Something went wrong!");
            }
        }).fail((xhr, ajaxOptions, errorThrown) => {
            console.log(xhr.responseText + " " + xhr.responseText);
        });
    };

    assetAriaBlock.on('click', '.btn-collapse-toggle', function (e) {
        let collpaseIcon = $(e.currentTarget).find('[data-collapse-icon]');

        if (collpaseIcon.hasClass('collapsed')) {
            $(e.currentTarget).hide();
        } else {
            $(e.currentTarget).show();
        }
        
    });

    // Remove asset block
    assetAriaBlock.on('click', '.btn-remove-asset-row', function (e) {
        e.preventDefault();
        $(this).parents('.asset-row').remove();

        if (assetAriaBlock.find('.asset-row').length <= 0) {
            loadEmptyAssetView();
        }
    });

    $('#asset-select').select2({
        ajax: {
            url: formApiUrl("admin/asset/autocomplete"),
            type: "get",
            dataType: "json",
            headers: {
                Authorization: `Bearer ${wapLogin.getToken()}`,
            },
            data: function (param) {
                return {
                    search: param.term
                }
            },
            processResults: function (data) {
                var resultDatas = [];
                $.each(data.assets, function (cindex, asset) {
                    resultDatas.push({ id: asset.id, text: asset.name });
                });
                return {
                    results: resultDatas
                };
            }
        },

    });

    $('#asset-select').on('select2:select', function (e) {
        let asValue = $(this).val();
        let loadSwal;

        // Get asset detail
        $.ajax({
            url: formApiUrl("admin/asset/detail", { asset_id: asValue }),
            type: "get",
            dataType: "json",
            headers: {
                Authorization: `Bearer ${wapLogin.getToken()}`,
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
        }).done((res) => {
            if (res.status == "success") {
                assetModal.modal('hide'); // Hide modal
                loadAssetView({
                    type: 'exist',
                    asset_id: asValue,
                    detail: res.asset
                });

            } else if (res.status == "error") {
                console.log(res.message);
            } else {
                console.log("group Autocomlete: Something went wrong!");
            }
        }).fail((xhr, ajaxOptions, errorThrown) => {
            console.log(xhr.responseText + " " + xhr.responseText);
        });
    });


    $('#btn_add_asset').click(function (e) {
        e.preventDefault();
        loadAssetView({
            type: 'new'
        });
    });


    // Reset modal on close
    assetModal.find('.close').click(function () {
        $('#asset-select').html('').trigger('change');
    });


});
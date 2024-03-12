
$(function () {
    
    const checklistArea = $('#checklisttask--deatils--area [data-container="checklistArea"]');
    
    $('[data-toggle="select2"]').select2();

    // Load empty view
    window.loadEmptyDetail = function () {
        checklistArea.find('.detail-area').html('<h6 class="lead text-muted">No Checklist available</h6>');
    }

    // Load detail view
    window.loadDetails = function (href) {
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
            complete: function () {
                loadSwal.close();
            }
        }).then(function (res) {
            if (res.status == 'success') {
                if (res.checklist) {
                    const checklistVal = res.checklist;
                    let headerAddBtn = '';

                    if (checklistVal.status == 1) {
                        status_badge_class = 'badge-success';
                    } else {
                        status_badge_class = 'badge-danger';
                    }

                    checklistArea.find('.detail-area').html(`<div class="d-flex flex-row align-items-center justify-content-start">
                        <h4 class="my-1 lead">${checklistVal.name}</h4>
                        <span class="ml-2 badge ${status_badge_class}">${getStatusText(checklistVal.status)}</span>
                        </div>
                        <span>${parseValue(checklistVal.type.name)}</span>
                    `);

                    let chklstView;
                    switch(checklistVal.type.code) {
                        case 'task':
                            chklstView = new ChecklistViewType1();
                            chklstView.loadTaskAreaView();

                            loadTaskDetails(formApiUrl('employee/checklist/task/list', { checklist_id: checklist_id }));
                            break;
                        case 'task_with_division':
                            chklstView = new ChecklistViewType2();
                            chklstView.loadDivisionAreaView();

                            loadDivisionDetails(formApiUrl('employee/checklist/division/list', { checklist_id: checklist_id }));
                            break;
                        default:
                        console.log('no checklist type');
                    }
                } else {
                    loadEmptyDetail();
                }
            } else if (res.status == 'error') {
                loadEmptyDetail();
                loadToastMessage('error', res);
            } else {
                loadEmptyDetail();
                loadToastMessage('error', {});
            }
        }).catch(function (xhr) {
            loadEmptyDetail();
            loadToastMessage('error', jqXHR);
        });
    }

    // Load Init fuction
    function loadInitFunctions() {
        loadDetails(formApiUrl('employee/checklist/detail', { checklist_id: checklist_id }));
    }

    // Check Login
    $.when(wapLogin.check()).done(function(res) {
        if (res.status == 'success') {
            console.log(res.message);
            appUser = res.user; // Set user infos
            wapLogin.setStatus(res.login);
            loadInitFunctions();
        } else if (res.status == 'error') {
            Swal.fire({
                icon: 'error',
                title: res.message,
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(function() {
                wapLogin.setStatus(res.login);
            });
        } else {
            wapLogin.setStatus(false);
            wapLogin.showDialog(res.message);
        }
    }).fail(function(jqXHR, textStatus) {
        wapLogin.setStatus(false);
        Swal.fire({
            icon: 'error',
            title: 'Something went wrong! Contact support',
            allowOutsideClick: false,
            allowEscapeKey: false,
            // timer: 3000,
            // timerProgressBar: true
        });
    });
});
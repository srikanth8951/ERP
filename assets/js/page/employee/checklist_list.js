
$(function () {
    $('[data-toggle="select2"]').select2();

    function loadInitFunctions() {
        loadChecklistDetails(formApiUrl('employee/checklist/list'));
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
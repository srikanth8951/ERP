$(function () {

    const loginForm = $('#customerLoginForm');

    loginForm.find('.rememberme.custom-checkbox').click(function (e) {
        e.preventDefault();
        
        if ($(this).find('input[name="remember_me"]:checked').length > 0) {
            $(this).find('input[name="remember_me"]').removeAttr('checked').val(0);
        } else {
            $(this).find('input[name="remember_me"]').attr('checked', true).val(1);
        }
    });
    
    var loginFormValidator = loginForm.validate({
        onkeyup: function (element) {
            $(element).valid();
        },
        onclick: function (element) {
            $(element).valid();
        },
        rules: {
            user_name: {
                required: true
            },
            user_password: {
                required: true
            },
        },
        messages: {       
            user_name: {
                required: 'Specify email'
            },
            user_password: {
                required: 'Specify password'
            }
        }
    }); 

    loginForm.submit(function (e) {
        e.preventDefault();

        if (loginFormValidator.valid()) {
            var formData = new FormData(loginForm[0]);
            $.ajax({
                type: "POST",
                url: formApiUrl('customer/login'),
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function () {
                    loginForm.find('button[type="submit"]').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>&nbsp;loading');
                },
                success: function (response) {
                    if (response.status == 'success') {
                        toastr.success(response.message, 'Message');
                        wapLogin.setData({
                            'check': formData.get('remember_me'),
                            'auth_token': response.user.auth_token
                        }).then(function () {
                            window.location.href = formUrl(response.link);
                        });
                        
                    } else if(response.status == 'error') {
                        toastr.error(response.message, 'Error');
                    } else {
                        toastr.error('No response status!', 'Error');
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log(xhr);
                    toastr.error(`${textStatus} <br />${errorThrown}` , 'Error');
                },
                complete: function () {
                    loginForm.find('button[type="submit"]').removeAttr('disabled').html('Login');
                },
            });
        }
    });
});
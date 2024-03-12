
$(function() {

	const forgotFormByEmail = $('#forgotFormByEmail');
	var forgotFormByEmailValidator;

	class ForgottenPassword {

		resetForgotForms() {
			forgotFormByEmail[0].reset();
			forgotFormByEmailValidator.resetForm();
		}

		load() {

			// Form Email
			forgotFormByEmailValidator = forgotFormByEmail.validate({
                onkeyup: function(element){$(element).valid();},
                onclick: function(element){$(element).valid();},
                rules: {
                    user_email: {
                        required: true,
                        email: true
                    },
                },
                messages: {       
                    user_email: {
                        required: 'Specify email address',
                        email: 'Specify valid email address'
                    }
                }
            }); 

			forgotFormByEmail.submit((e) => {
				e.preventDefault();
				
				if(forgotFormByEmail.valid()){
					this.callForgotEmailSubmit();
	            }
			});
	    }

        callForgotEmailSubmit() {
            let loadSwal;
            var formData = new FormData(forgotFormByEmail[0]);
                    
            $.ajax({
                url: formApiUrl('forgot_password/init_reset_by_email'),
                type: 'post',
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                cache: false,
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
            }).then(function(res) {
                if(res.status == 'success') {
                        
                    Swal.fire({
                        icon: 'success',
                        title: res.message,
                        text: res.message_info,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true
                    }).then(() => {
                        // Load OTP Password module
                        const otpPasswordReset = new PasswordResetOTP({
                            args: {
                                user_email: forgotFormByEmail.find('[name="user_email"]').val()
                            }
                        });
                        otpPasswordReset.render();
                    });
                } else if(res.status == 'error') {
                    toastr.error(res.message);
                } else {
                    toastr.error('No response status', 'Error');
                }
            }).catch(function(jqXHR, textStatus, errorThrown) {
                toastr.error(`${textStatus} : ${errorThrown}`, 'Error');
            });
        }

	}

    // Check Login
    if(parseValue(wapLogin.getToken()) != '') {
        wapLogin.setToken('');
    }
	
    const forgotPassword = new ForgottenPassword();
    forgotPassword.load();
});
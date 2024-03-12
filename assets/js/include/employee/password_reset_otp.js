/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: otp reset password js
 */


'use strict'

class PasswordResetOTP {
    constructor(options) {
        this.loadSwal = {};
        this.sectionContent = '';
        this.args = options.args,
            this.resetOTPForm = {};
    }

    render() {
        var resetOTPModalContent = '<div class="card border-0">' +
            '<div class="card-body">' +
            '<div class="heading">' +
            '<h3 class="text-center mt-3 mb-4">Two-Step Verification</h3>' +
            '</div>' +

            '<div class="d-block pt-4">' +
            '<p class="text-muted text-3 text-center">Please enter the OTP (one time password) to verify your account. A Code has been sent to <span class="text-dark text-4">*******' + this.args.user_email.substr(-3) + '</span></p>' +
            '<form id="resetOTPForm" class="form-border" method="post">' +
            '<div class="form">' +
            '<div class="form-group">' +
            '<input name="email_otp" type="text" class="form-control border-2 text-center text-6 px-0 py-2" required="" autocomplete="off">' +
            '</div>' +
            '</div>' +
            '<button class="btn btn-primary btn-block shadow-none my-4" type="submit">Verify</button>' +
            '</form>' +
            // '<p class="text-2 text-center">Not received your code? <a class="btn-link" href="#">Resend code</a></p>' +                    
            '</div>' +

            '</div>' +
            '</div>';

        var loadResetSwal = Swal.fire({
            html: resetOTPModalContent,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            showCloseButton: true,
            closeButtonHtml: '&times;'
        });

        this.resetOTPForm = $('#resetOTPForm');
        this.loadMethods();
    }

    loadMethods() {

        this.resetOTPForm.validate({
            onkeyup: function (element) { $(element).valid(); },
            onclick: function (element) { $(element).valid(); },
            rules: {
                email_otp: {
                    required: true
                }
            },
            messages: {
                email_otp: {
                    required: 'Specify OTP'
                }
            },
            errorPlacement: (error, element) => {
                error.addClass("invalid-feedback");

                if (element.prop("type") === "checkbox" || element.prop("type") === "radio") {
                    error.insertAfter(element.next("label"));
                    error.appendTo(element.parents('.ele-jqValid'));
                } else {
                    error.insertAfter(element);
                }
            }
        });

        this.resetOTPForm.submit((e) => {
            e.preventDefault();

            if (this.resetOTPForm.valid()) {
                this.verify();
            }
        });
    }

    verify() {
        let loadSwal;

        var formData = new FormData();
        formData.append('recover_email', this.args.user_email);
        formData.append('recover_otp', this.resetOTPForm.find('[name="email_otp"]').val());

        $.ajax({
            url: formApiUrl('employee/forgot_password/recover_by_email'),
            type: 'post',
            dataType: 'json',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: () => {
                loadSwal = Swal.fire({
                    html: '<div class="loader-block my-4 text-center d-inline-block">' + loaderContent + '</div>',
                    customClass: {
                        popup: 'col-6 col-sm-5 col-md-3 col-lg-2'
                    },
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false
                });
            },
            success: (res) => {
                if (res.status == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: res.message,
                        text: res.verify_message,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true
                    }).then(() => {
                        // Load password reset module
                        const passwordReset = new PasswordReset({
                            url: formApiUrl('employee/forgot_password/reset_by_email'),
                            args: {
                                recover_email: this.args.user_email,
                                recover_otp: this.resetOTPForm.find('[name="email_otp"]').val()
                            }
                        });
                        passwordReset.render();
                    });
                } else if (res.status == 'error') {
                    toastr.error(res.message);
                } else {
                    toastr.error('No response status', 'Error');
                }
            },
            error: (error) => {
                console.log(error);
                toastr.error(error.statusText, 'Error');
            },
            complete: () => {
                loadSwal.close();
            }
        });

    }

}


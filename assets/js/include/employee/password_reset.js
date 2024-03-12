/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: password reset js
 */


'use strict'

class PasswordReset {
    constructor(options) {
        this.loadSwal = {};
        this.sectionContent = '';
        this.resetModule = {
            params: options.args,
            url: options.url
        },
            this.resetPasswordForm = {};
    }

    render() {
        var resetModalContent = `<div class="card border-0">
            <div class="card-body">
                <div class="heading">
                    <h4>Reset Password</h4>
                </div>
                <div class="d-block pt-4">
                    <form id="resetPasswordForm">
                        <div class="form-group">
                            <input type="password" name="user_password" id="user-password-field" class="form-control"
                                placeholder="Password" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <input type="password" name="user_confirm_password" class="form-control"
                                placeholder="Confirm Password" autocomplete="off" />
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Update</button>
                    </form>
                </div>
            </div>
        </div>`;

        var loadResetSwal = Swal.fire({
            html: resetModalContent,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            showCloseButton: true,
            closeButtonHtml: '&times;'
        });

        this.resetPasswordForm = $('#resetPasswordForm');
        this.loadMethods();
    }

    loadMethods() {

        this.resetPasswordForm.validate({
            onkeyup: function (element) { $(element).valid(); },
            onclick: function (element) { $(element).valid(); },
            rules: {
                user_password: {
                    required: true
                },
                user_confirm_password: {
                    required: true,
                    equalTo: '#user-password-field'
                }
            },
            messages: {
                user_password: {
                    required: 'Specify New Password'
                },
                user_confirm_password: {
                    required: 'Specify Confirm Password',
                    equalTo: 'Password does not match'
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

        this.resetPasswordForm.submit((e) => {
            e.preventDefault();

            if (this.resetPasswordForm.valid()) {
                this.save();
            }
        });
    }

    save() {
        let loadSwal;

        var formData = new FormData(this.resetPasswordForm[0]);
        if (typeof this.resetModule.params != 'undefined') {

        }
        Object.entries(this.resetModule.params).forEach(([key, value]) => {
            formData.append(key, value);
        });
        $.ajax({
            url: this.resetModule.url,
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
                        window.location.href = base_url+'employee';
                    });
                } else if (res.status == 'error') {
                    toastr.error(res.message);
                } else {
                    toastr.error('No response status', 'Error');
                }
            },
            error: (error) => {
                console.log(error);
                toastr.error(error.statusText);
            },
            complete: () => {
                loadSwal.close();
            }
        });

    }

}


/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: Check Login js
 */

"use strict";
var wapLogin = {};
const appLogout = "#app-logout";

$(function () {
  window.wapLogin = {
    loadSwal: {},
    setData: function (data) {
      return new Promise(function (resolve, reject) {
        let baseUrl;
        let rememberMe;
        let authToken;

        if (parseValue(data.check) == 1) {
          baseUrl = $.cookie("base_url", base_url, {
            expires: 7,
            path: domain_name,
          });
          rememberMe = $.cookie("remember_me", data.check, {
            expires: 7,
            path: domain_name,
          });
          authToken = $.cookie("auth_token", data.auth_token, {
            expires: 7,
            path: domain_name,
          });
        } else {
          baseUrl = window.sessionStorage.setItem("base_url", base_url);
          rememberMe = window.sessionStorage.setItem("remember_me", data.check);
          authToken = window.sessionStorage.setItem(
            "auth_token",
            data.auth_token
          );
        }

        Promise.all([baseUrl, rememberMe, authToken])
          .then(function () {
            resolve(true);
          })
          .catch(function () {
            reject(true);
          });
      });
    },
    clearData: function (data) {
      return new Promise(function (resolve, reject) {
        var cookies = $.cookie();
        for (var cookie in cookies) {
          $.removeCookie(cookie);
        }

        window.sessionStorage.clear();
        window.localStorage.removeItem("permissions");

        setTimeout(function () {
          resolve(data);
        }, 1000);
      });
    },
    getToken: function () {
      if ($.cookie("remember_me") == 1) {
        return parseValue($.cookie("auth_token"));
      } else {
        return parseValue(window.sessionStorage.getItem("auth_token"));
      }
    },
    getUser: function () {
      if (appUser) {
        return appUser;
      } else {
        return "";
      }
    },
    check: function () {
      var dfd = jQuery.Deferred();
      $.ajax({
        url: formApiUrl("customer/checkLoggedin"),
        type: "post",
        headers: {
          Authorization: `Bearer ${this.getToken()}`,
        },
        dataType: "json",
        beforeSend: function () {
          this.loadSwal = Swal.fire({
            position: "bottom",
            html: '<div class="my-1 text-center d-inline-block"><h6 class="m-0">checking Login...</h6></div>',
            customClass: {
              popup: "col-6 col-sm-5 col-md-4 col-lg-4",
            },
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
          });
        },
        success: function (res) {
          dfd.resolve(res);
          $("#profile-image").html(`<img src="${res.user.image}" alt="user" class="rounded-circle">`)
        },
        error: function (error) {
          console.log(error);
          let errResponse = error.responseJSON;
          if (error.status == 401) {
            if (
              typeof errResponse.login != "undefined" &&
              errResponse.login == false
            ) {
              Swal.fire({
                icon: "error",
                title: errResponse.message,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
              }).then(function () {
                wapLogin.showPrompt(dfd);
              });
            } else {
              wapLogin.showPrompt(dfd);
            }
          } else {
            dfd.reject(error);
          }
        },
        complete: function () {
          this.loadSwal.close();
        },
      });

      return dfd.promise();
    },
    setStatus: function (lStatus) {
      if (lStatus == true) {
        processStatus = true;
      } else {
        processStatus = false;
        // window.location.href = formUrl('');
      }
    },
    setPermissions: function (permissions) {
      let storagePermissions = window.localStorage.getItem("permissions");
      if (storagePermissions !== "null") {
        window.localStorage.removeItem("permissions"); // Remove user permissions
      }
      window.localStorage.setItem("permissions", permissions); // Set user permissions
    },
    checkPermission: function (permission_name) {
      let permissions = window.localStorage.getItem("permissions");
      if (typeof permissions != "undefined") {
        let permissionsParsed = JSON.parse(permissions);
        return permissionsParsed.indexOf(permission_name);
      } else {
        return 0;
      }
    },
    removePermissions: function () {
      window.localStorage.removeItem("permissions");
    },
    applyPermissions: function () {
      $("[data-permission]").each(function () {
        let permitObj = $(this);
        let permit = permitObj.attr("data-permission");

        if (wapLogin.checkPermission(permit) < 0) {
          permitObj.fadeOut().remove();
        }
      });
    },
    showDialog: function (message) {
      Swal.fire({
        icon: "error",
        title: message,
        allowOutsideClick: false,
        allowEscapeKey: false,
        confirmButtonText: "Login",
      }).then(function (result) {
        if (result.isConfirmed) {
          window.location.href = base_url;
        }
      });
    },
    showPrompt: function (dfdPromise) {
      var loginModalContent = `<div class="card border-0">
                <div class="card-body">
                    <h3 class="text-center m-0">
                        <a href="javascript:void(0)" class="logo logo-admin img-fluid"><img class="img-fluid" src="${formUrl(
                          "assets/images/sterling-wilson.png"
                        )}" alt="logo" /></a>
                    </h3>
            
                    <div class="d-block">
                        <p class="text-muted text-center">Sign in to continue.</p>
                        <form class="form-horizontal m-t-30" id="userLoginForm" method="post">
                            <div class="form-group text-left">
                                <input type="text" name="user_name" class="form-control" id="user_name" placeholder="Usename/Email/Mobile"
                                    required />
                            </div>
                            <div class="form-group">
                                <a href="<?= base_url() ?>admin/password/forgot/" class="text-muted float-right" style="font-size:14px;"><i class="mdi mdi-lock"></i> Forgot password?</a>
                                <!-- <input type="password" class="form-control"   placeholder="Enter password" required /> -->
                                <div class="input-field">
                                    <input type="password" class="form-control password" id="userpassword" name="user_password" placeholder="Enter password" required>
                                    <i class="mdi mdi-eye-off showHidePw" style="top:65%;"></i>
                                </div>
                            </div>
                            <div class="form-group text-left">
                                <div class="rememberme custom-control custom-checkbox">
                                    <input type="checkbox" name="remember_me" class="custom-control-input" value="0" id="customControlInline">
                                    <label class="custom-control-label" for="customControlInline">Remember me</label>
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button class="btn btn-primary btn-block waves-effect waves-light" type="submit">Log In</button>
                            </div>
                            <div class="form-group m-t-10 mb-0 row">
                                <div class="col-12 m-t-20">
                                    <a href="${formUrl(
                                      "recover_password"
                                    )}" class="text-muted"><i class="mdi mdi-lock"></i>
                                        Forgot your password?</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>`;

      var loadLoginSwal = Swal.fire({
        html: loginModalContent,
        customClass: {
          popup: "col-md-5 col-lg-4",
        },
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
      });

      const pwShowHide = document.querySelectorAll(".showHidePw");
      const pwFields = document.querySelectorAll(".password");

      pwShowHide.forEach((eyeIcon) => {
        eyeIcon.addEventListener("click", () => {
          pwFields.forEach((pwField) => {
            if (pwField.type === "password") {
              pwField.type = "text";

              pwShowHide.forEach((icon) => {
                icon.classList.replace("mdi-eye-off", "mdi-eye");
              });
            } else {
              pwField.type = "password";

              pwShowHide.forEach((icon) => {
                icon.classList.replace("mdi-eye", "mdi-eye-off");
              });
            }
          });
        });
      });

      const loginForm = $("#userLoginForm");
      loginForm.find(".rememberme.custom-checkbox").click(function (e) {
        e.preventDefault();

        if ($(this).find('input[name="remember_me"]:checked').length > 0) {
          $(this)
            .find('input[name="remember_me"]')
            .removeAttr("checked")
            .val(0);
        } else {
          $(this)
            .find('input[name="remember_me"]')
            .attr("checked", true)
            .val(1);
        }
      });

      loginForm.validate({
        rules: {
          user_name: {
            required: true,
          },
          user_password: {
            required: true,
          },
        },
        messages: {
          user_name: {
            required: "Specify user name",
          },
          user_password: {
            required: "Specify password",
          },
        },
      });

      // Form submit
      loginForm.submit(function (e) {
        e.preventDefault();
        var loadSwal = {};
        var valid = loginForm.valid();
        if (valid) {
          $.when(wapLogin.login(loginForm))
            .done(function (res) {
              if (res.status == "success") {
                if (res.user) {
                  var user = res.user;
                  wapLogin
                    .setData({
                      check: loginForm.find('[name="remember_me"]').val(),
                      auth_token: res.user.auth_token,
                    })
                    .then(function () {
                      wapLogin.setPermissions(user.permission);

                      Swal.fire({
                        icon: "success",
                        title: res.message,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                      }).then(function () {
                        dfdPromise.resolve(res);
                      });
                    })
                    .catch(function () {
                      toastr.error(res.message);
                      wapLogin.showPrompt(dfdPromise);
                    });
                } else {
                  toastr.error("No User response");
                  wapLogin.showPrompt(dfdPromise);
                }
              } else if (res.status == "error") {
                toastr.error(res.message);
                wapLogin.showPrompt(dfdPromise);
              } else {
                toastr.error("No response status", "Error");
                wapLogin.showPrompt(dfdPromise);
              }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
              toastr.error(`${textStatus} : ${errorThrown}`, "Error");
              wapLogin.showPrompt(dfdPromise);
            });
        }
      });
    },
    logout: function () {
      var dfd = jQuery.Deferred();

      $.ajax({
        url: formApiUrl("customer/logout"),
        type: "post",
        dataType: "json",
        headers: {
          Authorization: `Bearer ${this.getToken()}`,
        },
        beforeSend: function () {
          this.loadSwal = Swal.fire({
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
        success: function (res) {
          dfd.resolve(res);
        },
        error: function (error) {
          dfd.reject(error);
        },
        complete: function () {
          this.loadSwal.close();
        },
      });

      return dfd.promise();
    },
    login: function (loginForm) {
      let loadSwal;
      var loginDFD = $.Deferred();
      $.ajax({
        url: formApiUrl("login"),
        type: "post",
        dataType: "json",
        data: loginForm.serialize(),
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
        success: function (res) {
          loginDFD.resolve(res);
        },
        error: function (error) {
          loginDFD.reject(error);
        },
        complete: function () {
          loadSwal.close();
        },
      });

      return loginDFD.promise();
    },
  };

  // Logout
  $(document)
    .find(appLogout)
    .click(function (e) {
      e.preventDefault();
      wapLogin
        .logout()
        .then(function (res) {
          if (res.status == "success") {
            wapLogin.clearData().then(function () {
              setTimeout(function () {
                window.location.href = base_url;
              }, 2000);
            }); // Clear Stored login data

            Swal.fire({
              icon: "success",
              title: res.message,
              allowOutsideClick: false,
              allowEscapeKey: false,
              showConfirmButton: false,
            });
          } else if (res.status == "error") {
            toastr.error(res.message);
          } else {
            toastr.error("No response status", "Error");
          }
        })
        .catch(function (error, textStatus, errorThrown) {
          let errResponse = error.responseJSON;
          if (error.status == 401) {
            toastr.error(errResponse.message);
          } else {
            toastr.error(`${textStatus} : ${errorThrown}`, "Error");
          }
        });
    });
});

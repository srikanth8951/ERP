/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: user js
 */

var appuser = {};

$(function () {
  const userDetailArea = $("#user--deatils--area");
  const userProfileForm = $("#userProfileForm");
  var userProfileFormValidator;

  // load details
  window.loadUserDetail = function () {
    let loadSwal;

    $.ajax({
      url: formApiUrl("admin/profile/detail"),
      type: "get",
      dataType: "json",
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
      success: function (res) {
        if (res.status == "success") {
          let userDetail = res.user.data;
          if (parseValue(res.user.data) != "") {
            userProfileForm
              .find('[name="first_name"]')
              .val(userDetail.first_name);
            userProfileForm
              .find('[name="last_name"]')
              .val(userDetail.last_name);
            userProfileForm.find('[name="email"]').val(userDetail.email);
            userProfileForm.find('[name="username"]').val(userDetail.username);
              userProfileForm.find('[name="mobile"]').val(userDetail.mobile);
              userProfileForm.find('#user-img-profile').attr('src', userDetail.image);
          } else {
            toastr.info("No customer data");
          }
        } else if (res.status == "error") {
          toastr.error(res.message);
        } else {
          toastr.error("No response status", "Error");
        }
      },
      error: function (xhr, textStatus, errorThrown) {
        toastr.error(`${textStatus} <br />${errorThrown}`, "Error");
      },
      complete: function () {
        loadSwal.close();
      },
    });
  };

  // customer Form
  userProfileFormValidator = userProfileForm.validate({
    onkeyup: function (element) {
      $(element).valid();
    },
    onclick: function (element) {
      $(element).valid();
    },
    rules: {
      first_name: {
        required: true,
      },
      last_name: {
        required: true,
      },
      email: {
        required: true,
        email: true,
      },
      username: {
        required: true,
      },
      mobile: {
        required: true,
      },
    },
    messages: {
      first_name: {
        required: "Specify customer name",
      },
      last_name: {
        required: "Specify contact person name",
      },
      email: {
        required: "Specify email address",
        email: "Specify valid email address",
      },
      mobile: {
        required: "Specify mobile number",
      },
      username: {
        required: "Specify username",
      },
    },
    errorPlacement: function (error, element) {
      // Add the `invalid-feedback` class to the error element
      error.addClass("invalid-feedback");

      if (
        element.prop("type") === "checkbox" ||
        element.attr("data-toggle") == "select2"
      ) {
        // error.insertAfter( element.next( "label" ) );
        element.parents(".ele-jqValid").append(error);
      } else {
        error.insertAfter(element);
      }
    },
  });

  function savecustomer() {
    let loadSwal;
    var formData = new FormData(userProfileForm[0]);

    $.ajax({
      url: formActionUrl,
      type: "post",
      data: formData,
      dataType: "json",
      processData: false,
      contentType: false,
      cache: false,
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
      },
    })
      .done(function (res) {
        if (res.status == "success") {
          toastr.success(res.message);
          userProfileForm[0].reset(); // Reset form
          setTimeout(function () {
            window.location.href = formUrl("admin/customer");
          });
        } else if (res.status == "error") {
          toastr.error(res.message);
        } else {
          toastr.error("No response status", "Error");
        }
      })
      .fail(function (jqXHR, textStatus, errorThrown) {
        toastr.error(`${textStatus} - ${errorThrown}`, "Error");
      });
  }

  userProfileForm.submit(function (e) {
    e.preventDefault();
    if (userProfileFormValidator.valid()) {
      savecustomer();
    }
  });
});

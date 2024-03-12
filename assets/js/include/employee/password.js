/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: employee js
 */

var appemployee = {};

$(function () {
  const employeePasswordChangeForm = $("#employeePasswordChangeForm");
  var employeePasswordChangeFormValidator;

  // employee Form
  employeePasswordChangeFormValidator = employeePasswordChangeForm.validate({
    onkeyup: function (element) {
      $(element).valid();
    },
    onclick: function (element) {
      $(element).valid();
    },
    rules: {
      user_current_password: {
        required: true,
      },
      user_new_password: {
        required: true,
      },
      confirm_password: {
        required: true,
        equalTo: "#newpassword",
      },
    },
    messages: {
      user_current_password: {
        required: "Specify Current password",
      },
      user_new_password: {
        required: "Specify new password",
      },
      confirm_password: {
        required: "Specify confirm password",
        equalTo: "Your password does not match",
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

  function saveEmployee() {
    let loadSwal;
    var formData = new FormData(employeePasswordChangeForm[0]);

    $.ajax({
      url: formApiUrl("change_password"),
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
          employeePasswordChangeForm[0].reset(); // Reset form
          setTimeout(function () {
            window.location.reload();
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

  employeePasswordChangeForm.submit(function (e) {
    e.preventDefault();
     
    if (employeePasswordChangeFormValidator.valid()) {
      saveEmployee();
    }
  });
});

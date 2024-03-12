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
      url: formApiUrl("customer/profile/detail"),
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
      success: async function (res) {
        if (res.status == "success") {
          let customerDetail = res.customer.data;
          if (parseValue(res.customer.data) != "") {
            userProfileForm
              .find('[name="company_name"]')
              .val(customerDetail.company_name);
            userProfileForm
              .find('[name="billing_address_contact_name"]')
              .val(customerDetail.billing_address_contact_name);
            userProfileForm
              .find('[name="billing_address_email"]')
              .val(customerDetail.billing_address_email);
            userProfileForm
              .find('[name="username"]')
              .val(customerDetail.username);
            userProfileForm
              .find('[name="billing_address_mobile"]')
              .val(customerDetail.billing_address_mobile);
            userProfileForm
              .find('[name="billing_address"]')
              .val(customerDetail.billing_address);
            userProfileForm
              .find('[name="billing_address_pincode"]')
              .val(customerDetail.billing_address_pincode);
              userProfileForm
              .find('[name="website"]')
              .val(customerDetail.website);
              userProfileForm
              .find('[name="gst_number"]')
              .val(customerDetail.gst_number);
              userProfileForm
              .find('[name="pan_number"]')
              .val(customerDetail.pan_number);
            userProfileForm
              .find("#user-img-profile")
              .attr("src", customerDetail.profile_image_link);
            
              loadAutocompleteCustomerSectores({
                selected: [customerDetail.sector],
              });

            await loadAutocompleteAddressCountries({
              element: userProfileForm.find(
                '[name="billing_address_country_id"]'
              ),
              params: {
                selected: [customerDetail.billing_address_country],
              },
            });
            await loadAutocompleteAddressStates({
              element: userProfileForm.find(
                '[name="billing_address_state_id"]'
              ),
              params: {
                country_id: customerDetail.billing_address_country,
                selected: [customerDetail.billing_address_state],
              },
            });
            await loadAutocompleteAddressCities({
              element: userProfileForm.find('[name="billing_address_city_id"]'),
              params: {
                state_id: customerDetail.billing_address_state,
                selected: [customerDetail.billing_address_city],
              },
            });
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

  //  customer_sectores Autocomplete
  window.loadAutocompleteCustomerSectores = function (options = {}) {
    var selected;
    var customer_sectoreSelectbox = userProfileForm.find('[name="sector"]');

    if (parseValue(options.selected) != "") {
      selected =
        Object.keys(options.selected).length > 0
          ? Object.values(options.selected)
          : [];
    } else {
      selected = [];
    }

    customer_sectoreSelectbox.html("").trigger("change"); // Reset selectbox
    $.ajax({
      url: formApiUrl("customer/customer_sector/autocomplete"),
      type: "get",
      dataType: "json",
      headers: {
        Authorization: `Bearer ${wapLogin.getToken()}`,
      },
    })
      .done((res) => {
        customer_sectoreSelectbox.append(
          new Option("Select", "", false, false)
        ); // Load initial select
        if (res.status == "success") {
          console.log(res);
          if (res.customer_sectores) {
            var customer_sectores = res.customer_sectores;
            var customer_sectoreOption;

            $.each(customer_sectores, function (bi, customer_sectore) {
              if (
                selected.find((value) => {
                  return value == customer_sectore.id;
                })
              ) {
                customer_sectoreOption = new Option(
                  customer_sectore.name,
                  customer_sectore.id,
                  true,
                  true
                );
              } else {
                customer_sectoreOption = new Option(
                  customer_sectore.name,
                  customer_sectore.id,
                  false,
                  false
                );
              }

              customer_sectoreSelectbox.append(customer_sectoreOption);
            });
            customer_sectoreSelectbox.trigger("change");
          }
        } else if (res.status == "error") {
          console.log(res.message);
        } else {
          console.log("customer_sectore Autocomlete: Something went wrong!");
        }
      })
      .fail((xhr, ajaxOptions, errorThrown) => {
        console.log(xhr.responseText + " " + xhr.responseText);
      });
  };

  // address country Autocomplete
  window.loadAutocompleteAddressCountries = function (options = {}) {
    return new Promise(function (resolve, reject) {
      var selected;
      var countrySelectbox = options.element;

      if (parseValue(options.params.selected) != "") {
        selected =
          Object.keys(options.params.selected).length > 0
            ? Object.values(options.params.selected)
            : [];
      } else {
        selected = [];
      }

      countrySelectbox.html("").trigger("change"); // Reset selectbox
      $.ajax({
        url: formApiUrl("customer/localisation/country/list"),
        type: "get",
        dataType: "json",
        headers: {
          Authorization: `Bearer ${wapLogin.getToken()}`,
        },
      })
        .done((res) => {
          countrySelectbox.append(new Option("Select", "", false, false)); // Load initial select
          if (res.status == "success") {
            if (res.localisation.countries) {
              var localisations = res.localisation.countries;
              var localisationOption;

              $.each(localisations, function (bi, country) {
                if (
                  selected.find((value) => {
                    return value == country.id;
                  })
                ) {
                  localisationOption = new Option(
                    country.name,
                    country.id,
                    true,
                    true
                  );
                } else {
                  localisationOption = new Option(
                    country.name,
                    country.id,
                    false,
                    false
                  );
                }

                countrySelectbox.append(localisationOption);

                localisationOption.setAttribute(
                  "data-dial-code",
                  `+${country.dial_code}`
                );
              });

              countrySelectbox.trigger("change");
            }
          } else if (res.status == "error") {
            console.log(res.message);
          } else {
            console.log("country Autocomlete: Something went wrong!");
          }
          resolve(res);
        })
        .fail((xhr, ajaxOptions, errorThrown) => {
          console.log(xhr.responseText + " " + xhr.responseText);
          reject(xhr);
        });
    });
  };

  // address state Autocomplete
  window.loadAutocompleteAddressStates = function (options = {}) {
    return new Promise(function (resolve, reject) {
      var selected;
      var country_id;
      var stateSelectbox = options.element;

      if (parseValue(options.params.country_id) != "") {
        country_id = options.params.country_id;
      } else {
        country_id = 0;
      }

      if (parseValue(options.params) != "") {
        optionParams = options.params;
      } else {
        optionParams = {};
      }

      if (parseValue(options.params.selected) != "") {
        selected =
          Object.keys(options.params.selected).length > 0
            ? Object.values(options.params.selected)
            : [];
      } else {
        selected = [];
      }

      stateSelectbox.html("").trigger("change"); // Reset selectbox
      $.ajax({
        url: formApiUrl("customer/localisation/state/list", optionParams),
        type: "get",
        dataType: "json",
        headers: {
          Authorization: `Bearer ${wapLogin.getToken()}`,
        },
      })
        .done((res) => {
          stateSelectbox.append(new Option("Select", "", false, false)); // Load initial select
          if (res.status == "success") {
            if (res.localisation.states) {
              var localisations = res.localisation.states;
              var localisationOption;

              $.each(localisations, function (bi, state) {
                if (
                  selected.find((value) => {
                    return value == state.id;
                  })
                ) {
                  localisationOption = new Option(
                    state.name,
                    state.id,
                    true,
                    true
                  );
                } else {
                  localisationOption = new Option(
                    state.name,
                    state.id,
                    false,
                    false
                  );
                }

                stateSelectbox.append(localisationOption);
              });
              stateSelectbox.trigger("change");
            }
          } else if (res.status == "error") {
            console.log(res.message);
          } else {
            console.log("state Autocomlete: Something went wrong!");
          }
          resolve(res);
        })
        .fail((xhr, ajaxOptions, errorThrown) => {
          console.log(xhr.responseText + " " + xhr.responseText);
          reject(xhr);
        });
    });
  };

  // address city Autocomplete
  window.loadAutocompleteAddressCities = function (options = {}) {
    return new Promise(function (resolve, reject) {
      var selected;
      var state_id;
      var citySelectbox = options.element;
      let optionParams = {};

      if (parseValue(options.params) != "") {
        optionParams = options.params;
      }

      if (parseValue(options.paramsstate_id) != "") {
        state_id = options.paramsstate_id;
      } else {
        state_id = 0;
      }

      if (parseValue(options.params.selected) != "") {
        selected =
          Object.keys(options.params.selected).length > 0
            ? Object.values(options.params.selected)
            : [];
      } else {
        selected = [];
      }

      citySelectbox.html("").trigger("change"); // Reset selectbox
      $.ajax({
        url: formApiUrl("customer/localisation/city/list", optionParams),
        type: "get",
        dataType: "json",
        headers: {
          Authorization: `Bearer ${wapLogin.getToken()}`,
        },
      })
        .done((res) => {
          citySelectbox.append(new Option("Select", "", false, false)); // Load initial select
          if (res.status == "success") {
            if (res.localisation.cities) {
              var localisations = res.localisation.cities;
              var localisationOption;

              $.each(localisations, function (bi, city) {
                if (
                  selected.find((value) => {
                    return value == city.id;
                  })
                ) {
                  localisationOption = new Option(
                    city.name,
                    city.id,
                    true,
                    true
                  );
                } else {
                  localisationOption = new Option(
                    city.name,
                    city.id,
                    false,
                    false
                  );
                }

                citySelectbox.append(localisationOption);
              });
              citySelectbox.trigger("change");
            }
          } else if (res.status == "error") {
            console.log(res.message);
          } else {
            console.log("state Autocomlete: Something went wrong!");
          }
          resolve(res);
        })
        .fail((xhr, ajaxOptions, errorThrown) => {
          console.log(xhr.responseText + " " + xhr.responseText);
          reject(xhr);
        });
    });
  };

  /* load state autocomplete after country select */
  userProfileForm
    .find('[name="billing_address_country_id"]')
    .on("select2:select", function () {
      if (parseValue($(this).val()) != "") {
        loadAutocompleteAddressStates({
          element: userProfileForm.find('[name="billing_address_state_id"]'),
          params: {
            country_id: $(this).val(),
          },
        });
      } else {
        userProfileForm
          .find('[name="billing_address_state_id"]')
          .html("")
          .trigger("change");
      }
    });

  /* set country dial code after country select/change*/
  userProfileForm
    .find('[name="billing_address_country_id"]')
    .change(function () {
      if (parseValue($(this).val()) != "") {
        $("#billing-address-country-dial-code").html(
          $(this).find(":selected").attr("data-dial-code")
        );
      } else {
        $("#billing-address-country-dial-code").html("");
      }
    });

  /* load city autocomplete after state select */
  userProfileForm
    .find('[name="billing_address_state_id"]')
    .on("select2:select", function () {
      if (parseValue($(this).val()) != "") {
        loadAutocompleteAddressCities({
          element: userProfileForm.find('[name="billing_address_city_id"]'),
          params: {
            state_id: $(this).val(),
          },
        });
      } else {
        userProfileForm
          .find('[name="billing_address_city_id"]')
          .html("")
          .trigger("change");
      }
    });

  // customer Form
  userProfileFormValidator = userProfileForm.validate({
    onkeyup: function (element) {
      $(element).valid();
    },
    onclick: function (element) {
      $(element).valid();
    },
    rules: {
      company_name: {
        required: true,
      },
      billing_address_contact_person_name: {
        required: true,
      },
      billing_address_email: {
        required: true,
        email: true,
      },
      username: {
        required: true,
      },
      billing_address_mobile: {
        required: true,
      },
    },
    messages: {
      company_name: {
        required: "Specify customer name",
      },
      billing_address_contact_person_name: {
        required: "Specify contact person name",
      },
      billing_address_email: {
        required: "Specify email address",
        email: "Specify valid email address",
      },
      billing_address_mobile: {
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
      url: formApiUrl("customer/profile/edit"),
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
            window.location.href = formUrl("customer/dashboard/");
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

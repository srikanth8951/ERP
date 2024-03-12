/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: customer js
 */

var appcustomer = {};

$(function () {
  const customerDetailArea = $("#customer--deatils--area");
  const customerForm = $("#customerForm");
  var customerFormValidator;

  $("#btn-pick-location").click(function (e) {
    e.preventDefault();
    getPlacePickDetail(customerForm.find('[name="input-place-search"]').val());
    // $('#placeSearchModal').find('#input-place-search').val();
  });

  $("#btn-search-location").click(function (e) {
    e.preventDefault();
    getPlaceSearchDetail().then(function (data) {
      customerForm
        .find('[name="customer_location_lattitude"]')
        .val(data.geometry.location.lat());
      customerForm
        .find('[name="customer_location_longitude"]')
        .val(data.geometry.location.lng());
      customerForm.find(".customer-map-location").html(`
                <p class="mb-1"><label class="mb-0">Address:</label> ${
                  data.formatted_address
                }</p>
                <p class="mb-0"><label class="mb-0">Lattitude:</label> ${data.geometry.location.lat()} <br /> <label class="mb-0">Longitude:</label> ${data.geometry.location.lng()}</p>
            `);
    });
  });

  // billing address  country Autocomplete
  window.loadAutocompleteBillingAddressCountry = function (options = {}) {
    var selected;
    var countrySelectbox = customerForm.find(
      '[name="billing_address_country_id"]'
    );
    var url = window.location.href.replace(/\/$/, "");
    var formType = url.substr(url.lastIndexOf("/") + 1);

    if (parseValue(options.selected) != "") {
      selected =
        Object.keys(options.selected).length > 0
          ? Object.values(options.selected)
          : [];
    } else {
      selected = [];
    }

    countrySelectbox.html("").trigger("change"); // Reset selectbox
    $.ajax({
      url: formApiUrl("admin/localisation/country/list"),
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

            // if (formType == "add") {
            //   countrySelectbox.val("103").trigger("change");
            // }

            countrySelectbox.trigger("change");
          }
        } else if (res.status == "error") {
          console.log(res.message);
        } else {
          console.log("country Autocomlete: Something went wrong!");
        }
      })
      .fail((xhr, ajaxOptions, errorThrown) => {
        console.log(xhr.responseText + " " + xhr.responseText);
      });
  };

  // billing address state Autocomplete
  window.loadAutocompleteBillingAddressStates = function (options = {}) {
    var selected;
    var country_id;
    var stateSelectbox = customerForm.find('[name="billing_address_state_id"]');

    if (parseValue(options.country_id) != "") {
      country_id = options.country_id;
    } else {
      country_id = 0;
    }

    if (parseValue(options.selected) != "") {
      selected =
        Object.keys(options.selected).length > 0
          ? Object.values(options.selected)
          : [];
    } else {
      selected = [];
    }

    stateSelectbox.html("").trigger("change"); // Reset selectbox
    $.ajax({
      url: formApiUrl("admin/localisation/state/list", options),
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
      })
      .fail((xhr, ajaxOptions, errorThrown) => {
        console.log(xhr.responseText + " " + xhr.responseText);
      });
  };

  // billing address city Autocomplete
  window.loadAutocompleteBillingAddressCities = function (options = {}) {
    var selected;
    var state_id;
    var citySelectbox = customerForm.find('[name="billing_address_city_id"]');

    if (parseValue(options.state_id) != "") {
      state_id = options.state_id;
    } else {
      state_id = 0;
    }

    if (parseValue(options.selected) != "") {
      selected =
        Object.keys(options.selected).length > 0
          ? Object.values(options.selected)
          : [];
    } else {
      selected = [];
    }

    citySelectbox.html("").trigger("change"); // Reset selectbox
    $.ajax({
      url: formApiUrl("admin/localisation/city/list", options),
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
                localisationOption = new Option(city.name, city.id, true, true);
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
          console.log("city Autocomlete: Something went wrong!");
        }
      })
      .fail((xhr, ajaxOptions, errorThrown) => {
        console.log(xhr.responseText + " " + xhr.responseText);
      });
  };

  // site address  country Autocomplete
  window.loadAutocompleteSiteAddressCountry = function (options = {}) {
    var selected;
    var countrySelectbox = customerForm.find(
      '[name="site_address_country_id"]'
    );
    var url = window.location.href.replace(/\/$/, "");
    var formType = url.substr(url.lastIndexOf("/") + 1);

    if (parseValue(options.selected) != "") {
      selected =
        Object.keys(options.selected).length > 0
          ? Object.values(options.selected)
          : [];
    } else {
      selected = [];
    }

    countrySelectbox.html("").trigger("change"); // Reset selectbox
    $.ajax({
      url: formApiUrl("admin/localisation/country/list"),
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

            // if (formType == "add") {
            //   countrySelectbox.val("103").trigger("change");
            // }

            countrySelectbox.trigger("change");
          }
        } else if (res.status == "error") {
          console.log(res.message);
        } else {
          console.log("country Autocomlete: Something went wrong!");
        }
      })
      .fail((xhr, ajaxOptions, errorThrown) => {
        console.log(xhr.responseText + " " + xhr.responseText);
      });
  };

  // site address state Autocomplete
  window.loadAutocompleteSiteAddressStates = function (options = {}) {
    var selected;
    var country_id;
    var stateSelectbox = customerForm.find('[name="site_address_state_id"]');

    if (parseValue(options.country_id) != "") {
      country_id = options.country_id;
    } else {
      country_id = 0;
    }

    if (parseValue(options.selected) != "") {
      selected =
        Object.keys(options.selected).length > 0
          ? Object.values(options.selected)
          : [];
    } else {
      selected = [];
    }

    stateSelectbox.html("").trigger("change"); // Reset selectbox
    $.ajax({
      url: formApiUrl("admin/localisation/state/list", options),
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
      })
      .fail((xhr, ajaxOptions, errorThrown) => {
        console.log(xhr.responseText + " " + xhr.responseText);
      });
  };

  // site address city Autocomplete
  window.loadAutocompleteSiteAddressCities = function (options = {}) {
    var selected;
    var state_id;
    var citySelectbox = customerForm.find('[name="site_address_city_id"]');

    if (parseValue(options.state_id) != "") {
      state_id = options.state_id;
    } else {
      state_id = 0;
    }

    if (parseValue(options.selected) != "") {
      selected =
        Object.keys(options.selected).length > 0
          ? Object.values(options.selected)
          : [];
    } else {
      selected = [];
    }

    citySelectbox.html("").trigger("change"); // Reset selectbox
    $.ajax({
      url: formApiUrl("admin/localisation/city/list", options),
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
                localisationOption = new Option(city.name, city.id, true, true);
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
      })
      .fail((xhr, ajaxOptions, errorThrown) => {
        console.log(xhr.responseText + " " + xhr.responseText);
      });
  };

  // billing address passing country ID to state
  if (emp_form_type == "edit") {
    // passing country ID to state
    customerForm
      .find('[name="billing_address_country_id"]')
      .on("select2:select", function () {
        if (parseValue($(this).val()) != "") {
          loadAutocompleteBillingAddressStates({
            country_id: $(this).val(),
          });
        } else {
          customerForm
            .find('[name="billing_address_state_id"]')
            .html("")
            .trigger("change");
        }
      });

    customerForm
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
  } else {
    // passing country ID to state
    customerForm
      .find('[name="billing_address_country_id"]')
      .change(function () {
        if (parseValue($(this).val()) != "") {
          console.log("hi");
          loadAutocompleteBillingAddressStates({
            country_id: $(this).val(),
          });
          $("#billing-address-country-dial-code").html(
            $(this).find(":selected").attr("data-dial-code")
          );
        } else {
          $("#billing-address-country-dial-code").html("");
          customerForm
            .find('[name="billing_address_state_id"]')
            .html("")
            .trigger("change");
        }
      });
  }

  // billing address passing state ID to city
  if (emp_form_type == "edit") {
    // passing state ID to city
    customerForm
      .find('[name="billing_address_state_id"]')
      .on("select2:select", function () {
        if (parseValue($(this).val()) != "") {
          loadAutocompleteBillingAddressCities({
            state_id: $(this).val(),
          });
        } else {
          customerForm
            .find('[name="billing_address_city_id"]')
            .html("")
            .trigger("change");
        }
      });
  } else {
    // passing state ID to city
    customerForm.find('[name="billing_address_state_id"]').change(function () {
      if (parseValue($(this).val()) != "") {
        loadAutocompleteBillingAddressCities({
          state_id: $(this).val(),
        });
      } else {
        customerForm
          .find('[name="billing_address_city_id"]')
          .html("")
          .trigger("change");
      }
    });
  }

  // site address passing country ID to state
  if (emp_form_type == "edit") {
    // passing country ID to state
    customerForm
      .find('[name="site_address_country_id"]')
      .on("select2:select", function () {
        if (parseValue($(this).val()) != "") {
          loadAutocompleteSiteAddressStates({
            country_id: $(this).val(),
          });
        } else {
          customerForm
            .find('[name="site_address_state_id"]')
            .html("")
            .trigger("change");
        }
      });

    customerForm.find('[name="site_address_country_id"]').change(function () {
      if (parseValue($(this).val()) != "") {
        $("#site-address-country-dial-code").html(
          $(this).find(":selected").attr("data-dial-code")
        );
      } else {
        $("#site-address-country-dial-code").html("");
      }
    });
  } else {
    // passing country ID to state
    customerForm.find('[name="site_address_country_id"]').change(function () {
      if (parseValue($(this).val()) != "") {
        console.log("hi");
        loadAutocompleteSiteAddressStates({
          country_id: $(this).val(),
        });
        $("#site-address-country-dial-code").html(
          $(this).find(":selected").attr("data-dial-code")
        );
      } else {
        $("#site-address-country-dial-code").html("");
        customerForm
          .find('[name="site_address_state_id"]')
          .html("")
          .trigger("change");
      }
    });
  }

  // site address passing state ID to city
  if (emp_form_type == "edit") {
    // passing state ID to city
    customerForm
      .find('[name="site_address_state_id"]')
      .on("select2:select", function () {
        if (parseValue($(this).val()) != "") {
          loadAutocompleteSiteAddressCities({
            state_id: $(this).val(),
          });
        } else {
          customerForm
            .find('[name="site_address_city_id"]')
            .html("")
            .trigger("change");
        }
      });
  } else {
    // passing state ID to city
    customerForm.find('[name="site_address_state_id"]').change(function () {
      if (parseValue($(this).val()) != "") {
        loadAutocompleteSiteAddressCities({
          state_id: $(this).val(),
        });
      } else {
        customerForm
          .find('[name="site_address_city_id"]')
          .html("")
          .trigger("change");
      }
    });
  }

  // Load payment term autocomplete
  window.loadAutocompletePaymentTerms = function (selected = []) {
    let modulePaymentTerm = new ModulePaymentTerm({
      autoloadUrl: formApiUrl("admin/payment_term/autocomplete"),
      selectboxElement: customerForm.find('[name="payment_term"]'),
    });

    modulePaymentTerm.autocomplete(selected);
  };

  // Add user Payment Term
  customerForm.on("click", "#btn-add-payment-term", function (e) {
    e.preventDefault();
    let modulePaymentTerm = new ModulePaymentTerm({
      autoloadUrl: formApiUrl("admin/payment_term/autocomplete"),
      selectboxElement: customerForm.find('[name="payment_term"]'),
    });

    modulePaymentTerm.loadPrompt({
      selected: [customerForm.find('[name="payment_term"]').val()],
      submitAction: formApiUrl("admin/payment_term/add"),
    });
  });

  //  customer_sectores Autocomplete
  window.loadAutocompleteCustomerSectores = function (options = {}) {
    var selected;
    var customer_sectoreSelectbox = customerForm.find('[name="sector"]');

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
      url: formApiUrl("admin/customer_sector/autocomplete"),
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

  //same address copy
  customerForm.find("#same-address").change(function (e) {
    e.preventDefault();
    let elem = this;
    if (elem.checked) {
      let address = customerForm.find('[name="billing_address"]').val();
      let contact_name = customerForm
        .find('[name="billing_address_contact_name"]')
        .val();
      let email = customerForm.find('[name="billing_address_email"]').val();
      let country = customerForm
        .find('[name="billing_address_country_id"]')
        .val();
      let mobile = customerForm.find('[name="billing_address_mobile"]').val();
      let state = customerForm.find('[name="billing_address_state_id"]').val();
      let city = customerForm.find('[name="billing_address_city_id"]').val();
      let pincode = customerForm.find('[name="billing_address_pincode"]').val();

      customerForm.find('[name="site_address"]').val(address);
      customerForm.find('[name="site_address_contact_name"]').val(contact_name);
      customerForm.find('[name="site_address_email"]').val(email);
      customerForm.find('[name="site_address_pincode"]').val(pincode);
      customerForm.find('[name="site_address_mobile"]').val(mobile);
      // console.log(state);
      loadAutocompleteSiteAddressCountry({ selected: [country] });
      loadAutocompleteSiteAddressStates({
        country_id: country,
        selected: [state],
      });
      loadAutocompleteSiteAddressCities({
        state_id: state,
        selected: [city],
      });
    } else {
      customerForm.find('[name="site_address"]').val("");
      customerForm.find('[name="site_address_contact_name"]').val("");
      customerForm.find('[name="site_address_email"]').val("");
      customerForm.find('[name="site_address_pincode"]').val("");
      customerForm.find('[name="site_address_mobile"]').val("");
      loadAutocompleteSiteAddressCountry();
    }
  });

  // random password generator
  customerForm.find(".password-generate").click(function (e) {
    e.preventDefault();
    let res = generateRandomString(8);
    customerForm.find('[name="password"]').val(res);
  });

  // load details
  window.loadCustomerDetail = function (href) {
    let loadSwal;

    $.ajax({
      url: formApiUrl("admin/customer/detail", { customer_id: customer_id }),
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
          let customerDetail = res.customer.data;
          if (parseValue(res.customer.data) != "") {
            customerForm.find('[name="name"]').val(customerDetail.name);
            customerForm
              .find('[name="company_name"]')
              .val(customerDetail.company_name);
            customerForm.find('[name="email"]').val(customerDetail.email);
            customerForm.find('[name="username"]').val(customerDetail.username);
            customerForm
              .find('[name="billing_address_mobile"]')
              .val(customerDetail.billing_address_mobile);
            customerForm
              .find('[name="billing_address_contact_name"]')
              .val(customerDetail.billing_address_contact_name);
            customerForm
              .find('[name="billing_address_email"]')
              .val(customerDetail.billing_address_email);
            customerForm
              .find('[name="billing_address"]')
              .val(customerDetail.billing_address);
            customerForm
              .find('[name="billing_address_pincode"]')
              .val(customerDetail.billing_address_pincode);
            customerForm
              .find('[name="site_address_contact_name"]')
              .val(customerDetail.site_address_contact_name);
            customerForm
              .find('[name="site_address_email"]')
              .val(customerDetail.site_address_email);
            customerForm
              .find('[name="site_address_mobile"]')
              .val(customerDetail.site_address_mobile);
            customerForm
              .find('[name="site_address"]')
              .val(customerDetail.site_address);
            customerForm
              .find('[name="site_address_pincode"]')
              .val(customerDetail.site_address_pincode);
              customerForm
              .find('[name="website"]')
              .val(customerDetail.website);
              customerForm
              .find('[name="gst_number"]')
              .val(customerDetail.gst_number);
              customerForm
              .find('[name="pan_number"]')
              .val(customerDetail.pan_number);

            // Load autocompletes
            loadAutocompleteBillingAddressCountry({
              selected: [customerDetail.billing_address_country],
            });
            loadAutocompleteSiteAddressCountry({
              selected: [customerDetail.site_address_country],
            });
            loadAutocompletePaymentTerms({
              selected: [customerDetail.payment_term],
            });
            loadAutocompleteCustomerSectores({
              selected: [customerDetail.sector],
            });
            loadAutocompleteBillingAddressStates({
              country_id: customerDetail.billing_address_country,
              selected: [customerDetail.billing_address_state],
            });
            loadAutocompleteBillingAddressCities({
              state_id: customerDetail.billing_address_state,
              selected: [customerDetail.billing_address_city],
            });
            loadAutocompleteSiteAddressStates({
              country_id: customerDetail.site_address_country,
              selected: [customerDetail.site_address_state],
            });
            loadAutocompleteSiteAddressCities({
              state_id: customerDetail.site_address_state,
              selected: [customerDetail.site_address_city],
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

  // customer Form
  customerFormValidator = customerForm.validate({
    onkeyup: function (element) {
      $(element).valid();
    },
    onclick: function (element) {
      $(element).valid();
    },
    rules: {
      name: {
        required: true,
      },
      company_name: {
        required: true,
      },
      email: {
        required: true,
        email: true,
      },
      username: {
        required: true,
      },
      billing_address_mobile: {
        required: true,
        digits: true,
        minlength: 10,
        maxlength: 10,
      },
      site_address_mobile: {
        digits: true,
        minlength: 10,
        maxlength: 10,
      },
      gst_number: {
        regex: "^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9]{1}Z[a-zA-Z0-9]{1}$",
      },
      pan_number: {
        regex: "^[A-Z]{5}[0-9]{4}[A-Z]{1}$",
      },
    },
    messages: {
      name: {
        required: "Specify customer name",
        minlength: "Specify atleast 3 characters",
      },
      company_name: {
        required: "Specify contact person name",
        minlength: "Specify atleast 3 characters",
      },
      email: {
        required: "Specify email address",
        email: "Specify valid email address",
      },
      country_id: {
        required: "Specify Country",
      },
      customer_billing_address_mobile: {
        required: "Specify mobile number",
        digits: "Mobile number must be numeric",
        minlength: "Specify valid 10 digit mobile number",
        minlength: "Specify valid 10 digit mobile number",
      },
      customer_site_address_mobile: {
        digits: "Mobile number must be numeric",
        minlength: "Specify valid 10 digit mobile number",
        minlength: "Specify valid 10 digit mobile number",
      },
      username: {
        required: "Specify username",
      },
      gst_number: {
        regex: "Specify valid GST number",
      },
      pan_number: {
        regex: "Specify valid PAN number",
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

  if (emp_form_type == "edit") {
    customerForm.find('[name="password"]').rules("remove");
  } else {
    customerForm.find('[name="password"]').rules("add", {
      required: true,
      messages: {
        required: "Specify password",
      },
    });
  }

  function savecustomer() {
    let loadSwal;
    var formData = new FormData(customerForm[0]);

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
          customerForm[0].reset(); // Reset form
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

  customerForm.submit(function (e) {
    e.preventDefault();
    if (customerFormValidator.valid()) {
      savecustomer();
    }
  });
});

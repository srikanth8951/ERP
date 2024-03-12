/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: Contract Job js
 */

var appcontractjob = {};

$(function () {
  var contractJobFormValidator;

  // Status select
  $("#status-select").on("change", function () {
    var status = $(this).val();

    if (status == 1) {
      $("#contract_period").show();
    } else if (status == 2) {
      $("#contract_period").show();
      $("#status1").hide();
    } else {
      $("#contract_period").hide();
    }
  });

  async function loadCustomerFormView(detail) {
    if (parseValue(detail) != "" && Object.keys(detail).length > 0) {
      contractJobForm
        .find(
          '.customer-detail-area input[type="text"], .customer-detail-area textarea, .customer-detail-area select, .customer-detail-area button.password-generate'
        )
        .prop("disabled", true);
      contractJobForm
        .find('[name="customer_company_name"]')
        .val(detail.company_name);
      contractJobForm
        .find('[name="customer_contact_name"]')
        .val(detail.contact_name);
      contractJobForm
        .find('[name="customer_job_number"]')
        .val(detail.job_number);
      contractJobForm
        .find('[name="customer_sector"]')
        .val(detail.sector)
        .trigger("change");
      contractJobForm.find('[name="customer_username"]').val(detail.username);
      contractJobForm
        .find('[name="customer_billing_address"]')
        .val(detail.billing_address);
      contractJobForm
        .find('[name="customer_billing_address_contact_name"]')
        .val(detail.billing_address_contact_name);
      contractJobForm
        .find('[name="customer_billing_address_email"]')
        .val(detail.billing_address_email);
      contractJobForm
        .find('[name="customer_site_address"]')
        .val(detail.site_address);

      await loadAutocompleteAddressCountries({
        element: contractJobForm.find(
          '[name="customer_billing_address_country"]'
        ),
        params: {
          selected: [detail.billing_address_country],
        },
      });
      await loadAutocompleteAddressStates({
        element: contractJobForm.find(
          '[name="customer_billing_address_state"]'
        ),
        params: {
          country_id: detail.billing_address_country,
          selected: [detail.billing_address_state],
        },
      });
      await loadAutocompleteAddressCities({
        element: contractJobForm.find('[name="customer_billing_address_city"]'),
        params: {
          state_id: detail.billing_address_state,
          selected: [detail.billing_address_city],
        },
      });
      contractJobForm
        .find('[name="customer_billing_address_pincode"]')
        .val(detail.billing_address_pincode);
      contractJobForm
        .find('[name="customer_billing_address_mobile"]')
        .val(detail.billing_address_mobile);
      contractJobForm
        .find('[name="customer_site_address_contact_name"]')
        .val(detail.site_address_contact_name);
      contractJobForm
        .find('[name="customer_site_address_email"]')
        .val(detail.site_address_email);
      contractJobForm
        .find('[name="customer_site_address_country"]')
        .val(detail.site_address_country)
        .trigger("change");
      contractJobForm
        .find('[name="customer_site_address_state"]')
        .val(detail.site_address_state)
        .trigger("change");
      contractJobForm
        .find('[name="customer_site_address_city"]')
        .val(detail.site_address_city)
        .trigger("change");
      await loadAutocompleteAddressCountries({
        element: contractJobForm.find('[name="customer_site_address_country"]'),
        params: {
          selected: [detail.site_address_country],
        },
      });
      await loadAutocompleteAddressStates({
        element: contractJobForm.find('[name="customer_site_address_state"]'),
        params: {
          country_id: detail.site_address_country,
          selected: [detail.site_address_state],
        },
      });
      await loadAutocompleteAddressCities({
        element: contractJobForm.find('[name="customer_site_address_city"]'),
        params: {
          state_id: detail.site_address_state,
          selected: [detail.site_address_city],
        },
      });
      contractJobForm
        .find('[name="customer_site_address_pincode"]')
        .val(detail.site_address_pincode);
      contractJobForm
        .find('[name="customer_site_address_mobile"]')
        .val(detail.site_address_mobile);
      contractJobForm.find('[name="customer_website"]').val(detail.website);
      contractJobForm
        .find('[name="customer_gst_number"]')
        .val(detail.gst_number);
      contractJobForm
        .find('[name="customer_pan_number"]')
        .val(detail.pan_number);
      contractJobForm
        .find('[name="customer_payment_term"]')
        .val(detail.payment_term)
        .trigger("change");
    } else {
      toastr.info("Error occured while loading customer detail!");
      contractJobForm
        .find(
          '.customer-detail-area input[type="text"], .customer-detail-area textarea, .customer-detail-area select, .customer-detail-area button.password-generate'
        )
        .prop("disabled", false);
    }
  }

  // Customer type switch
  $("#select-customer-type .ctype").click(function (e) {
    e.preventDefault();
    $("#select-customer-type .ctype").removeClass("active");
    var inputCtype = $(this).parent().find('input[name="customer_type"]');
    var inputCid = $(this).parent().find('input[name="customer_id"]');
    var ctype = $(this).data("customer-type");

    if (ctype == "exist") {
      $(this).addClass("active");
      inputCtype.val("exist");
      customerModal.modal({
        backdrop: "static",
        keyboard: false,
        show: true,
      });
      $("#same-address-area").css("display", "none");
      $("#customer-select").html("").trigger("change");
      contractJobForm
        .find(
          '.customer-detail-area input[type="text"], .customer-detail-area textarea, .customer-detail-area select, .customer-detail-area button.password-generate'
        )
        .prop("disabled", true);
    } else if (ctype == "new") {
      $(this).addClass("active");
      inputCtype.val("new");
      inputCid.val(0);
      contractJobForm
        .find(
          '.customer-detail-area input[type="text"], .customer-detail-area textarea, .customer-detail-area select, .customer-detail-area button.password-generate'
        )
        .val("")
        .prop("disabled", false);
        $("#same-address-area").css("display", "block");
      contractJobForm
        .find('.customer-detail-area [data-toggle="select2"]')
        .val("")
        .trigger("change");
      loadAutocompleteAddressCountries({ selected: [103] });
    } else {
      inputCtype.val("");
      inputCid.val(0);
      contractJobForm
        .find(
          '.customer-detail-area input[type="text"], .customer-detail-area textarea, .customer-detail-area select, .customer-detail-area button.password-generate'
        )
        .val("")
        .prop("disabled", false);
      contractJobForm
        .find('.customer-detail-area [data-toggle="select2"]')
        .val("")
        .trigger("change");
    }
  });

  // Customer select
  $("#customer-select").select2({
    ajax: {
      url: formApiUrl("admin/customer/autocomplete"),
      type: "get",
      dataType: "json",
      headers: {
        Authorization: `Bearer ${wapLogin.getToken()}`,
      },
      data: function (param) {
        return {
          search: param.term,
        };
      },
      processResults: function (data) {
        var resultDatas = [];
        $.each(data.customers, function (cindex, customer) {
          resultDatas.push({ id: customer.id, text: customer.name });
        });
        return {
          results: resultDatas,
        };
      },
    },
  });

  // After customer select
  $("#customer-select").on("select2:select", function (e) {
    let csValue = $(this).val();
    let loadSwal;

    // Get customer detail
    $.ajax({
      url: formApiUrl("admin/customer/detail", { customer_id: csValue }),
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
      complete: function () {
        loadSwal.close();
      },
    })
      .done((res) => {
        if (res.status == "success") {
          customerModal.modal("hide"); // Hide modal
          $('#select-customer-type input[name="customer_id"]').val(csValue);

          loadCustomerFormView(res.customer.data);
        } else if (res.status == "error") {
          console.log(res.message);
        } else {
          console.log("country Autocomlete: Something went wrong!");
        }
      })
      .fail((xhr, ajaxOptions, errorThrown) => {
        console.log(xhr.responseText + " " + xhr.responseText);
      });
  });

  // customer modal close
  customerModal.find(".close").click(function () {
    $("#customer-select").html("").trigger("change");
  });

  // Load payment term autocomplete
  window.loadAutocompletePaymentTerms = function (selected = []) {
    let modulePaymentTerm = new ModulePaymentTerm({
      autoloadUrl: formApiUrl("admin/payment_term/autocomplete"),
      selectboxElement: contractJobForm.find('[name="customer_payment_term"]'),
    });

    modulePaymentTerm.autocomplete(selected);
  };

  // Add user Payment Term
  contractJobForm.on("click", "#btn-add-payment-term", function (e) {
    e.preventDefault();
    let modulePaymentTerm = new ModulePaymentTerm({
      autoloadUrl: formApiUrl("admin/payment_term/autocomplete"),
      selectboxElement: contractJobForm.find('[name="customer_payment_term"]'),
    });

    modulePaymentTerm.loadPrompt({
      selected: [contractJobForm.find('[name="customer_payment_term"]').val()],
      submitAction: formApiUrl("admin/payment_term/add"),
    });
  });

  //  Customer sectores Autocomplete
  window.loadAutocompleteCustomerSectores = function (options = {}) {
    var selected;
    var customer_sectoreSelectbox = contractJobForm.find(
      '[name="customer_sector"]'
    );

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
        url: formApiUrl("admin/localisation/state/list", optionParams),
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
        url: formApiUrl("admin/localisation/city/list", optionParams),
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

  //same address copy
  contractJobForm.find("#same-address").change(async function (e) {
    e.preventDefault();
    let elem = this;
    if (elem.checked) {
      let address = contractJobForm
        .find('[name="customer_billing_address"]')
        .val();
      let contact_name = contractJobForm
        .find('[name="customer_billing_address_contact_name"]')
        .val();
      let email = contractJobForm
        .find('[name="customer_billing_address_email"]')
        .val();
      let country = contractJobForm
        .find('[name="customer_billing_address_country"]')
        .val();
      let mobile = contractJobForm
        .find('[name="customer_billing_address_mobile"]')
        .val();
      let state = contractJobForm
        .find('[name="customer_billing_address_state"]')
        .val();
      let city = contractJobForm
        .find('[name="customer_billing_address_city"]')
        .val();
      let pincode = contractJobForm
        .find('[name="customer_billing_address_pincode"]')
        .val();

      contractJobForm.find('[name="customer_site_address"]').val(address);
      contractJobForm
        .find('[name="customer_site_address_contact_name"]')
        .val(contact_name);
      contractJobForm.find('[name="customer_site_address_email"]').val(email);
      contractJobForm
        .find('[name="customer_site_address_pincode"]')
        .val(pincode);
      contractJobForm.find('[name="customer_site_address_mobile"]').val(mobile);

      await loadAutocompleteAddressCountries({
        element: contractJobForm.find('[name="customer_site_address_country"]'),
        params: {
          selected: [country],
        },
      });
      await loadAutocompleteAddressStates({
        element: contractJobForm.find('[name="customer_site_address_state"]'),
        params: {
          country_id: country,
          selected: [state],
        },
      });
      await loadAutocompleteAddressCities({
        element: contractJobForm.find('[name="customer_site_address_city"]'),
        params: {
          state_id: state,
          selected: [city],
        },
      });
    } else {
      contractJobForm.find('[name="customer_site_address"]').val("");
      contractJobForm
        .find('[name="customer_site_address_contact_name"]')
        .val("");
      contractJobForm.find('[name="customer_site_address_email"]').val("");
      contractJobForm.find('[name="customer_site_address_city"]').val("");
      contractJobForm.find('[name="customer_site_address_pincode"]').val("");
      contractJobForm.find('[name="customer_site_address_mobile"]').val("");
      await loadAutocompleteAddressCountries({
        element: contractJobForm.find('[name="customer_site_address_country"]'),
      });
    }
  });

  // random password generator
  contractJobForm.find(".password-generate").click(function (e) {
    e.preventDefault();
    let res = generateRandomString(8);
    contractJobForm.find('[name="customer_password"]').val(res);
  });

  //contract total value caculation
  contractJobForm.find('[name="contract_value"]').on("keyup", function (e) {
    let contract_value = contractJobForm.find('[name="contract_value"]').val()
      ? contractJobForm.find('[name="contract_value"]').val()
      : 0;
    let gst_value = contractJobForm.find('[name="contract_gst_value"]').val()
      ? contractJobForm.find('[name="contract_gst_value"]').val()
      : 0;
    let total_value;
    if (contract_value) {
      total_value =
        parseFloat(contract_value) * (parseFloat(gst_value) / 100) +
        parseFloat(contract_value);
      contractJobForm
        .find('[name="contract_value_total"]')
        .val(total_value.toFixed(2));
      contractJobForm
        .find('[name="total_contact_value"]')
        .val(total_value.toFixed(2));
    } else {
      contractJobForm.find('[name="contract_value_total"]').val("");
      contractJobForm.find('[name="total_contact_value"]').val("");
    }
  });

  //contract total value caculation
  contractJobForm.find('[name="contract_gst_value"]').on("keyup", function (e) {
    let contract_value = contractJobForm.find('[name="contract_value"]').val()
      ? contractJobForm.find('[name="contract_value"]').val()
      : 0;
    let gst_value = contractJobForm.find('[name="contract_gst_value"]').val()
      ? contractJobForm.find('[name="contract_gst_value"]').val()
      : 0;
    let total_value;
    if (gst_value) {
      total_value =
        parseFloat(contract_value) * (parseFloat(gst_value) / 100) +
        parseFloat(contract_value);
      contractJobForm
        .find('[name="contract_value_total"]')
        .val(total_value.toFixed(2));
      contractJobForm
        .find('[name="total_contact_value"]')
        .val(total_value.toFixed(2));
    } else if (contract_value) {
      total_value =
        parseFloat(contract_value) * (parseFloat(gst_value) / 100) +
        parseFloat(contract_value);
      contractJobForm
        .find('[name="contract_value_total"]')
        .val(total_value.toFixed(2));
      contractJobForm
        .find('[name="total_contact_value"]')
        .val(total_value.toFixed(2));
    } else {
      contractJobForm.find('[name="contract_value_total"]').val("");
      contractJobForm.find('[name="total_contact_value"]').val("");
    }
  });

  // load contract job view based on detail
  function loadContractJobView(detail) {
    if (parseValue(detail) != "" && Object.keys(detail).length > 0) {
      contractJobForm
        .find('[name="job_title"]')
        .val(detail.job_title)
        .trigger("change");
      contractJobForm.find('[name="job_number"]').val(detail.job_number);

      if (emp_form_type == "update") {
        contractJobForm
          .find('[name="sap_job_number"]')
          .val(detail.sap_job_number);
        contractJobForm
          .find('[name="po_number"]')
          .val(detail.purchase_order_number);
        contractJobForm
          .find('[name="contract_nature"]')
          .val(detail.contract_nature_name)
          .trigger("change")
          .prop("disabled", true);
        contractJobForm
          .find('[name="contract_type"]')
          .val(detail.contract_type_name)
          .trigger("change")
          .prop("disabled", true);
      } else {
        contractJobForm
          .find('[name="contract_nature"]')
          .val(detail.contract_nature_name)
          .trigger("change");
        contractJobForm
          .find('[name="contract_type"]')
          .val(detail.contract_type_name)
          .trigger("change");
      }

      contractJobForm
        .find('[name="deployed_people_number"]')
        .val(detail.deployed_people_number);
      contractJobForm
        .find('[name="ppm_frequency"]')
        .val(detail.ppm_frequency)
        .trigger("change");
      contractJobForm
        .find('[name="customer_account_manager"]')
        .val(detail.customer_account_manager_name)
        .trigger("change");
      contractJobForm
        .find('[name="engineer"]')
        .val(detail.engineer_name)
        .trigger("change");
      contractJobForm
        .find('[name="contract_currency"]')
        .val(detail.contract_currency_name)
        .trigger("change");
      contractJobForm
        .find('[name="contract_gst_value"]')
        .val(detail.contract_gst_value);
      contractJobForm
        .find('[name="contract_value_total"]')
        .val(detail.total_contract_value);
      contractJobForm
        .find('[name="total_contact_value"]')
        .val(detail.total_contract_value);
      contractJobForm
        .find('[name="contract_value"]')
        .val(detail.contract_value);
      contractJobForm
        .find('[name="expected_gross_margin"]')
        .val(detail.expected_gross_margin);
      contractJobForm
        .find('[name="contract_status"]')
        .val(detail.contract_status_name)
        .trigger("change");
      contractJobForm.find('[name="contract_period"]').val(detail.period);
      contractJobForm
        .find('[name="job_location_lattitude"]')
        .val(detail.geolocation_lattitude);
      contractJobForm
        .find('[name="job_location_longitude"]')
        .val(detail.geolocation_longitude);
      contractJobForm
        .find('[name="job_location_range"]')
        .val(detail.geolocation_range);

      if (emp_form_type != "renew") {
        contractJobForm
          .find('[name="period_fromdate"]')
          .val(moment(detail.period_fromdate).format("DD/MMM/YYYY"));
        contractJobForm
          .find('[name="period_todate"]')
          .val(moment(detail.period_todate).format("DD/MMM/YYYY"));
      }

      // Load asset details
      loadAssetDetails(detail.contract_job_id);

      loadAutocompleteContractNature({ selected: [detail.contract_nature_id] });
      loadAutocompleteContractType({ selected: [detail.contract_type_id] });
      loadAutocompleteCAM({ selected: [detail.customer_account_manager_id] });
      loadAutocompleteEngineer({
        cam_id: detail.customer_account_manager_id,
        selected: [detail.engineer_id],
        loadView: {
          id: detail.engineer_id,
        },
      });
      loadAutocompleteCurrency({ selected: [detail.contract_currency_id] });
      loadAutocompleteContractStatus({ selected: [detail.contract_status_id] });

      // load customer details

      getCustomerDetails(detail.customer_id);

      if (detail.contract_status_id != 3) {
        contractJobForm.find("#contract_period").show();
      } else {
        contractJobForm.find("#contract_period").hide();
      }
    } else {
      toastr.info("Error occured while loading customer detail!");
      contractJobForm
        .find(
          '.customer-detail-area input[type="text"], .customer-detail-area textarea, .customer-detail-area select, .customer-detail-area button.password-generate'
        )
        .prop("disabled", false);
    }
  }

  // Customer billing Address Selectbox
  /* load state autocomplete after country select */
  contractJobForm
    .find('[name="customer_billing_address_country"]')
    .on("select2:select", function () {
      if (parseValue($(this).val()) != "") {
        loadAutocompleteAddressStates({
          element: contractJobForm.find(
            '[name="customer_billing_address_state"]'
          ),
          params: {
            country_id: $(this).val(),
          },
        });
      } else {
        contractJobForm
          .find('[name="customer_billing_address_state"]')
          .html("")
          .trigger("change");
      }
    });

  /* set country dial code after country select/change*/
  contractJobForm
    .find('[name="customer_billing_address_country"]')
    .change(function () {
      if (parseValue($(this).val()) != "") {
        $("#billing_address_country-dial-code").html(
          $(this).find(":selected").attr("data-dial-code")
        );
      } else {
        $("#billing_address_country-dial-code").html("");
      }
    });

  /* load city autocomplete after state select */
  contractJobForm
    .find('[name="customer_billing_address_state"]')
    .on("select2:select", function () {
      if (parseValue($(this).val()) != "") {
        loadAutocompleteAddressCities({
          element: contractJobForm.find(
            '[name="customer_billing_address_city"]'
          ),
          params: {
            state_id: $(this).val(),
          },
        });
      } else {
        contractJobForm
          .find('[name="customer_billing_address_city"]')
          .html("")
          .trigger("change");
      }
    });

  // Customer site Address Selectbox
  /*  load state autocomplete after country select */
  contractJobForm
    .find('[name="customer_site_address_country"]')
    .on("select2:select", function () {
      if (parseValue($(this).val()) != "") {
        loadAutocompleteAddressStates({
          element: contractJobForm.find('[name="customer_site_address_state"]'),
          params: {
            country_id: $(this).val(),
          },
        });
      } else {
        contractJobForm
          .find('[name="customer_site_address_state"]')
          .html("")
          .trigger("change");
      }
    });

  /* set country dial code after country select/change*/
  contractJobForm
    .find('[name="customer_site_address_country"]')
    .change(function () {
      if (parseValue($(this).val()) != "") {
        $("#site_address_country-dial-code").html(
          $(this).find(":selected").attr("data-dial-code")
        );
      } else {
        $("#site_address_country-dial-code").html("");
      }
    });

  /* load city autocomplete after state select */
  contractJobForm
    .find('[name="customer_site_address_state"]')
    .on("select2:select", function () {
      if (parseValue($(this).val()) != "") {
        loadAutocompleteAddressCities({
          element: contractJobForm.find('[name="customer_site_address_city"]'),
          params: {
            state_id: $(this).val(),
          },
        });
      } else {
        contractJobForm
          .find('[name="customer_site_address_city"]')
          .html("")
          .trigger("change");
      }
    });

  // Get customer details
  function getCustomerDetails(customer_id) {
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
      success: async function (res) {
        if (res.status == "success") {
          if (parseValue(res.customer.data) != "") {
            let details = res.customer.data;

            contractJobForm
              .find('[name="customer_id"]')
              .val(details.customer_id);
            contractJobForm
              .find('[name="customer_company_name"]')
              .val(details.company_name);
            contractJobForm
              .find('[name="customer_job_number"]')
              .val(details.job_number);
            contractJobForm
              .find('[name="customer_username"]')
              .val(details.username);
            contractJobForm
              .find('[name="customer_billing_address"]')
              .val(details.billing_address);
            contractJobForm
              .find('[name="customer_site_address"]')
              .val(details.site_address);
            contractJobForm
              .find('[name="customer_billing_address_contact_name"]')
              .val(details.billing_address_contact_name);
            contractJobForm
              .find('[name="customer_billing_address_email"]')
              .val(details.billing_address_email);
            contractJobForm
              .find('[name="customer_billing_address_pincode"]')
              .val(details.billing_address_pincode);
            contractJobForm
              .find('[name="customer_billing_address_mobile"]')
              .val(details.billing_address_mobile);
            contractJobForm
              .find('[name="customer_site_address_contact_name"]')
              .val(details.site_address_contact_name);
            contractJobForm
              .find('[name="customer_site_address_email"]')
              .val(details.site_address_email);
            contractJobForm
              .find('[name="customer_site_address_pincode"]')
              .val(details.site_address_pincode);
            contractJobForm
              .find('[name="customer_site_address_mobile"]')
              .val(details.site_address_mobile);
            contractJobForm
              .find('[name="customer_website"]')
              .val(details.website);
            contractJobForm
              .find('[name="customer_gst_number"]')
              .val(details.gst_number);
            contractJobForm
              .find('[name="customer_pan_number"]')
              .val(details.pan_number);
            contractJobForm
              .find('[name="customer_payment_term"]')
              .val(details.payment_term)
              .trigger("change");

            loadAutocompletePaymentTerms({ selected: details.payment_term });

            await loadAutocompleteAddressCountries({
              element: contractJobForm.find(
                '[name="customer_billing_address_country"]'
              ),
              params: {
                selected: [details.billing_address_country],
              },
            });
            await loadAutocompleteAddressStates({
              element: contractJobForm.find(
                '[name="customer_billing_address_state"]'
              ),
              params: {
                country_id: details.billing_address_country,
                selected: [details.billing_address_state],
              },
            });
            await loadAutocompleteAddressCities({
              element: contractJobForm.find(
                '[name="customer_billing_address_city"]'
              ),
              params: {
                state_id: details.billing_address_state,
                selected: [details.billing_address_city],
              },
            });
            await loadAutocompleteAddressCountries({
              element: contractJobForm.find(
                '[name="customer_site_address_country"]'
              ),
              params: {
                selected: [details.site_address_country],
              },
            });
            await loadAutocompleteAddressStates({
              element: contractJobForm.find(
                '[name="customer_site_address_state"]'
              ),
              params: {
                country_id: details.site_address_country,
                selected: [details.site_address_state],
              },
            });
            await loadAutocompleteAddressCities({
              element: contractJobForm.find(
                '[name="customer_site_address_city"]'
              ),
              params: {
                state_id: details.site_address_state,
                selected: [details.site_address_city],
              },
            });
            loadAutocompleteCustomerSectores({
              selected: [details.sector],
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
  }

  // load contract job details
  window.loadContractJobDetail = function () {
    let loadSwal;

    $.ajax({
      url: formApiUrl("admin/contract_job/detail", {
        contract_job_id: contract_job_id,
      }),
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
          if (parseValue(res.contract_job) != "") {
            loadContractJobView(res.contract_job);
          } else {
            toastr.info("No contract job data");
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

  // customer Form validation
  contractJobFormValidator = contractJobForm.validate({
    onkeyup: function (element) {
      $(element).valid();
    },
    onclick: function (element) {
      $(element).valid();
    },
    rules: {
      customer_name: {
        required: true,
      },
      customer_company_name: {
        required: true,
      },
      customer_email: {
        required: true,
        email: true,
      },
      customer_billing_address_mobile: {
        required: true,
        digits: true,
        minlength: 10,
        maxlength: 10,
      },
      customer_site_address_mobile: {
        digits: true,
        minlength: 10,
        maxlength: 10,
      },
      // customer_country: {
      //   required: true,
      // },
      customer_username: {
        required: true,
      },
      "assets[]": {
        required: true,
      },
      customer_gst_number: {
        regex: "^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9]{1}Z[a-zA-Z0-9]{1}$",
      },
      customer_pan_number: {
        regex: "^[A-Z]{5}[0-9]{4}[A-Z]{1}$",
      },
    },
    messages: {
      customer_name: {
        required: "Specify customer name",
        minlength: "Specify atleast 3 characters",
      },
      customer_company_name: {
        required: "Specify company name",
        minlength: "Specify atleast 3 characters",
      },
      customer_email: {
        required: "Specify email address",
        email: "Specify valid email address",
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
      // customer_country: {
      //   required: "Specify country",
      // },
      customer_username: {
        required: "Specify username",
      },
      "assets[]": {
        required: "Specify atleast one asset",
      },
      customer_gst_number: {
        regex: "Specify valid GST number",
      },
      customer_pan_number: {
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

  if (emp_form_type == "update" || emp_form_type == "renew") {
    contractJobForm.find('[name="customer_password"]').rules("remove");
  } else {
    contractJobForm.find('[name="customer_password"]').rules("add", {
      required: true,
      messages: {
        required: "Specify password",
      },
    });
  }

  // Contract job Save function
  function saveContractJob() {
    let loadSwal;
    var formData = new FormData(contractJobForm[0]);
    console.log(formData);
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
        contractJobForm
          .find('[type="submit"]')
          .attr("disabled", true)
          .html('<i class="fa fa-spinner fa-spin "></i> Loading');
      },
      complete: function () {
        loadSwal.close();
        contractJobForm
          .find('[type="submit"]')
          .attr("disabled", false)
          .html("Submit");
      },
    })
      .done(function (res) {
        if (res.status == "success") {
          toastr.success(res.message);
          contractJobForm[0].reset(); // Reset form
          setTimeout(function () {
            window.location.href = formUrl("admin/contract_job");
          });
        } else if (res.status == "error") {
          if (
            typeof res.message == "object" &&
            Object.keys(res.message).length > 0
          ) {
            for (const [mkey, mvalue] of Object.entries(res.message)) {
              toastr.error(mvalue);
            }
          } else {
            toastr.error(res.message);
          }
        } else {
          toastr.error("No response status", "Error");
        }
      })
      .fail(function (jqXHR, textStatus, errorThrown) {
        toastr.error(`${textStatus} - ${errorThrown}`, "Error");
      });
  }

  // Contract job submit
  contractJobForm.submit(function (e) {
    e.preventDefault();
    if (contractJobFormValidator.valid()) {
      saveContractJob();
    }
  });
});

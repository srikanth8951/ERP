$(function () {
  //  Contract Payment Terms Autocomplete
  window.loadAutocompletePaymentTerms = function (options = {}) {
    var selected;
    var paymentTermSelectbox = contractJobForm.find(
      '[name="customer_payment_term"]'
    );

    if (parseValue(options.selected) != "") {
      selected =
        Object.keys(options.selected).length > 0
          ? Object.values(options.selected)
          : [];
    } else {
      selected = [];
    }

    paymentTermSelectbox.html("").trigger("change"); // Reset selectbox
    $.ajax({
      url: formApiUrl("admin/payment_term/autocomplete"),
      type: "get",
      dataType: "json",
      headers: {
        Authorization: `Bearer ${wapLogin.getToken()}`,
      },
    })
      .done((res) => {
        paymentTermSelectbox.append(new Option("Select", "", false, false)); // Load initial select
        if (res.status == "success") {
          if (res.payment_terms) {
            var paymentTerms = res.payment_terms;
            var paymentTermOption;

            $.each(paymentTerms, function (bi, paymentTerm) {
              if (
                selected.find((value) => {
                  return value == paymentTerm.id;
                })
              ) {
                paymentTermOption = new Option(
                  paymentTerm.name,
                  paymentTerm.id,
                  true,
                  true
                );
              } else {
                paymentTermOption = new Option(
                  paymentTerm.name,
                  paymentTerm.id,
                  false,
                  false
                );
              }

              paymentTermSelectbox.append(paymentTermOption);
            });
            paymentTermSelectbox.trigger("change");
          }
        } else if (res.status == "error") {
          console.log(res.message);
        } else {
          console.log("NOC Autocomlete: Something went wrong!");
        }
      })
      .fail((xhr, ajaxOptions, errorThrown) => {
        console.log(xhr.responseText + " " + xhr.responseText);
      });
  };

  //  Contract Nature Autocomplete
  window.loadAutocompleteContractNature = function (options = {}) {
    var selected;
    var nocSelectbox = contractJobForm.find('[name="contract_nature"]');

    if (parseValue(options.selected) != "") {
      selected =
        Object.keys(options.selected).length > 0
          ? Object.values(options.selected)
          : [];
    } else {
      selected = [];
    }

    nocSelectbox.html("").trigger("change"); // Reset selectbox
    $.ajax({
      url: formApiUrl("admin/contract_nature/autocomplete"),
      type: "get",
      dataType: "json",
      headers: {
        Authorization: `Bearer ${wapLogin.getToken()}`,
      },
    })
      .done((res) => {
        nocSelectbox.append(new Option("Select", "", false, false)); // Load initial select
        if (res.status == "success") {
          if (res.contract_natures) {
            var nocs = res.contract_natures;
            var nocOption;

            $.each(nocs, function (bi, noc) {
              if (
                selected.find((value) => {
                  return value == noc.id;
                })
              ) {
                nocOption = new Option(noc.name, noc.id, true, true);
              } else {
                nocOption = new Option(noc.name, noc.id, false, false);
              }

              nocSelectbox.append(nocOption);
            });
            nocSelectbox.trigger("change");
          }
        } else if (res.status == "error") {
          console.log(res.message);
        } else {
          console.log("NOC Autocomlete: Something went wrong!");
        }
      })
      .fail((xhr, ajaxOptions, errorThrown) => {
        console.log(xhr.responseText + " " + xhr.responseText);
      });
  };

  //  Contract Type Autocomplete
  window.loadAutocompleteContractType = function (options = {}) {
    var selected;
    var tocSelectbox = contractJobForm.find('[name="contract_type"]');

    if (parseValue(options.selected) != "") {
      selected =
        Object.keys(options.selected).length > 0
          ? Object.values(options.selected)
          : [];
    } else {
      selected = [];
    }

    tocSelectbox.html("").trigger("change"); // Reset selectbox
    $.ajax({
      url: formApiUrl("admin/contract_type/autocomplete"),
      type: "get",
      dataType: "json",
      headers: {
        Authorization: `Bearer ${wapLogin.getToken()}`,
      },
    })
      .done((res) => {
        tocSelectbox.append(new Option("Select", "", false, false)); // Load initial select
        if (res.status == "success") {
          if (res.contract_types) {
            var tocs = res.contract_types;
            var tocOption;

            $.each(tocs, function (bi, toc) {
              if (
                selected.find((value) => {
                  return value == toc.id;
                })
              ) {
                localisationOption = new Option(toc.name, toc.id, true, true);
              } else {
                if(toc.disable == 1) {
                  localisationOption = `<option value="${toc.id}" disabled>${toc.name}</option>`;
                } else {
                localisationOption = new Option(toc.name, toc.id, false, false);
                }
              }
                tocSelectbox.append(localisationOption);
            });
            tocSelectbox.trigger("change");
          }
        } else if (res.status == "error") {
          console.log(res.message);
        } else {
          console.log("Contract Type Autocomlete: Something went wrong!");
        }
      })
      .fail((xhr, ajaxOptions, errorThrown) => {
        console.log(xhr.responseText + " " + xhr.responseText);
      });
  };

  //  Contract status Autocomplete
  window.loadAutocompleteContractStatus = function (options = {}) {
    var url = window.location.href.replace(/\/$/, "");
    var formType = url.substring(url.lastIndexOf("/") + 1);

    var selected;
    var contractStatusSelectbox = contractJobForm.find(
      '[name="contract_status"]'
    );

    if (parseValue(options.selected) != "") {
      selected =
        Object.keys(options.selected).length > 0
          ? Object.values(options.selected)
          : [];
    } else {
      selected = [];
    }

    contractStatusSelectbox.html("").trigger("change"); // Reset selectbox
    $.ajax({
      url: formApiUrl("admin/contract_status/autocomplete"),
      data: { formType: formType },
      type: "get",
      dataType: "json",
      headers: {
        Authorization: `Bearer ${wapLogin.getToken()}`,
      },
    })
      .done((res) => {
        contractStatusSelectbox.append(new Option("Select", "", false, false)); // Load initial select
        if (res.status == "success") {
          if (res.contract_statuses) {
            var contractStatuses = res.contract_statuses;
            var contractStatusOption;

            $.each(contractStatuses, function (bi, contractStatus) {
              if (
                selected.find((value) => {
                  return value == contractStatus.id;
                })
              ) {
                localisationOption = new Option(
                  contractStatus.name,
                  contractStatus.id,
                  true,
                  true
                );
              } else {
                localisationOption = new Option(
                  contractStatus.name,
                  contractStatus.id,
                  false,
                  false
                );
              }

              contractStatusSelectbox.append(localisationOption);
            });

            // Tigger
            contractStatusSelectbox.trigger("change");

            if (contractStatuses.length <= 1) {
              contractStatusSelectbox.val("2").trigger("change");
            } else {
              
            }
          }
        } else if (res.status == "error") {
          console.log(res.message);
        } else {
          console.log("Contract Status Autocomlete: Something went wrong!");
        }
      })
      .fail((xhr, ajaxOptions, errorThrown) => {
        console.log(xhr.responseText + " " + xhr.responseText);
      });
  };

  //  Currency Autocomplete
  window.loadAutocompleteCurrency = function (options = {}) {
    var selected;
    var currencySelectbox = contractJobForm.find('[name="contract_currency"]');

    if (parseValue(options.selected) != "") {
      selected =
        Object.keys(options.selected).length > 0
          ? Object.values(options.selected)
          : [];
    } else {
      selected = [];
    }

    currencySelectbox.html("").trigger("change"); // Reset selectbox
    $.ajax({
      url: formApiUrl("admin/localisation/currency/autocomplete"),
      type: "get",
      dataType: "json",
      headers: {
        Authorization: `Bearer ${wapLogin.getToken()}`,
      },
    })
      .done((res) => {
        currencySelectbox.append(new Option("Select", "", false, false)); // Load initial select
        if (res.status == "success") {
          if (res.localisation.currencies) {
            var currencies = res.localisation.currencies;
            var localisationOption;

            $.each(currencies, function (bi, currency) {
              if (
                selected.find((value) => {
                  return value == currency.id;
                })
              ) {
                localisationOption = new Option(
                  `${currency.code} - ${currency.symbol}`,
                  currency.id,
                  true,
                  true
                );
              } else {
                localisationOption = new Option(
                  `${currency.code} - ${currency.symbol}`,
                  currency.id,
                  false,
                  false
                );
              }

              currencySelectbox.append(localisationOption);
            });
            currencySelectbox.trigger("change");
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

  //  cCAMountry Autocomplete
  window.loadAutocompleteCAM = function (options = {}) {
    var selected;
    var camSelectbox = contractJobForm.find(
      '[name="customer_account_manager"]'
    );

    if (parseValue(options.selected) != "") {
      selected =
        Object.keys(options.selected).length > 0
          ? Object.values(options.selected)
          : [];
    } else {
      selected = [];
    }

    camSelectbox.html("").trigger("change"); // Reset selectbox
    $.ajax({
      url: formApiUrl("admin/employee/client_account_manager/autocomplete"),
      type: "get",
      dataType: "json",
      headers: {
        Authorization: `Bearer ${wapLogin.getToken()}`,
      },
    })
      .done((res) => {
        camSelectbox.append(new Option("Select", "", false, false)); // Load initial select
        if (res.status == "success") {
          if (res.employee.data) {
            var details = res.employee.data;
            var employeeOption;

            $.each(details, function (bi, cam) {
              if (
                selected.find((value) => {
                  return value == cam.id;
                })
              ) {
                employeeOption = new Option(cam.name, cam.id, true, true);
              } else {
                employeeOption = new Option(cam.name, cam.id, false, false);
              }

              camSelectbox.append(employeeOption);
            });
            camSelectbox.trigger("change");
          }
        } else if (res.status == "error") {
          console.log(res.message);
        } else {
          console.log(
            "Client Account Manager Autocomlete: Something went wrong!"
          );
        }
      })
      .fail((xhr, ajaxOptions, errorThrown) => {
        console.log(xhr.responseText + " " + xhr.responseText);
      });
  };

  //  Engineer Autocomplete
  window.loadAutocompleteEngineer = function (options = {}) {
    var selected;
    var cam_id; var loadView = {};
    var engineerSelectbox = contractJobForm.find('[name="engineer"]');
    if (parseValue(options.cam_id) != "") {
      cam_id = options.cam_id;
    } else {
      cam_id = 0;
    }

    // console.log(options.selected);
    if (parseValue(options.selected) != "") {
      selected =
        Object.keys(options.selected).length > 0
          ? Object.values(options.selected)
          : [];
    } else {
      selected = [];
    }

    if(parseValue(options.loadView) != '') {
      loadView = options.loadView;
    }

    engineerSelectbox.html("").trigger("change"); // Reset selectbox
    $.ajax({
      url: formApiUrl("admin/employee/engineer/autocomplete", options),
      type: "get",
      dataType: "json",
      headers: {
        Authorization: `Bearer ${wapLogin.getToken()}`,
      },
    }).done((res) => {
      engineerSelectbox.append(new Option("Select", "", false, false)); // Load initial select
      if (res.status == "success") {
        if (res.employee.data) {
          var details = res.employee.data;
          var employeeOption;

          $.each(details, function (bi, engineer) {
            if (
              selected.find((value) => {
                return value == engineer.id;
              })
            ) {
              employeeOption = new Option(
                engineer.name,
                engineer.id,
                true,
                true
              );
            } else {
              employeeOption = new Option(
                engineer.name,
                engineer.id,
                false,
                false
              );
            }

            engineerSelectbox.append(employeeOption);
          });
          engineerSelectbox.trigger("change");

          // Load engineer view
          if(Object.keys(loadView).length > 0) {
            loadEngineerDetailView(loadView.id);
          }
        }
      } else if (res.status == "error") {
        console.log(res.message);
      } else {
        console.log("Engineer Autocomlete: Something went wrong!");
      }
    }).fail((xhr, ajaxOptions, errorThrown) => {
      console.log(xhr.responseText + " " + xhr.responseText);
    });
  };

  $("#select_engineer").on("select2:select", function (e) {
    let engVal = $(this).val();
    loadEngineerDetailView(engVal); 
  });

  function loadEngineerDetailView(engVal) {
    // Get engineer detail
    $.ajax({
      url: formApiUrl("admin/employee/engineer/getEmployeeDetails", {
        engineer_id: engVal,
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
      complete: function () {
        loadSwal.close();
      },
    }).done((res) => {
      if (res.status == "success") {
        $("#engineer-details").show(); // Hide modal

        loadEngineerDetails(res.employee.data);
      } else if (res.status == "error") {
        console.log(res.message);
      } else {
        console.log("country Autocomlete: Something went wrong!");
      }
    }).fail((xhr, ajaxOptions, errorThrown) => {
      console.log(xhr.responseText + " " + xhr.responseText);
    });
  }

  function loadEngineerDetails(detail) {
    if (parseValue(detail) != "" && Object.keys(detail).length > 0) {
      console.log(detail);
      contractJobForm
        .find('.engineer-detail-area input[type="text"]')
        .prop("disabled", true);
      contractJobForm.find('[name="engineer_nh"]').val(detail.national_head);
      contractJobForm.find('[name="engineer_aisd"]').val(detail.aisd_head);
      contractJobForm.find('[name="engineer_rh"]').val(detail.regional_head);
      contractJobForm.find('[name="engineer_rsd"]').val(detail.rsd);
      contractJobForm.find('[name="engineer_asd"]').val(detail.asd);
      contractJobForm.find('[name="engineer_region"]').val(detail.region_name);
      contractJobForm.find('[name="engineer_area"]').val(detail.area_name);
      contractJobForm.find('[name="engineer_contact"]').val(detail.mobile);
    } else {
      toastr.info("Error occured while loading engineer detail!");
      contractJobForm
        .find('.engineer-detail-area input[type="text"]')
        .prop("disabled", false);
    }
  }

  // Sending CAM ID to ENGINEER
  contractJobForm.find('[name="customer_account_manager"]').on("select2:select", function (e) {
      e.preventDefault();
      if (parseValue($(this).val()) != "") {
        loadAutocompleteEngineer({
          cam_id: $(this).val(),
        });
      } else {
        contractJobForm.find('[name="engineer"]').html("").trigger("change");
      }
    });
});

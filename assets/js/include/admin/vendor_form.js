/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: vendor js
 */

var appvendor = {};

$(function () {
  const vendorDetailArea = $("#vendor--deatils--area");
  const vendorForm = $("#vendorForm");
  var vendorFormValidator;

  // region Autocomplete
  window.loadAutocompleteRegions = function (selected = []) {
    let moduleRegion = new ModuleRegion({
      autoloadUrl: formApiUrl("admin/region/autocomplete"),
      selectboxElement: vendorForm.find('[name="region_id"]'),
    });

    moduleRegion.autocomplete(selected);
  };

  // branch Autocomplete
  window.loadAutocompleteBranches = function (selected = []) {
    console.log(selected.region_id);
    let moduleBranch = new ModuleBranch({
      autoloadUrl: formApiUrl("admin/branch/autocomplete", {
        region_id: selected.region_id,
      }),
      selectboxElement: vendorForm.find('[name="branch_id"]'),
    });

    moduleBranch.autocomplete(selected);
  };

  // areas Autocomplete
  window.loadAutocompleteAreas = function (selected = []) {
    let moduleArea = new ModuleArea({
      autoloadUrl: formApiUrl("admin/area/autocomplete", {
        branch_id: selected.branch_id,
      }),
      selectboxElement: vendorForm.find('[name="area_id"]'),
    });

    moduleArea.autocomplete(selected);
  };

  // passing region ID to Branch
  vendorForm.find('[name="region_id"]').on("select2:select", function () {
    if (parseValue($(this).val()) != "") {
      loadAutocompleteBranches({
        region_id: $(this).val(),
      });
    } else {
      vendorForm.find('[name="branch_id"]').html("").trigger("change");
    }
  });

  // passing branch ID to area
  vendorForm.find('[name="branch_id"]').on("select2:select", function () {
    if (parseValue($(this).val()) != "") {
      loadAutocompleteAreas({
        branch_id: $(this).val(),
      });
    } else {
      vendorForm.find('[name="area_id"]').html("").trigger("change");
    }
  });

  //  country Autocomplete
  window.loadAutocompleteCountry = function (options = {}) {
    var selected;
    var countrySelectbox = vendorForm.find('[name="country_id"]');
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

  // state Autocomplete
  window.loadAutocompleteStates = function (options = {}) {
    var selected;
    var country_id;
    var stateSelectbox = vendorForm.find('[name="state_id"]');

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

  // city Autocomplete
  window.loadAutocompleteCities = function (options = {}) {
    var selected;
    var state_id;
    var citySelectbox = vendorForm.find('[name="city_id"]');

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

  // passing country ID to state
  if (emp_form_type == "edit") {
    // passing country ID to state
    vendorForm.find('[name="country_id"]').on("select2:select", function () {
      if (parseValue($(this).val()) != "") {
        loadAutocompleteStates({
          country_id: $(this).val(),
        });
      } else {
        vendorForm.find('[name="state_id"]').html("").trigger("change");
      }
    });

    vendorForm.find('[name="country_id"]').change(function () {
      if (parseValue($(this).val()) != "") {
        $("#country-dial-code").html(
          $(this).find(":selected").attr("data-dial-code")
        );
      } else {
        $("#country-dial-code").html("");
      }
    });
  } else {
    // passing country ID to state
    vendorForm.find('[name="country_id"]').change(function () {
      if (parseValue($(this).val()) != "") {
        console.log("hi");
        loadAutocompleteStates({
          country_id: $(this).val(),
        });
        $("#country-dial-code").html(
          $(this).find(":selected").attr("data-dial-code")
        );
      } else {
        $("#country-dial-code").html("");
        vendorForm.find('[name="state_id"]').html("").trigger("change");
      }
    });
  }

  // passing state ID to city
  if (emp_form_type == "edit") {
    // passing state ID to city
    vendorForm.find('[name="state_id"]').on("select2:select", function () {
      if (parseValue($(this).val()) != "") {
        loadAutocompleteCities({
          state_id: $(this).val(),
        });
      } else {
        vendorForm.find('[name="city_id"]').html("").trigger("change");
      }
    });
  } else {
    // passing state ID to city
    vendorForm.find('[name="state_id"]').change(function () {
      if (parseValue($(this).val()) != "") {
        loadAutocompleteCities({
          state_id: $(this).val(),
        });
      } else {
        vendorForm.find('[name="city_id"]').html("").trigger("change");
      }
    });
  }

  // Add user region
  vendorForm.on("click", "#btn-add-user-region", function (e) {
    e.preventDefault();
    let moduleRegion = new ModuleRegion({
      autoloadUrl: formApiUrl("admin/region/autocomplete"),
      selectboxElement: vendorForm.find('[name="region_id"]'),
    });

    moduleRegion.loadPrompt({
      selected: [vendorForm.find('[name="region_id"]').val()],
      submitAction: formApiUrl("admin/region/add"),
    });
  });

  // Add user branch
  vendorForm.on("click", "#btn-add-user-branch", function (e) {
    e.preventDefault();
    let moduleBranch = new ModuleBranch({
      autoloadUrl: formApiUrl("admin/branch/autocomplete"),
      selectboxElement: vendorForm.find('[name="branch_id"]'),
    });

    moduleBranch.loadPrompt({
      selected: [vendorForm.find('[name="branch_id"]').val()],
      submitAction: formApiUrl("admin/branch/add"),
    });
    loadAutocompleteRegions();
    vendorForm.find('[name="branch_id"]').html("").trigger("change");
    vendorForm.find('[name="area_id"]').html("").trigger("change");
  });

  // Add user area
  vendorForm.on("click", "#btn-add-user-area", function (e) {
    e.preventDefault();
    let moduleArea = new ModuleArea({
      autoloadUrl: formApiUrl("admin/area/autocomplete"),
      selectboxElement: vendorForm.find('[name="area_id"]'),
    });

    moduleArea.loadPrompt({
      selected: [vendorForm.find('[name="area_id"]').val()],
      submitAction: formApiUrl("admin/area/add"),
    });
    loadAutocompleteRegions();
    vendorForm.find('[name="branch_id"]').html("").trigger("change");
    vendorForm.find('[name="area_id"]').html("").trigger("change");
  });

  vendorForm.find(".password-generate").click(function (e) {
    e.preventDefault();
    let res = generateRandomString(8);
    vendorForm.find('[name="password"]').val(res);
  });

  // load details
  window.loadVendorDetail = function (href) {
    let loadSwal;

    $.ajax({
      url: formApiUrl("admin/vendor/detail", { vendor_id: vendor_id }),
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
          let vendorDetail = res.vendor.data;
          if (parseValue(res.vendor.data) != "") {
            vendorForm
              .find('[name="organization_name"]')
              .val(vendorDetail.organization_name);
            vendorForm
              .find('[name="contact_name"]')
              .val(vendorDetail.contact_name);
            vendorForm.find('[name="code"]').val(vendorDetail.code);
            vendorForm.find('[name="email"]').val(vendorDetail.email);
            vendorForm.find('[name="mobile"]').val(vendorDetail.mobile);
            vendorForm.find('[name="username"]').val(vendorDetail.username);
            vendorForm.find('[name="address1"]').val(vendorDetail.address1);
            vendorForm.find('[name="address2"]').val(vendorDetail.address2);
            vendorForm.find('[name="pincode"]').val(vendorDetail.pincode);
            vendorForm.find('[name="website"]').val(vendorDetail.website);
            vendorForm.find('[name="pan_number"]').val(vendorDetail.pan_number);
            vendorForm.find('[name="gst_number"]').val(vendorDetail.gst_number);
            vendorForm.find('[name="bank_name"]').val(vendorDetail.bank_name);
            vendorForm
              .find('[name="bank_account_person_name"]')
              .val(vendorDetail.bank_account_person_name);
            vendorForm
              .find('[name="bank_ifsc_code"]')
              .val(vendorDetail.bank_ifsc_code);
            vendorForm
              .find('[name="bank_branch_name"]')
              .val(vendorDetail.bank_branch_name);
            vendorForm
              .find('[name="bank_account_number"]')
              .val(vendorDetail.bank_account_number);

            // Load autocompletes
            loadAutocompleteCountry({ selected: [vendorDetail.country] });
            loadAutocompleteRegions({ selected: [vendorDetail.region_id] });
            loadAutocompleteBranches({
              region_id: vendorDetail.region_id,
              selected: [vendorDetail.branch_id],
            });
            loadAutocompleteAreas({
              branch_id: vendorDetail.branch_id,
              selected: [vendorDetail.area_id],
            });
            loadAutocompleteStates({
              country_id: vendorDetail.country,
              selected: [vendorDetail.state],
            });

            loadAutocompleteCities({
              state_id: vendorDetail.state,
              selected: [vendorDetail.city],
            });

            if (parseValue(res.vendor.file) != "") {
              $.each(res.vendor.file, function (listIn, listVal) {
                console.log(listVal);
                $("#vendorForm")
                  .find(".MultiFile-list")
                  .append(
                    `<span class="MultiFile-title"><a class="MultiFile-remove" href="#" data-id="${listVal.vendor_evaluation_id}" data-del="${listVal.file}">x </a>${listVal.file}</span><br/>`
                  );
              });

              $(".MultiFile-remove").click(function (e) {
                e.preventDefault();
                var file = $(this).attr("data-del");
                var vendor_evaluation_id = $(this).attr("data-id");
                var dataString = {
                  vendor_evaluation_id: vendor_evaluation_id,
                  file: file,
                };

                Swal.fire({
                  icon: "question",
                  title: "Are you sure to delete file",
                  allowOutsideClick: false,
                  allowEscapeKey: false,
                  showConfirmButton: true,
                  confirmButtonText: "Yes",
                  showCancelButton: true,
                  cancelButtonText: "No",
                  focusCancel: true,
                }).then((result) => {
                  if (result.isConfirmed) {
                    $.ajax({
                      url: formApiUrl(
                        "admin/vendor/evaluation/delete",
                        dataString
                      ),
                      type: "post",
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
                      .done(function (res) {
                        if (res.status == "success") {
                          window.location.reload();
                          toastr.success(res.message);
                          $(this).remove();
                        } else if (res.status == "error") {
                          toastr.error(res.message);
                        } else {
                          toastr.error("No response status!", "Error");
                        }
                      })
                      .fail(function (jqXHR, textStatus, errorThrown) {
                        toastr.error(
                          `${textStatus} <br />${errorThrown}`,
                          "Error"
                        );
                      });
                  }
                });
              });
            }
          } else {
            toastr.info("No vendor data");
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

  // vendor Form
  vendorFormValidator = vendorForm.validate({
    onkeyup: function (element) {
      $(element).valid();
    },
    onclick: function (element) {
      $(element).valid();
    },
    rules: {
      organization_name: {
        required: true,
      },
      contact_name: {
        required: true,
      },
      email: {
        required: true,
        email: true,
      },
      mobile: {
        required: true,
        digits: true,
        minlength: 10,
        maxlength: 10,
      },
      username: {
        required: true,
      },
      gst_number: {
        regex: "^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9]{1}Z[a-zA-Z0-9]{1}$",
      },
      pan_number: {
        regex: "^[A-Z]{5}[0-9]{4}[A-Z]{1}$",
      },
    },
    messages: {
      organization_name: {
        required: "Specify vendor name",
        minlength: "Specify atleast 3 characters",
      },
      contact_name: {
        required: "Specify contact name",
        minlength: "Specify atleast 3 characters",
      },
      email: {
        required: "Specify email address",
        email: "Specify valid email address",
      },
      mobile: {
        required: "Specify mobile number",
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

  //password valid rule
  if (emp_form_type == "edit") {
    vendorForm.find('[name="password"]').rules("remove");
  } else {
    vendorForm.find('[name="password"]').rules("add", {
      required: true,
      messages: {
        required: "Specify password",
      },
    });
  }

  function savevendor() {
    let loadSwal;
    var formData = new FormData(vendorForm[0]);
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
      },
      complete: function () {
        loadSwal.close();
      },
    })
      .done(function (res) {
        if (res.status == "success") {
          toastr.success(res.message);
          vendorForm[0].reset(); // Reset form
          setTimeout(function () {
            window.location.href = formUrl("admin/vendor");
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

  vendorForm.submit(function (e) {
    e.preventDefault();
    if (vendorFormValidator.valid()) {
      savevendor();
    }
  });
});

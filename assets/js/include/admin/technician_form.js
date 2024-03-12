/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: employee js
 */

var appemployee = {};

$(function () {
  const empDetailArea = $("#employee--deatils--area");
  const employeeTechnicianForm = $("#employeeTechnicianForm");
  var employeeTechnicianFormValidator;

  // region Autocomplete
  window.loadAutocompleteRegions = function (selected = []) {
    let moduleRegion = new ModuleRegion({
      autoloadUrl: formApiUrl("admin/region/autocomplete"),
      selectboxElement: employeeTechnicianForm.find('[name="region_id"]'),
    });

    moduleRegion.autocomplete(selected);
  };

  // branch Autocomplete
  window.loadAutocompleteBranches = function (selected = []) {
    let moduleBranch = new ModuleBranch({
      autoloadUrl: formApiUrl("admin/branch/autocomplete", {
        region_id: selected.region_id,
      }),
      selectboxElement: employeeTechnicianForm.find('[name="branch_id"]'),
    });

    moduleBranch.autocomplete(selected);
  };

  // areas Autocomplete
  window.loadAutocompleteAreas = function (selected = []) {
    let moduleArea = new ModuleArea({
      autoloadUrl: formApiUrl("admin/area/autocomplete", {
        branch_id: selected.branch_id,
      }),
      selectboxElement: employeeTechnicianForm.find('[name="area_id"]'),
    });

    moduleArea.autocomplete(selected);
  };

  // passing region ID to branch
  employeeTechnicianForm
    .find('[name="region_id"]')
    .on("select2:select", function () {
      if (parseValue($(this).val()) != "") {
        loadAutocompleteBranches({
          region_id: $(this).val(),
        });
      } else {
        employeeTechnicianForm
          .find('[name="branch_id"]')
          .html("")
          .trigger("change");
      }
    });

  // passing branch ID to area
  employeeTechnicianForm
    .find('[name="branch_id"]')
    .on("select2:select", function () {
      if (parseValue($(this).val()) != "") {
        loadAutocompleteAreas({
          branch_id: $(this).val(),
        });
      } else {
        employeeTechnicianForm
          .find('[name="area_id"]')
          .html("")
          .trigger("change");
      }
    });

  // Load designation autocomplete
  window.loadAutocompleteDesignations = function (selected = []) {
    let moduleDesignation = new ModuleDesignation({
      autoloadUrl: formApiUrl("admin/designation/autocomplete"),
      selectboxElement: employeeTechnicianForm.find('[name="designation_id"]'),
    });

    moduleDesignation.autocomplete(selected);
  };

  // Load department autocomplete
  window.loadAutocompleteDepartments = function (selected = []) {
    let moduleDepartment = new ModuleDepartment({
      autoloadUrl: formApiUrl("admin/department/autocomplete"),
      selectboxElement: employeeTechnicianForm.find('[name="department_id"]'),
    });

    moduleDepartment.autocomplete(selected);
  };

  // Load WorkExpertise autocomplete
  window.loadAutocompleteWorkExpertise = function (selected = []) {
    let moduleWorkExpertise = new ModuleWorkExpertise({
      autoloadUrl: formApiUrl("admin/work_expertise/autocomplete"),
      selectboxElement: employeeTechnicianForm.find('[name="work_expertise"]'),
    });

    moduleWorkExpertise.autocomplete(selected);
  };

  // Add user region
  employeeTechnicianForm.on("click", "#btn-add-user-region", function (e) {
    e.preventDefault();
    let moduleRegion = new ModuleRegion({
      autoloadUrl: formApiUrl("admin/region/autocomplete"),
      selectboxElement: employeeTechnicianForm.find('[name="region_id"]'),
    });

    moduleRegion.loadPrompt({
      selected: [employeeTechnicianForm.find('[name="region_id"]').val()],
      submitAction: formApiUrl("admin/region/add"),
    });
  });

  // Add user branch
  employeeTechnicianForm.on("click", "#btn-add-user-branch", function (e) {
    e.preventDefault();
    let moduleBranch = new ModuleBranch({
      autoloadUrl: formApiUrl("admin/branch/autocomplete"),
      selectboxElement: employeeTechnicianForm.find('[name="branch_id"]'),
    });

    moduleBranch.loadPrompt({
      selected: [employeeTechnicianForm.find('[name="branch_id"]').val()],
      submitAction: formApiUrl("admin/branch/add"),
    });
    loadAutocompleteRegions();
    employeeTechnicianForm.find('[name="branch_id"]').html("").trigger("change");
    employeeTechnicianForm.find('[name="area_id"]').html("").trigger("change");
  });

  // Add user designation
  employeeTechnicianForm.on("click", "#btn-add-user-designation", function (e) {
    e.preventDefault();
    let moduleDesignation = new ModuleDesignation({
      autoloadUrl: formApiUrl("admin/designation/autocomplete"),
      selectboxElement: employeeTechnicianForm.find('[name="designation_id"]'),
    });

    moduleDesignation.loadPrompt({
      selected: [employeeTechnicianForm.find('[name="designation_id"]').val()],
      submitAction: formApiUrl("admin/designation/add"),
    });
  });

  // Add user department
  employeeTechnicianForm.on("click", "#btn-add-user-department", function (e) {
    e.preventDefault();
    let moduleDepartment = new ModuleDepartment({
      autoloadUrl: formApiUrl("admin/department/autocomplete"),
      selectboxElement: employeeTechnicianForm.find('[name="department_id"]'),
    });

    moduleDepartment.loadPrompt({
      selected: [employeeTechnicianForm.find('[name="department_id"]').val()],
      submitAction: formApiUrl("admin/department/add"),
    });
  });

  // Add user WorkExpertise
  employeeTechnicianForm.on(
    "click",
    "#btn-add-user-work-expertise",
    function (e) {
      e.preventDefault();
      let moduleWorkExpertise = new ModuleWorkExpertise({
        autoloadUrl: formApiUrl("admin/work_expertise/autocomplete"),
        selectboxElement: employeeTechnicianForm.find('[name="work_expertise"]'),
      });

      moduleWorkExpertise.loadPrompt({
        selected: [employeeTechnicianForm.find('[name="work_expertise"]').val()],
        submitAction: formApiUrl("admin/work_expertise/add"),
      });
    }
  );

  // Add user area
  employeeTechnicianForm.on("click", "#btn-add-user-area", function (e) {
    e.preventDefault();
    let moduleArea = new ModuleArea({
      autoloadUrl: formApiUrl("admin/area/autocomplete"),
      selectboxElement: employeeTechnicianForm.find('[name="area_id"]'),
    });

    moduleArea.loadPrompt({
      selected: [employeeTechnicianForm.find('[name="area_id"]').val()],
      submitAction: formApiUrl("admin/area/add"),
    });
    loadAutocompleteRegions();
    employeeTechnicianForm.find('[name="branch_id"]').html("").trigger("change");
    employeeTechnicianForm.find('[name="area_id"]').html("").trigger("change");
  });

  // country Autocomplete
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

  // state Autocomplete
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

  // city Autocomplete
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

  /* load state autocomplete after country select */
  employeeTechnicianForm
    .find('[name="country_id"]')
    .on("select2:select", function () {
      if (parseValue($(this).val()) != "") {
        loadAutocompleteAddressStates({
          element: employeeTechnicianForm.find('[name="state_id"]'),
          params: {
            country_id: $(this).val(),
          },
        });
      } else {
        employeeTechnicianForm
          .find('[name="state_id"]')
          .html("")
          .trigger("change");
      }
    });

  /* set country dial code after country select/change*/
  employeeTechnicianForm.find('[name="country_id"]').change(function () {
    if (parseValue($(this).val()) != "") {
      $("#country-dial-code").html(
        $(this).find(":selected").attr("data-dial-code")
      );
    } else {
      $("#country-dial-code").html("");
    }
  });

  /* load city autocomplete after state select */
  employeeTechnicianForm
    .find('[name="state_id"]')
    .on("select2:select", function () {
      if (parseValue($(this).val()) != "") {
        loadAutocompleteAddressCities({
          element: employeeTechnicianForm.find('[name="city_id"]'),
          params: {
            state_id: $(this).val(),
          },
        });
      } else {
        employeeTechnicianForm
          .find('[name="city_id"]')
          .html("")
          .trigger("change");
      }
    });

  employeeTechnicianForm.find(".password-generate").click(function (e) {
    e.preventDefault();
    let res = generateRandomString(8);
    employeeTechnicianForm.find('[name="password"]').val(res);
  });

  // load details
  window.loadEmpDetail = function (href) {
    let loadSwal;

    $.ajax({
      url: formApiUrl("admin/employee/technician/detail", {
        employee_id: employee_id,
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
      success: async function (res) {
        if (res.status == "success") {
          let empDetail = res.employee.data;
          if (parseValue(res.employee.data) != "") {
            employeeTechnicianForm
              .find('[name="first_name"]')
              .val(empDetail.first_name);
            employeeTechnicianForm
              .find('[name="last_name"]')
              .val(empDetail.last_name);
            employeeTechnicianForm.find('[name="email"]').val(empDetail.email);
            employeeTechnicianForm
              .find('[name="mobile"]')
              .val(empDetail.mobile);
              employeeTechnicianForm
              .find('[name="username"]')
              .val(empDetail.username);
            employeeTechnicianForm
              .find('[name="address"]')
              .val(empDetail.address);
            employeeTechnicianForm
              .find('[name="pincode"]')
              .val(empDetail.pincode);
            employeeTechnicianForm
              .find('[name="joining_date"]')
              .val(empDetail.joining_date);

            // Load autocompletes
            loadAutocompleteRegions({ selected: [empDetail.region_id] });
            loadAutocompleteBranches({
              region_id: empDetail.region_id,
              selected: [empDetail.branch_id],
            });
            loadAutocompleteAreas({
              branch_id: empDetail.branch_id,
              selected: [empDetail.area_id],
            });
            loadAutocompleteDepartments({
              selected: [empDetail.department_id],
            });
            loadAutocompleteDesignations({
              selected: [empDetail.designation_id],
            });
            loadAutocompleteAddressCountries({
              element: employeeTechnicianForm.find('[name="country_id"]'),
              params: {
                selected: [empDetail.country],
              },
            });
            loadAutocompleteWorkExpertise({
              selected: [empDetail.work_expertise],
            });

            await loadAutocompleteAddressStates({
              element: employeeTechnicianForm.find('[name="state_id"]'),
              params: {
                country_id: empDetail.country,
                selected: [empDetail.state],
              },
            });

            await loadAutocompleteAddressCities({
              element: employeeTechnicianForm.find('[name="city_id"]'),
              params: {
                state_id: empDetail.state,
                selected: [empDetail.city],
              },
            })
          } else {
            toastr.info("No employee data");
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

  // employee Form
  employeeTechnicianFormValidator = employeeTechnicianForm.validate({
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
      mobile: {
        required: true,
        digits: true,
        minlength: 10,
        maxlength: 10,
      },
      username: {
        required: true,
      },
    },
    messages: {
      first_name: {
        required: "Specify employee name",
        minlength: "Specify atleast 3 characters",
      },
      last_name: {
        required: "Specify employee name",
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
    employeeTechnicianForm.find('[name="password"]').rules("remove");
  } else {
    employeeTechnicianForm.find('[name="password"]').rules("add", {
      required: true,
      messages: {
        required: "Specify password",
      },
    });
  }

  function saveEmployee() {
    let loadSwal;
    var formData = new FormData(employeeTechnicianForm[0]);

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
          employeeTechnicianForm[0].reset(); // Reset form
          setTimeout(function () {
            window.location.href = formUrl("admin/technician");
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

  employeeTechnicianForm.submit(function (e) {
    e.preventDefault();
    if (employeeTechnicianFormValidator.valid()) {
      saveEmployee();
    }
  });
});

/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: employee js
 */

var appemployee = {};

$(function () {
  const empDetailArea = $("#employee--deatils--area");
  const employeeAreaHeadForm = $("#employeeAreaHeadForm");
  var employeeAreaHeadFormValidator;

  // region Autocomplete
  window.loadAutocompleteRegions = function (selected = []) {
    let moduleRegion = new ModuleRegion({
      autoloadUrl: formApiUrl("admin/region/autocomplete"),
      selectboxElement: employeeAreaHeadForm.find('[name="region_id"]'),
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
      selectboxElement: employeeAreaHeadForm.find('[name="branch_id"]'),
    });

    moduleBranch.autocomplete(selected);
  };

  // Load designation autocomplete
  window.loadAutocompleteDesignations = function (selected = []) {
    let moduleDesignation = new ModuleDesignation({
      autoloadUrl: formApiUrl("admin/designation/autocomplete"),
      selectboxElement: employeeAreaHeadForm.find('[name="designation_id"]'),
    });

    moduleDesignation.autocomplete(selected);
  };

  // Load department autocomplete
  window.loadAutocompleteDepartments = function (selected = []) {
    let moduleDepartment = new ModuleDepartment({
      autoloadUrl: formApiUrl("admin/department/autocomplete"),
      selectboxElement: employeeAreaHeadForm.find('[name="department_id"]'),
    });

    moduleDepartment.autocomplete(selected);
  };

  // Load WorkExpertise autocomplete
  window.loadAutocompleteWorkExpertise = function (selected = []) {
    let moduleWorkExpertise = new ModuleWorkExpertise({
        autoloadUrl: formApiUrl("admin/work_expertise/autocomplete"),
        selectboxElement: employeeAreaHeadForm.find('[name="work_expertise"]'),
    });

    moduleWorkExpertise.autocomplete(selected);
  };

  // Add user region
  employeeAreaHeadForm.on("click", "#btn-add-user-region", function (e) {
    e.preventDefault();
    let moduleRegion = new ModuleRegion({
      autoloadUrl: formApiUrl("admin/region/autocomplete"),
      selectboxElement: employeeAreaHeadForm.find('[name="region_id"]'),
    });

    moduleRegion.loadPrompt({
      selected: [employeeAreaHeadForm.find('[name="region_id"]').val()],
      submitAction: formApiUrl("admin/region/add"),
    });
  });

  // Add user branch
  employeeAreaHeadForm.on("click", "#btn-add-user-branch", function (e) {
    e.preventDefault();
    let moduleBranch = new ModuleBranch({
      autoloadUrl: formApiUrl("admin/branch/autocomplete"),
      selectboxElement: employeeAreaHeadForm.find('[name="branch_id"]'),
    });

    moduleBranch.loadPrompt({
      selected: [employeeAreaHeadForm.find('[name="branch_id"]').val()],
      submitAction: formApiUrl("admin/branch/add"),
    });

    moduleBranch.loadRegionAutocomplete({});
  });

  // Add user designation
  employeeAreaHeadForm.on("click", "#btn-add-user-designation", function (e) {
    e.preventDefault();
    let moduleDesignation = new ModuleDesignation({
      autoloadUrl: formApiUrl("admin/designation/autocomplete"),
      selectboxElement: employeeAreaHeadForm.find('[name="designation_id"]'),
    });

    moduleDesignation.loadPrompt({
      selected: [employeeAreaHeadForm.find('[name="designation_id"]').val()],
      submitAction: formApiUrl("admin/designation/add"),
    });
  });

  // Add user WorkExpertise
  employeeAreaHeadForm.on('click', '#btn-add-user-work-expertise', function (e) {
    e.preventDefault();
    let moduleWorkExpertise = new ModuleWorkExpertise({
        autoloadUrl: formApiUrl('admin/work_expertise/autocomplete'),
        selectboxElement: employeeAreaHeadForm.find('[name="work_expertise"]')
    });

    moduleWorkExpertise.loadPrompt({
        selected: [employeeAreaHeadForm.find('[name="work_expertise"]').val()],
        submitAction: formApiUrl('admin/work_expertise/add')
    });
  });

  // Add user department
  employeeAreaHeadForm.on("click", "#btn-add-user-department", function (e) {
    e.preventDefault();
    let moduleDepartment = new ModuleDepartment({
      autoloadUrl: formApiUrl("admin/department/autocomplete"),
      selectboxElement: employeeAreaHeadForm.find('[name="department_id"]'),
    });

    moduleDepartment.loadPrompt({
      selected: [employeeAreaHeadForm.find('[name="department_id"]').val()],
      submitAction: formApiUrl("admin/department/add"),
    });
  });

  // passing region ID to branch
  employeeAreaHeadForm
    .find('[name="region_id"]')
    .on("select2:select", function () {
      if (parseValue($(this).val()) != "") {
        loadAutocompleteBranches({
          region_id: $(this).val(),
        });
      } else {
        employeeAreaHeadForm
          .find('[name="branch_id"]')
          .html("")
          .trigger("change");
      }
    });

  //  country Autocomplete
  window.loadAutocompleteCountry = function (options = {}) {
    var selected;
    var countrySelectbox = employeeAreaHeadForm.find('[name="country_id"]');
    var url = window.location.href.replace(/\/$/, ''); 
    var formType = url.substr(url.lastIndexOf('/') + 1);
    
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
            // if(formType == 'add'){
            //   countrySelectbox.val('103').trigger("change");
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
    var stateSelectbox = employeeAreaHeadForm.find('[name="state_id"]');

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
    var citySelectbox = employeeAreaHeadForm.find('[name="city_id"]');

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
    employeeAreaHeadForm.find('[name="country_id"]').on("select2:select", function () {
      if (parseValue($(this).val()) != "") {
        loadAutocompleteStates({
          country_id: $(this).val(),
        });
      } else {
        employeeAreaHeadForm.find('[name="state_id"]').html("").trigger("change");
      }
    });

    employeeAreaHeadForm.find('[name="country_id"]').change(function () {
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
    employeeAreaHeadForm
      .find('[name="country_id"]')
      .change(function () {
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
          employeeAreaHeadForm.find('[name="state_id"]').html("").trigger("change");
        }
      });
  }

  // passing state ID to city
  if (emp_form_type == "edit") {
    // passing state ID to city
    employeeAreaHeadForm
      .find('[name="state_id"]')
      .on("select2:select", function () {
        if (parseValue($(this).val()) != "") {
          loadAutocompleteCities({
            state_id: $(this).val(),
          });
        } else {
          employeeAreaHeadForm
            .find('[name="city_id"]')
            .html("")
            .trigger("change");
        }
      });
  } else {
    // passing state ID to city
    employeeAreaHeadForm.find('[name="state_id"]').change(function () {
      if (parseValue($(this).val()) != "") {
        loadAutocompleteCities({
          state_id: $(this).val(),
        });
      } else {
        employeeAreaHeadForm
          .find('[name="city_id"]')
          .html("")
          .trigger("change");
      }
    });
  }

  employeeAreaHeadForm.find(".password-generate").click(function (e) {
    e.preventDefault();
    let res = generateRandomString(8);
    employeeAreaHeadForm.find('[name="password"]').val(res);
  });

  // load details
  window.loadEmpDetail = function (href) {
    let loadSwal;

    $.ajax({
      url: formApiUrl("admin/employee/area_head/detail", {
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
      success: function (res) {
        if (res.status == "success") {
          let empDetail = res.employee.data;
          if (parseValue(res.employee.data) != "") {
            employeeAreaHeadForm
              .find('[name="first_name"]')
              .val(empDetail.first_name);
            employeeAreaHeadForm
              .find('[name="last_name"]')
              .val(empDetail.last_name);
            employeeAreaHeadForm.find('[name="email"]').val(empDetail.email);
            employeeAreaHeadForm.find('[name="mobile"]').val(empDetail.mobile);
            employeeAreaHeadForm
              .find('[name="username"]')
              .val(empDetail.username);
            employeeAreaHeadForm
              .find('[name="address"]')
              .val(empDetail.address);
            employeeAreaHeadForm
              .find('[name="pincode"]')
              .val(empDetail.pincode);
            employeeAreaHeadForm
              .find('[name="joining_date"]')
              .val(empDetail.joining_date);

            // Load autocompletes
            loadAutocompleteRegions({ selected: [empDetail.region_id] });
            loadAutocompleteBranches({
              region_id: empDetail.region_id,
              selected: [empDetail.branch_id],
            });
            loadAutocompleteDepartments({
              selected: [empDetail.department_id],
            });
            loadAutocompleteDesignations({
              selected: [empDetail.designation_id],
            });
            loadAutocompleteCountry({ selected: [empDetail.country] });
            loadAutocompleteWorkExpertise({
              selected: [empDetail.work_expertise],
            });
            loadAutocompleteStates({
              country_id: empDetail.country,
              selected: [empDetail.state],
            });
            loadAutocompleteCities({
              state_id: empDetail.state,
              selected: [empDetail.city],
            });
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
  employeeAreaHeadFormValidator = employeeAreaHeadForm.validate({
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
      region_id: {
        required: true,
      },
      branch_id: {
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
      region_id: {
        required: "Specify Region",
      },
      branch_id: {
        required: "Specify Branch",
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
    employeeAreaHeadForm.find('[name="password"]').rules("remove");
  } else {
    employeeAreaHeadForm.find('[name="password"]').rules("add", {
      required: true,
      messages: {
        required: "Specify password",
      },
    });
  }

  function saveEmployee() {
    let loadSwal;
    var formData = new FormData(employeeAreaHeadForm[0]);
    console.log(formActionUrl);
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
          employeeAreaHeadForm[0].reset(); // Reset form
          setTimeout(function () {
            window.location.href = formUrl("admin/area_head");
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

  employeeAreaHeadForm.submit(function (e) {
    e.preventDefault();
    if (employeeAreaHeadFormValidator.valid()) {
      saveEmployee();
    }
  });
});

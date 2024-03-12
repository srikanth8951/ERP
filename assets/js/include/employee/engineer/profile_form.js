/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: employee js
 */

var appemployee = {};

$(function () {
    const empDetailArea = $('#engineer--details--area');
    const employeeForm = $('#employeeForm');
    var employeeFormValidator;

    //  country Autocomplete
    window.loadAutocompleteCountry = function (options = {}) {
        var selected;
        var countrySelectbox = employeeForm.find('[name="country_id"]');

        if (parseValue(options.selected) != '') {
            selected = (Object.keys(options.selected).length > 0) ? Object.values(options.selected) : [];
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
        }).done((res) => {
            countrySelectbox.append(new Option("Select", "", false, false)); // Load initial select
            if (res.status == "success") {
                if (res.localisation.countries) {
                    var localisations = res.localisation.countries;
                    var localisationOption;

                    $.each(localisations, function (bi, country) {
                        if (selected.find((value) => {
                            return value == country.id
                        })) {
                            localisationOption = new Option(country.name, country.id, true, true);
                        } else {
                            localisationOption = new Option(country.name, country.id, false, false);
                        }

                        countrySelectbox.append(localisationOption);

                        localisationOption.setAttribute('data-dial-code', `+${country.dial_code}`);
                    });

                    countrySelectbox.trigger("change");
                }
            } else if (res.status == "error") {
                console.log(res.message);
            } else {
                console.log("country Autocomlete: Something went wrong!");
            }
        }).fail((xhr, ajaxOptions, errorThrown) => {
            console.log(xhr.responseText + " " + xhr.responseText);
        });
    };

    // state Autocomplete
    window.loadAutocompleteStates = function (options = {}) {
        var selected; var country_id;
        var stateSelectbox = employeeForm.find('[name="state_id"]');

        if (parseValue(options.country_id) != '') {
            country_id = options.country_id;
        } else {
            country_id = 0;
        }

        if (parseValue(options.selected) != '') {
            selected = (Object.keys(options.selected).length > 0) ? Object.values(options.selected) : [];
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
        }).done((res) => {
            stateSelectbox.append(new Option("Select", "", false, false)); // Load initial select
            if (res.status == "success") {
                if (res.localisation.states) {
                    var localisations = res.localisation.states;
                    var localisationOption;

                    $.each(localisations, function (bi, state) {
                        if (selected.find((value) => {
                            return value == state.id
                        })) {
                            localisationOption = new Option(state.name, state.id, true, true);
                        } else {
                            localisationOption = new Option(state.name, state.id, false, false);
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

    // passing national ID to state
    employeeForm.find('[name="country_id"]').change(function () {
        if (parseValue($(this).val()) != '') {
            loadAutocompleteStates({
                'country_id': $(this).val()
            });
            
            $('#country-dial-code').html($(this).find(':selected').attr('data-dial-code'));
        } else {
            $('#country-dial-code').html('');
            employeeForm.find('[name="state_id"]').html('').trigger('change');
        }
    });

    employeeForm.find('.password-generate').click(function (e) {
        e.preventDefault();
        let res = generateRandomString(8);
        employeeForm.find('[name="password"]').val(res);
    });

    // load details
    window.loadEmpDetail = function (href) {
        let loadSwal;

        $.ajax({
            url: formApiUrl('employee/engineer/profile/detail', { employee_id: employee_id }),
            type: 'get',
            dataType: 'json',
            headers: {
                Authorization: `Bearer ${wapLogin.getToken()}`
            },
            beforeSend: function () {
                loadSwal = Swal.fire({
                    html: '<div class="my-4 text-center d-inline-block">' + loaderContent + '</div>',
                    customClass: {
                        popup: 'col-6 col-sm-5 col-md-3 col-lg-2'
                    },
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false
                });
            },
            success: function (res) {
                if (res.status == 'success') {
                    let empDetail = res.employee.data;
                    if (parseValue(res.employee.data) != '') {
                        employeeForm.find('[name="first_name"]').val(empDetail.first_name);
                        employeeForm.find('[name="last_name"]').val(empDetail.last_name);
                        employeeForm.find('[name="email"]').val(empDetail.email);
                        employeeForm.find('[name="mobile"]').val(empDetail.mobile);
                        employeeForm.find('[name="username"]').val(empDetail.username);
                        employeeForm.find('[name="address"]').val(empDetail.address);
                        employeeForm.find('[name="city"]').val(empDetail.city);
                        employeeForm.find('[name="pincode"]').val(empDetail.pincode);
                      //  employeeForm.find('[name="joining_date"]').val(empDetail.joining_date);
                        
                        //loadAutocompleteDepartments({ selected: [empDetail.department_id] });
                        //loadAutocompleteDesignations({ selected: [empDetail.designation_id] });
                        loadAutocompleteCountry({ selected: [empDetail.country] });
                        //loadAutocompleteWorkExpertise({ selected: [empDetail.work_expertise] });
                        loadAutocompleteStates({ country_id: empDetail.country, selected: [empDetail.state] });

                    } else {
                        toastr.info('No employee data');
                    }
                } else if (res.status == 'error') {
                    toastr.error(res.message);
                } else {
                    toastr.error('No response status', 'Error');
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                toastr.error(`${textStatus} <br />${errorThrown}`, 'Error');
            },
            complete: function () {
                loadSwal.close();
            }
        });
    }

    // employee Form
    employeeFormValidator = employeeForm.validate({
        onkeyup: function (element) {
            $(element).valid();
        },
        onclick: function (element) {
            $(element).valid();
        },
        rules: {
            first_name: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            mobile: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10
            },
            username: {
                required: true
            },
        },
        messages: {
            first_name: {
                required: 'Specify employee name',
                minlength: 'Specify atleast 3 characters'
            },
            email: {
                required: 'Specify email address',
                email: 'Specify valid email address'
            },
            mobile: {
                required: 'Specify mobile number',
                digits: 'Mobile number must be numeric',
                minlength: 'Specify valid 10 digit mobile number',
                minlength: 'Specify valid 10 digit mobile number'
            },
            username: {
                required: 'Specify username'
            },
        },
        errorPlacement: function (error, element) {
            // Add the `invalid-feedback` class to the error element
            error.addClass("invalid-feedback");

            if (element.prop("type") === "checkbox" || element.attr('data-toggle') == 'select2') {
                // error.insertAfter( element.next( "label" ) );
                element.parents('.ele-jqValid').append(error);
            } else {
                error.insertAfter(element);
            }

        },
    });

    if (emp_form_type == 'edit') {
    	
        employeeForm.find('[name="password"]').rules( "remove" );

     } else {
        employeeForm.find('[name="password"]').rules( "add", {
            required: true,
            messages: {
              required: "Specify password",
            }
          });
     }

    function saveEmployee() {
        let loadSwal;
        var formData = new FormData(employeeForm[0]);

        $.ajax({
            url: formActionUrl,
            type: 'post',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,
            headers: {
                Authorization: `Bearer ${wapLogin.getToken()}`
            },
            beforeSend: function () {
                loadSwal = Swal.fire({
                    html: '<div class="my-4 text-center d-inline-block">' + loaderContent + '</div>',
                    customClass: {
                        popup: 'col-6 col-sm-5 col-md-3 col-lg-2'
                    },
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false
                });
            },
            complete: function () {
                loadSwal.close();
            }
        }).done(function (res) {
            if (res.status == 'success') {
                toastr.success(res.message);
                employeeForm[0].reset();    // Reset form
                setTimeout(function () {
                    window.location.href = formUrl('employee/engineer/profile');
                });
            } else if (res.status == 'error') {
                toastr.error(res.message);
            } else {
                toastr.error('No response status', 'Error');
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            toastr.error(`${textStatus} - ${errorThrown}`, 'Error');
        });
    }

    employeeForm.submit(function (e) {
        e.preventDefault();
        if (employeeFormValidator.valid()) {
            saveEmployee();
        }

    });

});


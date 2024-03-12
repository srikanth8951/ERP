/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: Contract Job js
 */

var appcontractjob = {};

$(function () {
  var contractJobDetailAreaValidator;
  const ppmModal = $("#ppmModal");

  // ppm list
  $("#btn-ppm-list").click(function (e) {
    e.preventDefault();

    ppmModal.find(".modal-header .modal-title").html("PPM list");
    ppmModal.modal({
      backdrop: "static",
      keyboard: false,
      show: true,
    });
    loadPPMDetail();
  });

  ppmModal.find(".close").click(function () {
    ppmModal.find("#PPM-detail").html("");
  });

  $("#btn-pick-location").click(function (e) {
    e.preventDefault();
    getPlacePickDetail(
      contractJobDetailArea.find('[name="input-place-search"]').html()
    );
    // $('#placeSearchModal').find('#input-place-search').html();
  });

  $("#btn-search-location").click(function (e) {
    e.preventDefault();
    getPlaceSearchDetail().then(function (data) {
      contractJobDetailArea
        .find('[name="customer_location_lattitude"]')
        .html(data.geometry.location.lat());
      contractJobDetailArea
        .find('[name="customer_location_longitude"]')
        .html(data.geometry.location.lng());
      contractJobDetailArea.find(".customer-map-location").html(`
                  <p class="mb-1"><label class="mb-0">Address:</label> ${
                    data.formatted_address
                  }</p>
                  <p class="mb-0"><label class="mb-0">Lattitude:</label> ${data.geometry.location.lat()} <br /> <label class="mb-0">Longitude:</label> ${data.geometry.location.lng()}</p>
              `);
    });
  });

  function loadPPMView(details) {
    if (parseValue(details) != "" && Object.keys(details).length > 0) {
      if (details) {
        $.each(details, function (listIn, listVal) {
          ppmModal.find("#PPM-detail").append(`
            <tr>
                <td>${listVal.contract_job_name}</td>
                <td>${moment(listVal.start_date).format("DD/MMM/YYYY")}</td>
                <td>${listVal.status.name}</td>
                <td>${moment(listVal.end_date).format("DD/MMM/YYYY")}</td>
            </tr>`);
        });
      }
    } else {
      toastr.info("Error occured while loading ppm detail!");
    }
  }

  window.loadPPMDetail = function () {
    let loadSwal;

    $.ajax({
      url: formApiUrl("employee/cam/contract_job/ppm/list", {
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
          if (parseValue(res.contract_job_ppm_frequencies) != "") {
            // console.log(res);
            loadPPMView(res.contract_job_ppm_frequencies);
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

  function getCustomerDetails(customer_id) {
    let loadSwal;
    $.ajax({
      url: formApiUrl("employee/customer/detail", { customer_id: customer_id }),
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
          if (parseValue(res.customer.data) != "") {
            let details = res.customer.data;
            contractJobDetailArea
              .find("#customer-company-name")
              .html(details.company_name);
            // contractJobDetailArea.find('#customer-contact-name').html(details.contact_name);
            contractJobDetailArea
              .find("#customer-job-number")
              .html(details.job_number);
            contractJobDetailArea
              .find("#customer-sector")
              .html(details.customer_sector_title);
            contractJobDetailArea
              .find("#customer-username")
              .html(details.username);
            contractJobDetailArea
              .find("#customer-billing-address")
              .html(details.billing_address);
            contractJobDetailArea
              .find("#customer-billing-address-contact-name")
              .html(details.billing_address_contact_name);
            contractJobDetailArea
              .find("#customer-billing-address-email")
              .html(details.billing_address_email);
            contractJobDetailArea
              .find("#customer-site-address")
              .html(details.site_address);
            contractJobDetailArea
              .find("#customer-billing-address-country")
              .html(details.billing_address_country_name);
            contractJobDetailArea
              .find("#customer-billing-address-state")
              .html(details.billing_address_state_name);
            contractJobDetailArea
              .find("#customer-billing-address-city")
              .html(details.billing_address_city_name);
            contractJobDetailArea
              .find("#customer-billing-address-pincode")
              .html(details.billing_address_pincode);
            contractJobDetailArea
              .find("#customer-billing-address-mobile")
              .html(details.billing_address_mobile);
            contractJobDetailArea
              .find("#customer-site-address-contact-name")
              .html(details.site_address_contact_name);
            contractJobDetailArea
              .find("#customer-site-address-email")
              .html(details.site_address_email);
            contractJobDetailArea
              .find("#customer-site-address-country")
              .html(details.site_address_country_name);
            contractJobDetailArea
              .find("#customer-site-address-state")
              .html(details.site_address_state_name);
            contractJobDetailArea
              .find("#customer-site-address-city")
              .html(details.site_address_city_name);
            contractJobDetailArea
              .find("#customer-site-address-pincode")
              .html(details.site_address_pincode);
            contractJobDetailArea
              .find("#customer-site-address-mobile")
              .html(details.site_address_mobile);
            contractJobDetailArea
              .find("#customer-website")
              .html(details.website);
            contractJobDetailArea
              .find("#customer-gst-number")
              .html(details.gst_number);
            contractJobDetailArea
              .find("#customer-pan-number")
              .html(details.pan_number);
            contractJobDetailArea
              .find("#customer-payment-term")
              .html(details.payment_term_title);
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

  function loadContractJobView(detail) {
    if (parseValue(detail) != "" && Object.keys(detail).length > 0) {
      contractJobDetailArea.find("#job-title").html(detail.job_title);
      contractJobDetailArea.find("#job-number").html(detail.job_number);
      contractJobDetailArea.find("#sap-job-number").html(detail.sap_job_number);
      contractJobDetailArea
        .find("#po-number")
        .html(detail.purchase_order_number);
      contractJobDetailArea
        .find("#contract-nature")
        .html(detail.contract_nature_name);
      contractJobDetailArea
        .find("#contract-type")
        .html(detail.contract_type_name);
      contractJobDetailArea
        .find("#deployed-people-number")
        .html(detail.deployed_people_number);
      contractJobDetailArea.find("#ppm-frequency").html(detail.ppm_frequency);
      contractJobDetailArea
        .find("#customer-account-manager")
        .html(detail.customer_account_manager_name);
      contractJobDetailArea.find("#engineer").html(detail.engineer_name);
      contractJobDetailArea
        .find("#contract-currency")
        .html(detail.contract_currency_name);
      contractJobDetailArea
        .find("#contract-gst-value")
        .html(detail.contract_gst_value);
      contractJobDetailArea.find("#contract-value").html(detail.contract_value);
      contractJobDetailArea
        .find("#total-contract-value")
        .html(detail.total_contract_value);
      contractJobDetailArea
        .find("#expected-gross-margin")
        .html(detail.expected_gross_margin);
      contractJobDetailArea
        .find("#contract-status")
        .html(detail.contract_status_name);
      contractJobDetailArea.find("#contract-period").html(detail.period);
      contractJobDetailArea
        .find("#job-location-latitude")
        .html(detail.geolocation_lattitude);
      contractJobDetailArea
        .find("#job-location-longitude")
        .html(detail.geolocation_longitude);
      contractJobDetailArea
        .find("#job-location-range")
        .html(detail.geolocation_range);
      contractJobDetailArea
        .find("#contract-period-fromdate")
        .html(moment(detail.period_fromdate).format("DD/MMM/YYYY"));
      contractJobDetailArea
        .find("#contract-period-todate")
        .html(moment(detail.period_todate).format("DD/MMM/YYYY"));

      // Load asset details
      loadAssetDetails(detail.contract_job_id);

      // load customer details
      console.log(detail.customer_id);
      getCustomerDetails(detail.customer_id);

      if (detail.contract_status_id != 3) {
        contractJobDetailArea.find("#contract_period").show();
      } else {
        contractJobDetailArea.find("#contract_period").hide();
      }
    } else {
      toastr.info("Error occured while loading customer detail!");
      contractJobDetailArea
        .find(
          '.customer-detail-area input[type="text"], .customer-detail-area textarea, .customer-detail-area select, .customer-detail-area button.password-generate'
        )
        .prop("disabled", false);
    }
  }

  // load details
  window.loadContractJobDetail = function () {
    let loadSwal;

    $.ajax({
      url: formApiUrl("employee/cam/contract_job/detail", {
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
});

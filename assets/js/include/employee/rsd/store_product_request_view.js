/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: Contract Job js
 */

var appProductRequest = {};

$(function () {
  function loadProductRequestView(detail) {
    if (parseValue(detail) != "" && Object.keys(detail).length > 0) {
      requestbDetailArea.find("#heading-area").html(`
            <h4>${detail.request_number}</h4>
            <h6>${detail.title}</h6>
       `);
      requestbDetailArea.find("#request-detail-area")
        .html(`
        <div class="col-md-8">
          <div class="media"><span class="status-block text-white bg-brown">${detail.status.name}</span>
            <div class="media-body ml-4">
                <p class="mb-0 d-flex align-items-center"><span class="w-25"><b>Region</b></span><span class="w-70">${detail.region_name}</span></p>
                <p class="mb-0 d-flex align-items-center"><span class="w-25"><b>Branch</b></span><span class="w-70">${detail.branch_name}</span></p>
                <p class="mb-0 d-flex align-items-center"><span class="w-25"><b>Created On</b></span><span class="w-70">${detail.created_datetime}</span></p>
            </div>
          </div>
         </div>
        <div class="col-md-4">
          <div class="align-items-center"><span class="d-block"><b>Engineer</b></span><span class="d-block">${detail.enginner_name}</span><span class="d-block">${detail.employee_mobile}</span></div>
        </div>`);
      if (detail.products) {
        
        $.each(detail.products, function (listIn, listVal) {

          let total_price = '';

          if (listVal.amount) {
            total_price = parseFloat(listVal.amount) * parseFloat(listVal.requested_quantity);
            total_price = total_price.toFixed(2);
          } else {
            total_price = parseFloat("0.00");
          }

          requestbDetailArea.find("#request-product-detail").append(`
          <tbody>
            <tr>
                <td>${listVal.name}</td>
                <td>${listVal.category_name}</td>
                <td>${listVal.sub_category_name}</td>
                <td>${listVal.requested_quantity}</td>
                <td>${total_price}</td>
            </tr>
          </tbody>`);
        });
      }
         requestbDetailArea.find("#request-product-detail").html(detail.sap_job_number);
      //    requestbDetailArea.find("#po-number").html(detail.purchase_order_number);
    } else {
      toastr.info("Error occured while loading customer detail!");
      requestbDetailArea
        .find(
          '.customer-detail-area input[type="text"], .customer-detail-area textarea, .customer-detail-area select, .customer-detail-area button.password-generate'
        )
        .prop("disabled", false);
    }
  }

  // load details
  window.loadRequestDetail = function () {
    let loadSwal;

    $.ajax({
      url: formApiUrl("employee/rsd/store_product/request/detail", {
        request_id: request_id,
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
          if (parseValue(res.product_request) != "") {
            loadProductRequestView(res.product_request);
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

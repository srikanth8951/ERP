/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: product js
 */

var appproduct = {};

$(function () {
  const listProduct = $("#product--deatils--area");
  const lengthContainer = listProduct.find('[data-jy-length="record"]');
  const searchContainer = listProduct.find('[data-jy-search="record"]');
  const tableContainer = listProduct.find('[data-container="productListArea"]');

  const listContainer = tableContainer.find(
    '[data-container="productTlistArea"]'
  );
  const listPagination = tableContainer.find(
    '[data-pagination="productTlistArea"]'
  );
  listPagination.find(".list-pagination").html("");
  listPagination.find(".list-pagination-label").html("");
  const quantityForm = $("#quantityForm");
  var quantityFormValidator;
  const quantityModal = $("#quantityModal");
  const btnResetQuantityForm = $("#btn-reset-quantity-form");

  window.loadEmptyDetail = function () {
    listContainer.html("");
    listPagination.find(".list-pagination").html("");
    listPagination.find(".list-pagination-label").html("");
    listContainer.append(
      "<tr>" +
        '<td colspan="6" class="text-center">No Details Found!</td>' +
        "</tr>"
    );
  };

  window.loadDetails = function (href) {
    let loadSwal;
    let filterData = {};
    if (lengthContainer.val() != "") {
      filterData["length"] = lengthContainer.val();
    }
    if (searchContainer.find('input[name="search"]').val() != "") {
      filterData["search"] = searchContainer.find('input[name="search"]').val();
    }

    $.ajax({
      url: href,
      type: "get",
      dataType: "json",
      headers: {
        Authorization: `Bearer ${wapLogin.getToken()}`,
      },
      data: filterData,
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
          if (res.products) {
            listContainer.html("");
            var details = res.products;
            var pagination = res.pagination;
            if (details.length && pagination.total > 0) {
              let status_badge_class = "";
              $.each(details, function (listIn, listVal) {
                if (listVal.status == 1) {
                  status_badge_class = "badge-success";
                } else {
                  status_badge_class = "badge-danger";
                }
                let url = formUrl(
                  "admin/store/products/edit/" + listVal.product_id
                );
                listContainer.append(
                  "<tr>" +
                    "<td>" +
                    (listIn + 1) +
                    "</td>" +
                    "<td>" +
                    listVal.name +
                    "</td>" +
                    "<td>" +
                    listVal.category_name +
                    "</td>" +
                    "<td>" +
                    listVal.sub_category_name +
                    "</td>" +
                    "<td>" +
                    '<span class="mr-3">' +
                    listVal.quantity +
                    "</span>" +
                    '<a href="javascript:void(0)" data-product="' +
                    listVal.product_id +
                    '" class="btn btn-link rounded-circle  btn-sm float-right" id="btn-update-quantity" title="Update Quantity" data-original-title="Update Quantity"><i class="mdi mdi-plus"></i></a>' +
                    "</td>" +
                    "<td>" +
                    '<a href="' +
                    url +
                    '" class="text-white btn btn-sm btn-dark waves-effect waves-light btn-edit-product mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit product"><i class="mdi mdi-pencil"></i></a>' +
                    '<a href="javascript:void(0)" data-product="' +
                    listVal.product_id +
                    '" class="text-white btn btn-sm btn-danger waves-effect waves-light btn-delete-product mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="mdi mdi-delete"></i></a>' +
                    "</td>" +
                    "</tr>"
                );
              });

              listContainer.find('[data-toggle="tooltip"]').tooltip(); // Load tooltip
              listPagination
                .find(".list-pagination-label")
                .html(
                  `Showing ${pagination.start} to ${
                    parseInt(pagination.start) - 1 + pagination.records
                  } of ${pagination.total}`
                );
              listPagination.find(".list-pagination").pagination({
                items: parseInt(pagination.total),
                itemsOnPage: parseInt(pagination.length),
                currentPage: Math.ceil(
                  parseInt(pagination.start) / parseInt(pagination.length)
                ),
                displayedPages: 3,
                navStyle: "pagination",
                listStyle: "page-item",
                linkStyle: "page-link",
                onPageClick: function (pageNumber, event) {
                  var page_link = formApiUrl("admin/store/product/list", {
                    start: parseInt(pagination.length) * (pageNumber - 1) + 1,
                  });
                  loadDetails(page_link);
                },
              });
            } else {
              loadEmptyDetail();
            }
          } else {
            loadEmptyDetail();
          }
        } else if (res.status == "error") {
          // toastr.error(res.message);
          loadEmptyDetail();
        } else {
          toastr.error("No response status", "Error");
          loadEmptyDetail();
        }
      },
      error: function (xhr, textStatus, errorThrown) {
        toastr.error(`${textStatus} <br />${errorThrown}`, "Error");
        loadEmptyDetail();
      },
      complete: function () {
        loadSwal.close();
      },
    });
  };

  lengthContainer.change(function () {
    loadDetails(formApiUrl("admin/store/product/list")); // Load product details
  });

  searchContainer.submit(function (e) {
    e.preventDefault();
    loadDetails(formApiUrl("admin/store/product/list")); // Load product details
  });

  // Delete product
  $(listContainer).on("click", ".btn-delete-product", function (e) {
    e.preventDefault();
    var product_id = $(this).attr("data-product");
    Swal.fire({
      icon: "question",
      title: "Are you sure to delete product",
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
          url: formApiUrl("admin/store/product/delete", {
            product_id: product_id,
          }),
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
              loadDetails(formApiUrl("admin/store/product/list")); // Load product details
              toastr.success(res.message);
              productModal.modal("hide"); // Reset form
              //   resetproductForm();
            } else if (res.status == "error") {
              toastr.error(res.message);
            } else {
              toastr.error("No response status!", "Error");
            }
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            toastr.error(`${textStatus} <br />${errorThrown}`, "Error");
          });
      }
    });
  });

  // Add store_category
  $(listContainer).on("click", "#btn-update-quantity", function (e) {
    e.preventDefault();
    let product_id = $(this).attr("data-product");
    // btnResetstore_categoryForm.show();    // Show reset button
    quantityModal.find(quantityForm).attr(
      "action",
      formApiUrl("admin/store/product/add/stock", {
        product_id: product_id,
      })
    );
    quantityModal.find(".modal-header .modal-title").html("Update Quantity");
    quantityModal.modal({
      backdrop: "static",
      keyboard: false,
      show: true,
    });
  });

  // customer Form validation
  quantityFormValidator = quantityForm.validate({
    onkeyup: function (element) {
      $(element).valid();
    },
    onclick: function (element) {
      $(element).valid();
    },
    rules: {
      quantity: {
        required: true,
      },
    },
    messages: {
      quantity: {
        required: "Specify Quantity",
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

  // Contract job Save function
  function saveQuantity() {
    let loadSwal;
    var formData = new FormData(quantityForm[0]);

    $.ajax({
      url: quantityForm.attr("action"),
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
        quantityForm
          .find('[type="submit"]')
          .attr("disabled", true)
          .html('<i class="fa fa-spinner fa-spin "></i> Loading');
      },
      complete: function () {
        loadSwal.close();
        quantityForm
          .find('[type="submit"]')
          .attr("disabled", false)
          .html("Submit");
      },
    })
      .done(function (res) {
        if (res.status == "success") {
          toastr.success(res.message);
          quantityForm[0].reset(); // Reset form
          setTimeout(function () {
            window.location.href = formUrl("admin/store/products");
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

  // product submit
  quantityForm.submit(function (e) {
    e.preventDefault();
    if (quantityFormValidator.valid()) {
      saveQuantity();
    }
  });
});

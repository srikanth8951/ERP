/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: Store Product js
 */

$(function () {
  var productFormValidator;

  // Get Measurement Units
  getMeasurementUnits().forEach(function (unit, ui) {
    $("#product-uom").append(new Option(unit.name, unit.code));
  });
  $("#product-uom").trigger("change");

  // store category Autocomplete
  window.loadAutocompleteCategory = function (options = {}) {
    return new Promise(function (resolve, reject) {
      var selected;
      var categorySelectbox = options.element;
      // alert("helolo");
      if (
        parseValue(options.params) != "" &&
        parseValue(options.params.selected) != ""
      ) {
        selected =
          Object.keys(options.params.selected).length > 0
            ? Object.values(options.params.selected)
            : [];
      } else {
        selected = [];
      }

      categorySelectbox.html("").trigger("change"); // Reset selectbox
      $.ajax({
        url: formApiUrl("admin/store/category/autocomplete"),
        type: "get",
        dataType: "json",
        headers: {
          Authorization: `Bearer ${wapLogin.getToken()}`,
        },
      })
        .done((res) => {
          console.log(res);
          categorySelectbox.append(new Option("Select", "", false, false)); // Load initial select
          if (res.status == "success") {
            if (res.store_categories) {
              var categories = res.store_categories;
              var categoryOption;

              $.each(categories, function (bi, category) {
                if (
                  selected.find((value) => {
                    return value == category.id;
                  })
                ) {
                  categoryOption = new Option(
                    category.name,
                    category.id,
                    true,
                    true
                  );
                } else {
                  categoryOption = new Option(
                    category.name,
                    category.id,
                    false,
                    false
                  );
                }

                categorySelectbox.append(categoryOption);
              });

              categorySelectbox.trigger("change");
            }
          } else if (res.status == "error") {
            console.log(res.message);
          } else {
            console.log("category Autocomlete: Something went wrong!");
          }
          resolve(res);
        })
        .fail((xhr, ajaxOptions, errorThrown) => {
          console.log(xhr.responseText + " " + xhr.responseText);
          reject(xhr);
        });
    });
  };

  // sub category Autocomplete
  window.loadAutocompleteSubCategory = function (options = {}) {
    return new Promise(function (resolve, reject) {
      var selected;
      var category_id;
      var sub_categorySelectbox = options.element;

      if (parseValue(options.params.category_id) != "") {
        category_id = options.params.category_id;
      } else {
        category_id = 0;
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

      sub_categorySelectbox.html("").trigger("change"); // Reset selectbox
      $.ajax({
        url: formApiUrl("admin/store/sub_category/autocomplete", optionParams),
        type: "get",
        dataType: "json",
        headers: {
          Authorization: `Bearer ${wapLogin.getToken()}`,
        },
      })
        .done((res) => {
          console.log(res);
          sub_categorySelectbox.append(new Option("Select", "", false, false)); // Load initial select
          if (res.status == "success") {
            if (res.store_sub_categories) {
              var store_sub_categories = res.store_sub_categories;
              var store_sub_categorieOption;

              $.each(store_sub_categories, function (bi, sub_category) {
                if (
                  selected.find((value) => {
                    return value == sub_category.id;
                  })
                ) {
                  store_sub_categorieOption = new Option(
                    sub_category.name,
                    sub_category.id,
                    true,
                    true
                  );
                } else {
                  store_sub_categorieOption = new Option(
                    sub_category.name,
                    sub_category.id,
                    false,
                    false
                  );
                }

                sub_categorySelectbox.append(store_sub_categorieOption);
              });
              sub_categorySelectbox.trigger("change");
            }
          } else if (res.status == "error") {
            console.log(res.message);
          } else {
            console.log("sub_category Autocomlete: Something went wrong!");
          }
          resolve(res);
        })
        .fail((xhr, ajaxOptions, errorThrown) => {
          console.log(xhr.responseText + " " + xhr.responseText);
          reject(xhr);
        });
    });
  };

  /* load sub category autocomplete after country select */
  productForm.find('[name="category_id"]').on("select2:select", function () {
    if (parseValue($(this).val()) != "") {
      loadAutocompleteSubCategory({
        element: productForm.find('[name="sub_category_id"]'),
        params: {
          parent: $(this).val(),
        },
      });
    } else {
      productForm.find('[name="sub_category_id"]').html("").trigger("change");
    }
  });

  // load contract job view based on detail
  async function loadProductView(details) {
    if (parseValue(details) != "" && Object.keys(details).length > 0) {
      let detail = details.product;
      productForm.find('[name="name"]').val(detail.name);
      productForm.find('[name="quantity"]').val(detail.quantity);
      productForm.find('[name="unit"]').val(detail.unit).trigger("change");
      productForm.find('[name="amount"]').val(detail.amount);
      productForm.find('[name="specification"]').val(detail.specification);

      await loadAutocompleteCategory({
        element: productForm.find('[name="category_id"]'),
        params: {
          selected: [detail.category_id],
        },
      });
      await loadAutocompleteSubCategory({
        element: productForm.find('[name="sub_category_id"]'),
        params: {
          parent: detail.category_id,
          selected: [detail.sub_category_id],
        },
      });
    } else {
      toastr.info("Error occured while loading customer detail!");
      productForm
        .find(
          '.customer-detail-area input[type="text"], .customer-detail-area textarea, .customer-detail-area select, .customer-detail-area button.password-generate'
        )
        .prop("disabled", false);
    }
  }

  // load contract job details
  window.loadProductDetail = function () {
    let loadSwal;

    $.ajax({
      url: formApiUrl("admin/store/product/detail", {
        product_id: product_id,
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
          if (parseValue(res.product) != "") {
            loadProductView(res);
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
  productFormValidator = productForm.validate({
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
      category_id: {
        required: true,
      },
      sub_category_id: {
        required: true,
      },
    },
    messages: {
      name: {
        required: "Specify product name",
      },
      category_id: {
        required: "Select category",
      },
      sub_category_id: {
        required: "Select sub category",
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
  function saveProduct() {
    let loadSwal;
    var formData = new FormData(productForm[0]);
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
        productForm
          .find('[type="submit"]')
          .attr("disabled", true)
          .html('<i class="fa fa-spinner fa-spin "></i> Loading');
      },
      complete: function () {
        loadSwal.close();
        productForm
          .find('[type="submit"]')
          .attr("disabled", false)
          .html("Submit");
      },
    })
      .done(function (res) {
        if (res.status == "success") {
          toastr.success(res.message);
          productForm[0].reset(); // Reset form
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
  productForm.submit(function (e) {
    e.preventDefault();
    if (productFormValidator.valid()) {
      saveProduct();
    }
  });
});

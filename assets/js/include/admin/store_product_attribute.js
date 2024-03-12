$(function () {
  const productForm = $("#productForm");
  const attributeForm = $("#attributeForm");
  var attributeFormValidator;
  const attributeModal = $("#attributeModal");
  const btnResetattributeForm = $("#btn-reset-attribute-form");
  let attributeCount = 1;
  $("#bt_attribute").click(function () {
    var html =
      '<div class="row pt-2 attribute_row">' +
      '<div class="col-5">' +
      '<select name="attribute[][id]" id="attributeSelect' +
      attributeCount +
      '" class="form-control select2 attribute-selectbox">' +
      "</select>" +
      "</div>" +
      '<div class="col">' +
      '<input class="form-control" type="text" name="attribute[][value]">' +
      "</div>" +
      '<button type="button" class="btn btn-danger" onclick="deleteRow(this)" >x</button>' +
      "</div>";
    $("#attribute_div").append(html);
    $(".select2").select2();
    loadAutocompleteAttribute({
      elementId: "#attributeSelect" + attributeCount,
    });

    attributeCount++;
  });

  // Add attribute
  $("#btn-add-attribute").click(function (e) {
    e.preventDefault();

    loadAutocompleteAttributeGroups(); // Load autocomplete for AttributeGroup
    btnResetattributeForm.show(); // Show reset button
    attributeForm.attr("action", formApiUrl("admin/store/attribute/add"));
    attributeModal.find(".modal-header .modal-title").html("Add attribute");
    attributeModal.modal({
      backdrop: "static",
      keyboard: false,
      show: true,
    });
  });

  window.resetattributeForm = function (resetAction = true) {
    if (resetAction == true) {
      attributeForm.attr("action", ""); // Form Attribute
    }

    attributeForm[0].reset(); // Form
    attributeForm
      .find('[data-toggle="select2"]')
      .prop("disabled", false)
      .val(null)
      .trigger("change"); // Select2
    attributeFormValidator.resetForm(); // Jquery validation
  };

  // Form reset button
  btnResetattributeForm.click(function (e) {
    e.preventDefault();
    resetattributeForm(false);
  });

  // Modal Form close
  attributeModal.find('[data-dismiss="modal"]').click(function () {
    resetattributeForm();
  });

  // Attribute Autocomplete
  window.loadAutocompleteAttribute = function (options = {}) {
    var selected;
    var attributeSelectbox = productForm.find(options.elementId);
    console.log(attributeSelectbox);
    if (parseValue(options.selected) != "") {
      selected =
        Object.keys(options.selected).length > 0
          ? Object.values(options.selected)
          : [];
    } else {
      selected = [];
    }

    attributeSelectbox.html("").trigger("change"); // Reset selectbox
    $.ajax({
      url: formApiUrl("admin/store/attribute/autocomplete", options),
      type: "get",
      dataType: "json",
      headers: {
        Authorization: `Bearer ${wapLogin.getToken()}`,
      },
    })
      .done((res) => {
        attributeSelectbox.append(new Option("Select", "", false, false)); // Load initial select
        if (res.status == "success") {
          if (res.attributes) {
            var attributes = res.attributes;
            var attributeOption;
            $.each(attributes, function (bi, attribute) {
              if (
                selected.find((value) => {
                  return value == attribute.id;
                })
              ) {
                attributeOption = new Option(
                  attribute.name,
                  attribute.id,
                  true,
                  true
                );
              } else {
                attributeOption = new Option(
                  attribute.name,
                  attribute.id,
                  false,
                  false
                );
              }

              attributeSelectbox.append(attributeOption);
            });
            attributeSelectbox.trigger("change");
          }
        } else if (res.status == "error") {
          console.log(res.message);
        } else {
          console.log("attribute Autocomlete: Something went wrong!");
        }
      })
      .fail((xhr, ajaxOptions, errorThrown) => {
        console.log(xhr.responseText + " " + xhr.responseText);
      });
  };

  // Attribute Group Autocomplete
  window.loadAutocompleteAttributeGroups = function (options = {}) {
    var selected;
    var attributeGroupSelectbox = attributeForm.find(
      '[name="attribute_group_id"]'
    );

    if (parseValue(options.selected) != "") {
      selected =
        Object.keys(options.selected).length > 0
          ? Object.values(options.selected)
          : [];
    } else {
      selected = [];
    }

    attributeGroupSelectbox.html("").trigger("change"); // Reset selectbox
    $.ajax({
      url: formApiUrl("admin/store/attribute_group/autocomplete", options),
      type: "get",
      dataType: "json",
      headers: {
        Authorization: `Bearer ${wapLogin.getToken()}`,
      },
    })
      .done((res) => {
        attributeGroupSelectbox.append(new Option("Select", "", false, false)); // Load initial select
        if (res.status == "success") {
          if (res.attribute_groups) {
            var attribute_groups = res.attribute_groups;
            var attribute_groupOption;

            $.each(attribute_groups, function (bi, attribute_group) {
              if (
                selected.find((value) => {
                  return value == attribute_group.id;
                })
              ) {
                attribute_groupOption = new Option(
                  attribute_group.name,
                  attribute_group.id,
                  true,
                  true
                );
              } else {
                attribute_groupOption = new Option(
                  attribute_group.name,
                  attribute_group.id,
                  false,
                  false
                );
              }

              attributeGroupSelectbox.append(attribute_groupOption);
            });
            attributeGroupSelectbox.trigger("change");
          }
        } else if (res.status == "error") {
          console.log(res.message);
        } else {
          console.log("attribute_group Autocomlete: Something went wrong!");
        }
      })
      .fail((xhr, ajaxOptions, errorThrown) => {
        console.log(xhr.responseText + " " + xhr.responseText);
      });
  };

  // attribute Form
  attributeFormValidator = attributeForm.validate({
    onkeyup: function (element) {
      $(element).valid();
    },
    onclick: function (element) {
      $(element).valid();
    },
    rules: {
      attribute_group_id: {
        required: true,
      },
      attribute_name: {
        required: true,
        // minlength: 3
      },
      status: {
        required: true,
      },
    },
    messages: {
      attribute_group_id: {
        required: "Select attribute_group",
      },
      attribute_name: {
        required: "Specify attribute name",
        // minlength: 'Specify atleast 3 characters'
      },
      status: {
        required: "Select status",
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

  function saveattribute() {
    let loadSwal;
    var formData = new FormData(attributeForm[0]);
    console.log();
    $.ajax({
      url: attributeForm.attr("action"),
      type: "post",
      data: formData,
      dataType: "json",
      headers: {
        Authorization: `Bearer ${wapLogin.getToken()}`,
      },
      processData: false,
      contentType: false,
      cache: false,
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
          // loadDetails(formApiUrl("admin/store/attribute/list")); // Load attribute details
          toastr.success(res.message);
          attributeModal.modal("hide"); // Hide modal
          resetattributeForm(); // Reset form
          productForm.find('.attribute-selectbox').each(function (sindex, select) {
            console.log($(select).val());
            loadAutocompleteAttribute({
              elementId: '#' + $(select).attr('id'),
              selected: [$(select).val()]
            });
          });;
          
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

  attributeForm.submit(function (e) {
    e.preventDefault();
    if (attributeFormValidator.valid()) {
      saveattribute();
    }
  });
});

function deleteRow(temp) {
  $(temp).parents(".attribute_row").remove();
}

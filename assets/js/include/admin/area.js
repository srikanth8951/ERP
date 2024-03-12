/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: area js
 */

var apparea = {};

$(function () {
  const listArea = $("#area--deatils--area");
  const lengthContainer = listArea.find('[data-jy-length="record"]');
  const searchContainer = listArea.find('[data-jy-search="record"]');
  const tableContainer = listArea.find('[data-container="areaListArea"]');

  const listContainer = tableContainer.find('[data-container="areaTlistArea"]');
  const listPagination = tableContainer.find(
    '[data-pagination="areaTlistArea"]'
  );
  const areaForm = $("#areaForm");
  var areaFormValidator;
  const areaModal = $("#areaModal");
  const btnResetareaForm = $("#btn-reset-area-form");
  listPagination.find(".list-pagination").html("");
  listPagination.find(".list-pagination-label").html("");

  // Add area
  $("#btn-add-area").click(function (e) {
    e.preventDefault();

    loadAutocompleteRegions(); // Load autocomplete
    btnResetareaForm.show(); // Show reset button
    areaForm.attr("action", formApiUrl("admin/area/add"));
    areaModal.find(".modal-header .modal-title").html("Add City");
    areaModal.modal({
      backdrop: "static",
      keyboard: false,
      show: true,
    });
  });

  window.resetareaForm = function (resetAction = true) {
    if (resetAction == true) {
      areaForm.attr("action", ""); // Form Attribute
    }

    areaForm[0].reset(); // Form
    areaForm
      .find('[data-toggle="select2"]')
      .prop("disabled", false)
      .val(null)
      .trigger("change"); // Select2
    areaFormValidator.resetForm(); // Jquery validation
  };

  // Form reset button
  btnResetareaForm.click(function (e) {
    e.preventDefault();
    resetareaForm(false);
  });

  // Modal Form close
  areaModal.find('[data-dismiss="modal"]').click(function () {
    resetareaForm();
  });

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
          if (res.areas) {
            listContainer.html("");
            var details = res.areas;
            var pagination = res.pagination;
            if (details.length && pagination.total > 0) {
              let status_badge_class = "";
              $.each(details, function (listIn, listVal) {
                if (listVal.status == 1) {
                  status_badge_class = "badge-success";
                } else {
                  status_badge_class = "badge-danger";
                }

                listContainer.append(
                  "<tr>" +
                    "<td>" +
                    (listIn + 1) +
                    "</td>" +
                    "<td>" +
                    listVal.name +
                    "</td>" +
                    "<td>" +
                    listVal.code +
                    "</td>" +
                    "<td>" +
                    listVal.region_name +
                    "</td>" +
                    "<td>" +
                    listVal.branch_name +
                    "</td>" +
                    "<td>" +
                    '<a href="javascript:void(0)" data-area="' +
                    listVal.area_id +
                    '" class="text-white btn btn-sm btn-dark waves-effect waves-light btn-edit-area mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit city"><i class="mdi mdi-pencil"></i></a>' +
                    '<a href="javascript:void(0)" data-area="' +
                    listVal.area_id +
                    '" class="text-white btn btn-sm btn-danger waves-effect waves-light btn-delete-area mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="mdi mdi-delete"></i></a>' +
                    "</td>" +
                    "</tr>"
                );
              });

              listContainer.find('[data-toggle="tooltip"]').tooltip(); // Load tooltip
              listPagination
                .find(".list-pagination-label")
                .html(
                  `Showing ${pagination.start} to ${(parseInt(pagination.start) -1) + pagination.records} of ${pagination.total}`
                );
              listPagination.find(".list-pagination").pagination({
                items: parseInt(pagination.total),
                itemsOnPage: parseInt(pagination.length),
                currentPage: Math.ceil(parseInt(pagination.start) / parseInt(pagination.length)),
                displayedPages: 3,
                navStyle: "pagination",
                listStyle: "page-item",
                linkStyle: "page-link",
                onPageClick: function (pageNumber, event) {
                  var page_link = formApiUrl("admin/area/list", {
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
    loadDetails(formApiUrl("admin/area/list")); // Load area details
  });

  searchContainer.submit(function (e) {
    e.preventDefault();
    loadDetails(formApiUrl("admin/area/list")); // Load area details
  });

  // region Autocomplete
  window.loadAutocompleteRegions = function (options = {}) {
    var selected;
    var regionSelectbox = areaForm.find('[name="region_id"]');

    if (parseValue(options.selected) != "") {
      selected =
        Object.keys(options.selected).length > 0
          ? Object.values(options.selected)
          : [];
    } else {
      selected = [];
    }

    regionSelectbox.html("").trigger("change"); // Reset selectbox
    $.ajax({
      url: formApiUrl("admin/region/autocomplete", options),
      type: "get",
      dataType: "json",
      headers: {
        Authorization: `Bearer ${wapLogin.getToken()}`,
      },
    })
      .done((res) => {
        regionSelectbox.append(new Option("Select", "", false, false)); // Load initial select
        if (res.status == "success") {
          if (res.regions) {
            var regions = res.regions;
            var regionOption;

            $.each(regions, function (bi, region) {
              if (
                selected.find((value) => {
                  return value == region.id;
                })
              ) {
                regionOption = new Option(region.name, region.id, true, true);
              } else {
                regionOption = new Option(region.name, region.id, false, false);
              }

              regionSelectbox.append(regionOption);
            });
            regionSelectbox.trigger("change");
          }
        } else if (res.status == "error") {
          console.log(res.message);
        } else {
          console.log("region Autocomlete: Something went wrong!");
        }
      })
      .fail((xhr, ajaxOptions, errorThrown) => {
        console.log(xhr.responseText + " " + xhr.responseText);
      });
  };

  // branch Autocomplete
  window.loadAutocompleteBranches = function (options = {}) {
    var selected;
    var region_id;
    var params = {};
    var branchSelectbox = areaForm.find('[name="branch_id"]');

    if (parseValue(options.region_id) != "") {
      region_id = options.region_id;
      params.region_id = options.region_id;
    } else {
      region_id = 0;
      params.region_id = 0;
    }

    if (parseValue(options.selected) != "") {
      selected =
        Object.keys(options.selected).length > 0
          ? Object.values(options.selected)
          : [];
    } else {
      selected = [];
    }

    branchSelectbox.html("").trigger("change"); // Reset selectbox
    $.ajax({
      url: formApiUrl("admin/branch/autocomplete", params),
      type: "get",
      dataType: "json",
      headers: {
        Authorization: `Bearer ${wapLogin.getToken()}`,
      },
    })
      .done((res) => {
        branchSelectbox.append(new Option("Select", "", false, false)); // Load initial select
        if (res.status == "success") {
          if (res.branches) {
            var branches = res.branches;
            var branchOption;

            $.each(branches, function (bi, branch) {
              if (
                selected.find((value) => {
                  return value == branch.id;
                })
              ) {
                branchOption = new Option(branch.name, branch.id, true, true);
              } else {
                branchOption = new Option(branch.name, branch.id, false, false);
              }

              branchSelectbox.append(branchOption);
            });
            branchSelectbox.trigger("change");
          }
        } else if (res.status == "error") {
          console.log(res.message);
        } else {
          console.log("branch Autocomlete: Something went wrong!");
        }
      })
      .fail((xhr, ajaxOptions, errorThrown) => {
        console.log(xhr.responseText + " " + xhr.responseText);
      });
  };

  // passing region ID to Branch
  areaForm.find('[name="region_id"]').on("select2:select", function () {
    if (parseValue($(this).val()) != "") {
      loadAutocompleteBranches({
        region_id: $(this).val(),
      });
    } else {
      areaForm.find('[name="branch_id"]').html("").trigger("change");
    }
  });

  // area Form
  areaFormValidator = areaForm.validate({
    onkeyup: function (element) {
      $(element).valid();
    },
    onclick: function (element) {
      $(element).valid();
    },
    rules: {
      region_id: {
        required: true,
      },
      branch_id: {
        required: true,
      },
      area_name: {
        required: true,
        // minlength: 3,
      },
      area_code: {
        required: true,
        // minlength: 3,
      },
    },
    messages: {
      region_id: {
        required: "Select region",
      },
      branch_id: {
        required: "Select branch",
      },
      area_name: {
        required: "Specify city name",
        // minlength: "Specify atleast 3 characters",
      },
      area_code: {
        required: "Specify city code",
        // minlength: "Specify atleast 3 characters",
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

  function savearea() {
    let loadSwal;
    var formData = new FormData(areaForm[0]);

    $.ajax({
      url: areaForm.attr("action"),
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
          loadDetails(formApiUrl("admin/area/list")); // Load area details
          toastr.success(res.message);
          areaModal.modal("hide"); // Hide modal
          resetareaForm(); // Reset form
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

  areaForm.submit(function (e) {
    e.preventDefault();
    if (areaFormValidator.valid()) {
      savearea();
    }
  });

  // Edit area
  $(listContainer).on("click", ".btn-edit-area", function (e) {
    e.preventDefault();

    btnResetareaForm.hide(); // Hide button

    var area_id = $(this).attr("data-area");
    $.ajax({
      url: formApiUrl("admin/area/detail", { area_id: area_id }),
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
    })
      .done(function (res) {
        if (res.status == "success") {
          if (res.area) {
            var area = res.area;

            // Set area Info
            areaForm.attr(
              "action",
              formApiUrl("admin/area/edit", { area_id: area.area_id })
            );
            areaForm.find('[name="area_name"]').val(area.name);
            areaForm.find('[name="area_code"]').val(area.code);
            // Regions selectbox with selected value
            loadAutocompleteRegions({
              selected: [area.region_id],
            });
            loadAutocompleteBranches({
              region_id: area.region_id,
              selected: [area.branch_id],
            });
            areaModal.find(".modal-header .modal-title").html("Edit city");

            // Show area modal
            areaModal.modal({
              backdrop: "static",
              keyboard: false,
              show: true,
            });
          } else {
            toastr.error("No area available");
          }
        } else if (res.status == "error") {
          toastr.error(res.message);
        } else {
          toastr.error("No response status!", "Error");
        }
      })
      .fail(function (jqXHR, textStatus, errorThrown) {
        toastr.error(`${textStatus} <br />${errorThrown}`, "Error");
      });
  });

  // Delete area
  $(listContainer).on("click", ".btn-delete-area", function (e) {
    e.preventDefault();
    var area_id = $(this).attr("data-area");
    Swal.fire({
      icon: "question",
      title: "Are you sure to delete city",
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
          url: formApiUrl("admin/area/delete", { area_id: area_id }),
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
              loadDetails(formApiUrl("admin/area/list")); // Load area details
              toastr.success(res.message);
              areaModal.modal("hide"); // Reset form
              resetareaForm();
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

  $("#btn-download-upload-sample").click(function (e) {
    e.preventDefault();
    let loadSwal;
    const invForm = $(this);

    $.ajax({
      url: formApiUrl("admin/area/downloadSample"),
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
    })
      .then(function (res) {
        if (res.status == "success") {
          if (res.content) {
            let fileName = "city-" + moment().format("DD/MM/YYYY") + ".xlsx";
            var anchorElement = $("<a></a>");
            anchorElement.attr("href", res.content);
            anchorElement.attr("download", fileName);
            anchorElement.css("display", "none");
            anchorElement.html("Download");
            anchorElement.appendTo("body");
            anchorElement[0].click();

            setTimeout(function () {
              anchorElement.remove();
            }, 1000);
          }
        } else {
          let res_message = "";
          if (typeof res.message != "undefined") {
            res_message = res.message;
          } else {
            res_message = "Something went wrong!";
          }

          toastr.error(res_message);
        }
      })
      .catch(function (error) {
        toastr.error("Something went wrong! Contact support");
      });
  });
});

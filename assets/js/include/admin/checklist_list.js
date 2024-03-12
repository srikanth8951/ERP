/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: checklist js
 */

var appchecklist = {};
const defaultOptions = {};
if (window.checklist_group != '') {
  defaultOptions.checklist_group = window.checklist_group;
}

$(function () {
  const listArea = $("#checklist--deatils--area");
  const listContainer = listArea.find('[data-container="checklistArea"]');
  const listPagination = listArea.find('[data-pagination="checklistArea"]');
  const checklistForm = $("#checklistForm");
  const checklistModal = $("#checklistModal");
  const btnResetchecklistForm = $("#btn-reset-checklist-form");
  listPagination.html("");

  // Add checklist
  $("#btn-add-checklist").click(function (e) {
    e.preventDefault();

    btnResetchecklistForm.show(); // Show reset button
    checklistForm.attr("action", formApiUrl("admin/checklist/add"));
    checklistModal.find(".modal-header .modal-title").html("Add checklist");
    checklistModal.modal({
      backdrop: "static",
      keyboard: false,
      show: true,
    });
  });

  window.resetchecklistForm = function (resetAction = true) {
    if (resetAction == true) {
      checklistForm.attr("action", ""); // Form Attribute
    }

    checklistForm[0].reset(); // Form
    checklistForm
      .find('[data-toggle="select2"]')
      .prop("disabled", false)
      .val(null)
      .trigger("change"); // Select2
    checklistFormValidator.resetForm(); // Jquery validation
  };

  // Form reset button
  btnResetchecklistForm.click(function (e) {
    e.preventDefault();
    resetchecklistForm(false);
  });

  // Modal Form close
  checklistModal.find('[data-dismiss="modal"]').click(function () {
    resetchecklistForm();
  });

  window.loadEmptyDetail = function () {
    listContainer.html("");
    listPagination.html("");
    listContainer.append(
      "<tr>" +
        '<td colspan="6" class="text-center">No Details Found!</td>' +
        "</tr>"
    );
  };

  window.loadChecklistDetails = function (options={}) {
    var optionsz = Object.assign({}, defaultOptions, options);
    listContainer.find("tr").attr("data-jy-loader", "timeline");

    $.ajax({
      url: formApiUrl('admin/checklist/list', optionsz),
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
          if (res.checklists) {
            listContainer.html("");
            var details = res.checklists;
            var pagination = res.pagination;
            if (details.length && pagination.total > 0) {
              let status_badge_class = "";
              $.each(details, function (listIn, listVal) {
                console.log(listVal);
                if (listVal.status == 1) {
                  status_badge_class = "badge-success";
                } else {
                  status_badge_class = "badge-danger";
                }

                var checklistViewLink;
                if (parseValue(window.checklist_group) != '') {
                  checklistViewLink = formUrl(`admin/catalog/checklist/${window.checklist_group}/view/${listVal.checklist_id}`);
                } else {
                  checklistViewLink = formUrl(`admin/catalog/checklist/view/${listVal.checklist_id}`);
                }
                
                listContainer.append(
                  "<tr>" +
                    "<td>" +
                    listVal.name +
                    "</td>" +
                    '<td><span class="badge ' +
                    status_badge_class +
                    '">' +
                    getStatusText(listVal.status) +
                    "</span></td>" +
                    "<td>" +
                    listVal.type.name +
                    "</td>" +
                    "<td>" +
                    moment(listVal.created_datetime).format("DD/MM/YYYY") +
                    "</td>" +
                    "<td>" +
                    '<a href="' +
                    checklistViewLink +
                    '" class="text-white btn btn-sm btn-teal waves-effect waves-light btn-view-checklist mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="View checklist"><i class="mdi mdi-eye"></i></a>' +
                    '<a href="javascript:void(0)" data-checklist="' +
                    listVal.checklist_id +
                    '" class="text-white btn btn-sm btn-dark waves-effect waves-light btn-edit-checklist mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit checklist"><i class="mdi mdi-pencil"></i></a>' +
                    '<a href="javascript:void(0)" data-checklist="' +
                    listVal.checklist_id +
                    '" class="text-white btn btn-sm btn-danger waves-effect waves-light btn-delete-checklist mr-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="mdi mdi-delete"></i></a>' +
                    "</td>" +
                    "</tr>"
                );
              });

              listContainer.find('[data-toggle="tooltip"]').tooltip(); // Load tooltip
              listContainer
                .find('[data-toggle="tooltip"]')
                .on("click", function () {
                  listContainer.find('[data-toggle="tooltip"]').tooltip("hide"); // Load tooltip
                });

              listPagination.pagination({
                items: parseInt(pagination.total),
                itemsOnPage: parseInt(pagination.limit),
                currentPage: parseInt(pagination.page),
                displayedPages: 3,
                navStyle: "pagination",
                listStyle: "page-item",
                linkStyle: "page-link",
                onPageClick: function (pageNumber, event) {
                  loadChecklistDetails({ page: pageNumber });
                },
              });
            } else {
              loadEmptyDetail();
            }
          } else {
            loadEmptyDetail();
          }
        } else if (res.status == "error") {
          loadEmptyDetail();
          toastr.error(res.message);
        } else {
          loadEmptyDetail();
          toastr.error("No response status");
        }
      })
      .catch(function (jqXHR) {
        loadEmptyDetail();
        toastr.error(jqXHR.statusText);
      });
  };

  // checklist Form
  checklistFormValidator = checklistForm.validate({
    onkeyup: function (element) {
      $(element).valid();
    },
    onclick: function (element) {
      $(element).valid();
    },
    rules: {
      checklist_name: {
        required: true,
        minlength: 3,
      },
      checklist_status: {
        required: true,
      },
    },
    messages: {
      checklist_name: {
        required: "Specify checklist name",
        minlength: "Specify atleast 3 characters",
      },
      checklist_status: {
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

  function savechecklist() {
    let loadSwal;
    var formData = new FormData(checklistForm[0]);
    formData.append('checklist_group', checklist_group);

    $.ajax({
      url: checklistForm.attr("action"),
      type: "post",
      data: formData,
      processData: false,
      contentType: false,
      cache: false,
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
          resetchecklistForm(); // Reset form
          loadChecklistDetails(); // Load checklist details
          checklistModal.modal("hide"); // Hide modal

          toastr.success(res.message);
        } else if (res.status == "error") {
          toastr.error(res.message);
        } else {
          toastr.error("No response status");
        }
      })
      .catch(function (jqXHR, textStatus) {
        toastr.error(jqXHR.statusText);
      });
  }

  checklistForm.submit(function (e) {
    e.preventDefault();
    if (checklistFormValidator.valid()) {
      savechecklist();
    }
  });

  // Edit checklist
  $(listContainer).on("click", ".btn-edit-checklist", function (e) {
    e.preventDefault();

    btnResetchecklistForm.hide(); // Hide button

    var checklist_id = $(this).attr("data-checklist");

    $.ajax({
      url: formApiUrl("admin/checklist/detail", { checklist_id: checklist_id }),
      type: "get",
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
          if (res.checklist) {
            var checklist = res.checklist;

            // Set checklist Info
            checklistForm.attr(
              "action",
              formApiUrl("admin/checklist/edit", {
                checklist_id: checklist.checklist_id,
              })
            );
            checklistForm.find('[name="checklist_name"]').val(checklist.name);
            checklistForm
              .find('[name="checklist_description"]')
              .val(checklist.description);
            checklistForm.find('[name="checklist_type"]').removeAttr("checked");

            if (parseValue(checklist.type.id) != "") {
              checklistForm
                .find(
                  '[name="checklist_type"][value="' + checklist.type.id + '"]'
                )
                .attr("checked", true);
            }
            checklistForm
              .find('[name="checklist_status"]')
              .val(checklist.status)
              .trigger("change");
            checklistModal
              .find(".modal-header .modal-title")
              .html("Edit checklist");

            // Show checklist modal
            checklistModal.modal({
              backdrop: "static",
              keyboard: false,
              show: true,
            });
          } else {
            toastr.error("No checklist available");
          }
        } else if (res.status == "error") {
          toastr.error(res.message);
        } else {
          toastr.error(res.message);
        }
      })
      .catch(function (jqXHR, textStatus) {
        toastr.error(jqXHR.statusText);
      });
  });

  // Delete checklist
  $(listContainer).on("click", ".btn-delete-checklist", function (e) {
    e.preventDefault();
    let loadSwal;
    var checklist_id = $(this).attr("data-checklist");
    Swal.fire({
      icon: "question",
      title: "Are you sure to delete checklist",
      showConfirmButton: true,
      confirmButtonText: "Yes",
      showCancelButton: true,
      cancelButtonText: "No",
      focusCancel: true,
      timer: false,
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: formApiUrl("admin/checklist/delete", {
            checklist_id: checklist_id,
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
          .then(function (res) {
            if (res.status == "success") {
              loadChecklistDetails(); // Load checklist details
              checklistModal.modal("hide"); // Reset form
              resetchecklistForm();

              toastr.success(res.message);
            } else if (res.status == "error") {
              toastr.error(res.message);
            } else {
              toastr.error("No response status");
            }
          })
          .catch(function (jqXHR, textStatus) {
            toastr.error(jqXHR.statusText);
          });
      }
    });
  });
});

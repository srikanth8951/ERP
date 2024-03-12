/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: checklisttask js
 */

class ChecklistViewType1 {
  loadTaskAreaView() {
    let mviewContent = `<div class="collapse card mb-4" id="taskAddFormCollapse">
            <div class="card-body">
                <div class="clearfix">
                    <form id="checklistTaskForm" action="${formApiUrl(
                      "admin/checklist/task/add",
                      { checklist_id: checklist_id }
                    )}" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Criteria<span class="text-danger">*</span></label>
                                    <input type="text" name="task_name" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Type</label>
                                    <div class="">
                                        <div class="form-check-inline">
                                            <div class="custom-control custom-radio mr-2"><input id="taskcheck1" value="1" class="custom-control-input mx-2" type="radio" name="task_type" checked=""><label for="taskcheck1" class="custom-control-label">Checkbox</label></div>
                                            <div class="custom-control custom-radio mr-2"><input id="taskcheck2" value="2" class="custom-control-input mx-2" type="radio" name="task_type"><label for="taskcheck2" class="custom-control-label">Textbox</label></div>
                                            <div class="custom-control custom-radio mr-2"><input id="taskcheck3" value="3" class="custom-control-input mx-2" type="radio" name="task_type"><label for="taskcheck3" class="custom-control-label">None</label></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="float-right my-4">
                                <button type="submit" title="Add Task" class="btn btn-indigo btn-sm waves-effect waves-light">
                                    <i class="mdi mdi-plus"></i>&nbsp;Add</button>
                                    <button type="button" data-btn-close="collapse" data-target-btn-open="#taskHeaderAddBtn" data-toggle="collapse" data-target="#taskAddFormCollapse" class="ml-1 btn btn-sm btn-secondary waves-effect waves-light"><i class="mdi mdi-close"></i>&nbsp;Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="checklist-container" data-container="checklistTaskArea">
            <div class="row checklist-row">
                <div class="col-md-12 checklist-col">
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="media">
                                <div>
                                    <div class="ph-item py-1">
                                        <div class="ph-col-1 empty mt-0 ">
                                            <div class="ph-avatar mt-0" style="min-width: 16px;min-height: 16px;width: 16px;margin: auto;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="media-body">
                                    <div class="ph-item py-1">
                                        <div class="ph-col-12 px-0 mb-0">
                                            <div class="ph-row d-flex align-items-center">
                                                <div class="ph-col-4"></div>
                                                <div class="ph-col-8 empty"></div>
                                                <div class="ph-col-2"></div>
                                                <div class="ph-col-10 empty"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
    $('[data-mview="checklistType"]').html(mviewContent);

    this.loadMethods();
  }

  loadMethods() {
    const listArea = $("#checklisttask--deatils--area");
    const listContainer = listArea.find('[data-container="checklistTaskArea"]');
    const listPagination = listArea.find(
      '[data-pagination="checklistTaskArea"]'
    );
    const checklistTaskForm = $("#checklistTaskForm");
    const checklistTaskModal = $("#checklistTaskModal");
    const btnResetChecklistTaskForm = $("#btn-reset-checklisttask-form");
    let checklistTaskFormValidator;
    listPagination.html("");

    // Collapse btn click action
    $('[data-btn-open="collapse"]').click((e) => {
      $(e.currentTarget).fadeOut();
    });

    $('[data-btn-close="collapse"]').click((e) => {
      let btnTarget = $(e.currentTarget).attr("data-target-btn-open");

      $(btnTarget).fadeIn("slow");
    });

    // Add checklisttask
    $("#btn-add-checklist-task").click(function (e) {
      e.preventDefault();

      btnResetChecklistTaskForm.show(); // Show reset button
      checklistTaskForm.attr(
        "action",
        formApiUrl("admin/checklist/task/add", { checklist_id: checklist_id })
      );
      checklistTaskModal.find(".modal-header .modal-title").html("Add Task");
      checklistTaskModal.modal({
        backdrop: "static",
        keyboard: false,
        show: true,
      });
    });

    window.resetchecklistTaskForm = function (resetAction = true) {
      if (resetAction == true) {
        checklistTaskForm.attr("action", ""); // Form Attribute
      }

      checklistTaskForm[0].reset(); // Form
      checklistTaskForm
        .find('[data-toggle="select2"]')
        .prop("disabled", false)
        .val(null)
        .trigger("change"); // Select2
      checklistTaskFormValidator.resetForm(); // Jquery validation
    };

    // Form reset button
    btnResetChecklistTaskForm.click(function (e) {
      e.preventDefault();
      resetchecklistTaskForm(false);
    });

    // Modal Form close
    checklistTaskModal.find('[data-dismiss="modal"]').click(function () {
      resetchecklistTaskForm(false);
    });

    window.loadEmptyTaskDetail = function () {
      listContainer.html("");
      listPagination.html("");
      listContainer.append(`<div class="4 checklist-col">
                <div class="card"> 
                    <div class="card-body"><h6 class="text-center lead my-0">No Details Found!</div></div>
                </div>
                </div>`);
    };

    window.loadTaskDetails = function (href) {
      listContainer
        .find(".checklist-col .card-body")
        .attr("data-jy-loader", "timeline");
      let loadSwal;

      $.ajax({
        url: href,
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
            if (res.checklist_tasks) {
              listContainer.find(".checklist-row").html("");
              var details = res.checklist_tasks;
              var pagination = res.pagination;
              if (details.length && pagination.total > 0) {
                let status_badge_class = "";
                $.each(details, function (listIn, listVal) {
                  if (listVal.status == 1) {
                    status_badge_class = "badge-success";
                  } else {
                    status_badge_class = "badge-danger";
                  }

                  var checklisttaskViewLink =
                    base_url +
                    "checklisttasks/view/" +
                    listVal.checklisttask_id;
                  listContainer.find(
                    ".checklist-row"
                  ).append(`<div class="col-md-12 checklist-col">
                                <div class="card mb-2">
                                    <div class="card-body position-relative py-2">
                                        <div class="media mr-4">
                                            <span class="mr-4"><i class="mdi mdi-checkbox-marked-outline"></i></span>
                                            <div class="media-body">
                                                <p class="mb-0">${
                                                  listVal.name
                                                }</p>
                                            </div>
                                            <div><span class="small mr-4">${parseValue(
                                              listVal.type.name
                                            )}</span><a href="javascript:void(0)" data-checklisttask="${listVal.checklist_task_id}" class="mr-1 btn btn-xs btn-outline-danger btn-delete-checklist-task"><i class="mdi mdi-delete"></i></a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>`);
                });

                listContainer.find('[data-toggle="tooltip"]').tooltip(); // Load tooltip
                listContainer
                  .find('[data-toggle="tooltip"]')
                  .on("click", function () {
                    listContainer
                      .find('[data-toggle="tooltip"]')
                      .tooltip("hide"); // Load tooltip
                  });

                // listPagination.pagination({
                //     items: parseInt(pagination.total),
                //     itemsOnPage: parseInt(pagination.limit),
                //     currentPage: parseInt(pagination.page),
                //     displayedPages: 3,
                //     navStyle: 'pagination',
                //     listStyle: 'page-item',
                //     linkStyle: 'page-link',
                //     onPageClick: function (pageNumber, event) {
                //         var page_link = formApiUrl('admin/checklist/task/list', { checklist_id: checklist_id, page: pageNumber });
                //         loadTaskDetails(page_link);
                //     }
                // });
              } else {
                loadEmptyTaskDetail();
              }
            } else {
              loadEmptyTaskDetail();
            }
          } else if (res.status == "error") {
            loadEmptyTaskDetail();
            toastr.error(res.message);
          } else {
            loadEmptyTaskDetail();
            toastr.error("No response status");
          }
        })
        .catch(function (xhr) {
          loadEmptyTaskDetail();
          toastr.error(jqXHR.statusText);
        });
    };

    // checklisttask Form
    checklistTaskFormValidator = checklistTaskForm.validate({
      onkeyup: function (element) {
        $(element).valid();
      },
      onclick: function (element) {
        $(element).valid();
      },
      rules: {
        checklisttask_name: {
          required: true,
          minlength: 3,
        },
        checklisttask_status: {
          required: true,
        },
      },
      messages: {
        checklisttask_name: {
          required: "Specify checklisttask name",
          minlength: "Specify atleast 3 characters",
        },
        checklisttask_status: {
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

    function savechecklisttask() {
      let loadSwal;
      var formData = new FormData(checklistTaskForm[0]);

      $.ajax({
        url: checklistTaskForm.attr("action"),
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
            resetchecklistTaskForm(false); // Reset form
            loadTaskDetails(
              formApiUrl("admin/checklist/task/list", {
                checklist_id: checklist_id,
              })
            ); // Load checklisttask details
            //checklistTaskModal.modal('hide');    // Hide modal
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

    checklistTaskForm.submit(function (e) {
      e.preventDefault();
      if (checklistTaskFormValidator.valid()) {
        savechecklisttask();
      }
    });

    // Edit checklisttask
    $(listContainer).on("click", ".btn-edit-checklisttask", function (e) {
      e.preventDefault();

      btnResetChecklistTaskForm.hide(); // Hide button

      var checklisttask_id = $(this).attr("data-checklisttask");

      $.ajax({
        url: formApiUrl("admin/checklist/task/detail", {
          checklist_id: checklist_id,
          checklist_task_id: checklisttask_id,
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
            if (res.checklisttask) {
              var checklisttask = res.checklisttask;

              // Set checklisttask Info
              checklistTaskForm.attr(
                "action",
                formApiUrl("admin/checklist/task/edit", {
                  checklist_id: checklist_id,
                  checklist_task_id: checklisttask.checklisttask_id,
                })
              );
              checklistTaskForm
                .find('[name="task_name"]')
                .val(checklisttask.name);
              checklistTaskForm
                .find('[name="task_type"]')
                .removeAttr("checked");

              checklistTaskForm
                .find(
                  '[name="task_type"][value="' + checklisttask.type.id + '"]'
                )
                .attr("checked", true);
              checklistTaskModal
                .find(".modal-header .modal-title")
                .html("Edit Task");

              // Show checklisttask modal
              checklistTaskModal.modal({
                backdrop: "static",
                keyboard: false,
                show: true,
              });
            } else {
              toastr.error("No checklisttask available");
            }
          } else if (res.status == "error") {
            toastr.error(res.message);
          } else {
            toastr.error("No response status");
          }
        })
        .catch(function (jqXHR, textStatus) {
          toastr.error(jqXHR.statusText);
        });
      // console.log(link);
      // savechecklisttask(formApiUrl('admin/checklist/task/editchecklisttask', {checklisttask_id:}));
    });

    // Delete checklisttask
    $(listContainer).on("click", ".btn-delete-checklist-task", function (e) {
        e.preventDefault();
        let loadSwal;
      var checklisttask_id = $(this).attr("data-checklisttask");
      Swal.fire({
        icon: "question",
        title: "Are you sure to delete checklist task",
        showConfirmButton: true,
        confirmButtonText: "Yes",
        showCancelButton: true,
        cancelButtonText: "No",
        focusCancel: true,
        timer: false,
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: formApiUrl("admin/checklist/task/delete", {
              checklist_id: checklist_id,
              checklist_task_id: checklisttask_id,
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
                loadTaskDetails(
                  formApiUrl("admin/checklist/task/list", {
                    checklist_id: checklist_id,
                  })
                ); // Load checklisttask details
                checklistTaskModal.modal("hide"); // Reset form
                resetchecklistTaskForm(false);

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
  }
}

/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: checklisttask js
 */

class ChecklistViewType2 {
  loadDivisionAreaView() {
    let mviewContent = `<div class="collapse card mb-4" id="divisionAddFormCollapse">
            <div class="card-body">
                <form id="checklistDivisionForm" action="${formApiUrl(
                  "admin/checklist/division/add",
                  { checklist_id: checklist_id }
                )}" method="post">
                    <div class="row">
                        <div class="col-md-10 mb-2 mb-md-0">
                            <input type="text" name="division_name" class="form-control" placeholder="Division Name" />
                        </div>
                        <div class="col-md-2 mb-2 mb-md-0">
                            <button type="submit" class="btn btn-indigo btn-sm waves-effect waves-light"><i class="mdi mdi-plus"></i>&nbsp;Add</button>
                            <button type="button" data-btn-close="collapse" data-target-btn-open="#divisionHeaderAddBtn" data-toggle="collapse" data-target="#divisionAddFormCollapse" class="ml-1 btn btn-sm btn-secondary waves-effect waves-light"><i class="mdi mdi-close"></i>&nbsp;Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="checklist-container" data-container="checklistTaskArea">
            <div class="row checklist-row">
                <div class="col-12 checklist-col">
                    <div class="card">
                        <div class="card-header">
                            <div class="ph-item py-1">
                                <div class="ph-col-12 px-0 mb-0">
                                    <div class="ph-row d-flex align-items-center">
                                        <div class="ph-col-4"></div>
                                        <div class="ph-col-8 empty"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    const checklistDivisionModal = $("#checklistDivisionModal");
    const checklistDivisionForm = $("#checklistDivisionForm");
    let checklistDivisionFormValidator;
    // const btnResetchecklistDivisionForm = $('#btn-reset-checklisttask-form');
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
    // $('#btn-add-checklist-division').click(function (e) {
    //     e.preventDefault();

    //     checklistDivisionForm.attr('action', formApiUrl('admin/checklist/division/add', { checklist_id: checklist_id }));
    //     checklistDivisionModal.find('.modal-header .modal-title').html('Add Division');
    //     checklistDivisionModal.modal({
    //         backdrop: 'static',
    //         keyboard: false,
    //         show: true
    //     });
    // });

    // checklisttask Form
    checklistDivisionFormValidator = checklistDivisionForm.validate({
      onkeyup: function (element) {
        $(element).valid();
      },
      onclick: function (element) {
        $(element).valid();
      },
      rules: {
        division_name: {
          required: true,
          minlength: 3,
        },
      },
      messages: {
        division_name: {
          required: "Specify division name",
          minlength: "Specify atleast 3 characters",
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

    window.resetchecklistDivisionForm = function (resetAction = true) {
      // if (resetAction == true) {
      //     checklistDivisionForm.attr('action', '');    // Form Attribute
      // }

      checklistDivisionForm[0].reset(); // Form
      checklistDivisionFormValidator.resetForm(); // Jquery validation
    };

    function saveChecklistTask() {
      let loadSwal;
      var formData = new FormData(checklistDivisionForm[0]);

      $.ajax({
        url: checklistDivisionForm.attr("action"),
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
            resetchecklistDivisionForm(); // Reset form
            loadDivisionDetails(
              formApiUrl("admin/checklist/division/list", {
                checklist_id: checklist_id,
              })
            ); // Load checklisttask details

            toastr.success(res.message);
          } else if (res.status == "error") {
            toastr.error(res.message);
          } else {
            toastr.error(res.message);
          }
        })
        .catch(function (jqXHR, textStatus) {
          toastr.error(jqXHR.statusText);
        });
    }

    checklistDivisionForm.submit(function (e) {
      e.preventDefault();
      if (checklistDivisionFormValidator.valid()) {
        saveChecklistTask();
      }
    });

    // Form reset button
    // btnResetChecklistDivisionForm.click(function (e) {
    //     e.preventDefault();
    //     resetchecklistDivisionForm(false);
    // });

    // Modal Form close
    // checklistDivisionModal.find('[data-dismiss="modal"]').click(function () {
    //     resetchecklistDivisionForm();
    // });

    window.loadEmptyDivisionDetail = function () {
      listContainer.find(".checklist-row").html("");
      listPagination.html("");
      listContainer.find(".checklist-row")
        .append(`<div class="col-12 checklist-col">
                <div class="card"> 
                    <div class="card-body"><h6 class="text-center lead my-0">No Details Found!</div></div>
                </div>
                </div>`);
    };

    window.loadDivisionDetails = (href) => {
      listContainer
        .find(".checklist-col .card")
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
        .then((res) => {
          if (res.status == "success") {
            if (res.checklist_divisions) {
              listContainer.find(".checklist-row").html("");
              var details = res.checklist_divisions;
              var pagination = res.pagination;
              if (details.length && pagination.total > 0) {
                let status_badge_class = "";
                details.forEach((listVal, listIn) => {
                  if (listVal.status == 1) {
                    status_badge_class = "badge-success";
                  } else {
                    status_badge_class = "badge-danger";
                  }

                  listContainer.find(".checklist-row")
                    .append(`<div class="col-12 checklist-col mb-3">
                                    <div class="card">
                                        <div class="card-header position-relative">
                                            <div class="btn-actions-boxz float-right">
                                                <button data-toggle="collapse" data-btn-open="collapse"
                                                id="btnOpenDiv${
                                                  listVal.checklist_division_id
                                                }TaskCollapse" 
                                                data-target="#division${
                                                  listVal.checklist_division_id
                                                }TaskFormCollapse" 
                                                class="ml-1 btn btn-sm btn-outline-purple waves-effect"><i class="mdi mdi-plus"></i>&nbsp;Task</button>
                                                <!-- <button data-checklist-division="${
                                                  listVal.checklist_division_id
                                                }" class="ml-1 btn-edit-checklist-division btn btn-sm btn-outline-purple waves-effect"><i class="mdi mdi-pencil"></i></button> -->
                                                <button data-checklist-division="${
                                                  listVal.checklist_division_id
                                                }" class="ml-1 btn-delete-checklist-division btn btn-sm btn-danger waves-effect"><i class="mdi mdi-close"></i></button>
                                            </div>
                                            <h6 class="my-0">${
                                              listVal.name
                                            }</h6>
                                        </div>
                                        <div class="card-body" id="division${
                                          listVal.checklist_division_id
                                        }task-area">
                                            <div class="collapse task-form mb-3" id="division${
                                              listVal.checklist_division_id
                                            }TaskFormCollapse">
                                                <div class="card card-body bg-light py-2">
                                                    <form action="${formApiUrl(
                                                      "admin/checklist/division/task/add",
                                                      {
                                                        checklist_id:
                                                          checklist_id,
                                                        checklist_division_id:
                                                          listVal.checklist_division_id,
                                                      }
                                                    )}" method="post" class="checklistDivisionTaskForm" 
                                                    id="checklistDivision${
                                                      listVal.checklist_division_id
                                                    }TaskForm">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="mb-2 mb-md-0">
                                                                    <label class="control-label">Criteria <span class="text-danger">*</span></label>
                                                                    <input type="text" name="task_name" class="form-control" placeholder="Criteria *" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-2 mb-md-0">
                                                                    <label class="control-label">Type</label>
                                                                    <div class="ele-jqValid">
                                                                        <div class="form-check-inline"><div class="custom-control custom-radio mr-2"><input value="1"
                                                                        id="division${
                                                                          listVal.checklist_division_id
                                                                        }taskcheck1" class="custom-control-input mx-2" type="radio" name="task_type" checked="">
                                                                        <label for="division${
                                                                          listVal.checklist_division_id
                                                                        }taskcheck1" class="custom-control-label">Checkbox</label></div>
                                                                        <div class="custom-control custom-radio mr-2">
                                                                        <input value="2" id="division${
                                                                          listVal.checklist_division_id
                                                                        }taskcheck2" class="custom-control-input mx-2" type="radio" name="task_type">
                                                                        <label for="division${
                                                                          listVal.checklist_division_id
                                                                        }taskcheck2" class="custom-control-label">Textbox</label>
                                                                        </div>
                                                                        <div class="custom-control custom-radio mr-2">
                                                                        <input value="3" id="division${
                                                                          listVal.checklist_division_id
                                                                        }taskcheck3" class="custom-control-input mx-2" type="radio" name="task_type">
                                                                        <label for="division${
                                                                          listVal.checklist_division_id
                                                                        }taskcheck3" class="custom-control-label">None</label></div></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2 align-self-center">
                                                                <hr class="d-block d-sm-none" />
                                                                <div class="mb-2 mb-md-0 d-flex align-items-center justify-content-between">
                                                                    <button type="submit" class="mb-sm-2 mb-md-0 btn btn-sm btn-indigo waves-effect waves-light"><i class="mdi mdi-plus"></i>&nbsp;Add</button>
                                                                    <button type="button" data-btn-close="collapse" data-target-btn-open="#btnOpenDiv${
                                                                      listVal.checklist_division_id
                                                                    }TaskCollapse" 
                                                                    data-toggle="collapse"
                                                                    data-target="#division${
                                                                      listVal.checklist_division_id
                                                                    }TaskFormCollapse" class="ml-1 btn btn-sm btn-secondary waves-effect waves-light"><i class="mdi mdi-close"></i>&nbsp;Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="checklist-task-row">
                                                <div class="checklist-task-col">
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
                                </div>`);

                  // Collapse btn click action
                  listContainer.on(
                    "click",
                    '[data-btn-open="collapse"]',
                    (e) => {
                      $(e.currentTarget).fadeOut();
                    }
                  );

                  listContainer.on(
                    "click",
                    '[data-btn-close="collapse"]',
                    (e) => {
                      let btnTarget = $(e.currentTarget).attr(
                        "data-target-btn-open"
                      );

                      $(btnTarget).fadeIn("slow");
                    }
                  );

                  // Load division tasks
                  let dtlist = `#division${listVal.checklist_division_id}task-area .checklist-task-row`;
                  this.loadDivisionTasks({
                    element: $(dtlist),
                    checklist_id: listVal.checklist_id,
                    division_id: listVal.checklist_division_id,
                  });
                });

                listContainer.find('[data-toggle="tooltip"]').tooltip(); // Load tooltip
                listContainer
                  .find('[data-toggle="tooltip"]')
                  .on("click", function () {
                    listContainer
                      .find('[data-toggle="tooltip"]')
                      .tooltip("hide"); // Load tooltip
                  });

                // Load division Methods
                this.loadDivisionMethods();
              } else {
                loadEmptyDivisionDetail();
              }
            } else {
              loadEmptyDivisionDetail();
            }
          } else if (res.status == "error") {
            loadEmptyDivisionDetail();
            toastr.error(res.message);
          } else {
            loadEmptyDivisionDetail();
            toastr.error("No response status");
          }
        })
        .catch((jqXHR) => {
          loadEmptyDivisionDetail();
          toastr.error(jqXHR.statusText);
        });
    };

    // Delete checklisttask
    $(listContainer).on("click", ".btn-delete-checklist-division", (e) => {
      e.preventDefault();
      var checklist_division_id = $(e.currentTarget).attr(
        "data-checklist-division"
      );
      Swal.fire({
        icon: "question",
        title: "Are you sure to delete division",
        showConfirmButton: true,
        confirmButtonText: "Yes",
        showCancelButton: true,
        cancelButtonText: "No",
        focusCancel: true,
        timer: false,
      }).then((result) => {
        if (result.isConfirmed) {
          let loadSwal;
          $.ajax({
            url: formApiUrl("admin/checklist/division/delete", {
              checklist_id: checklist_id,
              checklist_division_id: checklist_division_id,
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
                loadDivisionDetails(
                  formApiUrl("admin/checklist/division/list", {
                    checklist_id: checklist_id,
                  })
                ); // Load checklisttask details
                resetchecklistDivisionForm();

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

  loadDivisionMethods() {
    const checklistDivisionTaskForm = $(".checklistDivisionTaskForm");
    let checklistDivisionFormValidator;

    $(".checklistDivisionTaskForm").submit((e) => {
      e.preventDefault();

      var ctUrl = new URL($(e.currentTarget).attr("action"));

      // checklisttask Form
      if (
        parseValue($(e.currentTarget).find('[name="task_name"]').val()) == ""
      ) {
        toastr.error("Task name required");
      } else if (
        parseValue(
          $(e.currentTarget).find('[name="task_type"]:checked').length
        ) == ""
      ) {
        toastr.error("Task type required");
      } else {
        this.saveChecklistTask({
          form: $(e.currentTarget),
          checklist_id: ctUrl.searchParams.get("checklist_id"),
          division_id: ctUrl.searchParams.get("checklist_division_id"),
        });
      }
    });
  }

  saveChecklistTask(taskForm) {
    var formData = new FormData(taskForm.form[0]);
    let loadSwal;
    $.ajax({
      url: $(taskForm.form).attr("action"),
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
      .then((res) => {
        if (res.status == "success") {
          // resetchecklistDivisionTaskForm();    // Reset form
          taskForm.form[0].reset();
          let dtlist = `#division${taskForm.division_id}task-area .checklist-task-row`;
          this.loadDivisionTasks({
            element: $(dtlist),
            checklist_id: taskForm.checklist_id,
            division_id: taskForm.division_id,
          });

          toastr.success(res.message);
        } else if (res.status == "error") {
          toastr.error(res.message);
        } else {
          toastr.error("No response status");
        }
      })
      .catch((jqXHR, textStatus) => {
        toastr.error(jqXHR.statusText);
      });
  }

  loadDivisionTasks(options) {
    options.element
      .find(".checklist-task-col")
      .attr("data-jy-loader", "timeline");
    let loadSwal;
    $.ajax({
      url: formApiUrl("admin/checklist/division/task/list"),
      type: "get",
      data: {
        checklist_id: options.checklist_id,
        checklist_division_id: options.division_id,
      },
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
      .then(async (res) => {
        if (res.status == "success") {
          if (res.checklist_tasks && typeof res.checklist_tasks == "object") {
            var detail = res.checklist_tasks;
            await this.loadChecklistDivisionTaskView(options.element, detail);
          } else {
            await this.loadChecklistDivisionTaskView(options.element);
          }
        } else if (res.status == "error") {
          toastr.error(res.message);
          await this.loadChecklistDivisionTaskView(options.element);
        } else {
          toastr.error("Something went wrong!");
          await this.loadChecklistDivisionTaskView(options.element);
        }
      })
      .catch(async (xhr, errorText) => {
        toastr.error("Something went wrong!");
        await this.loadChecklistDivisionTaskView(options.element);
      });
  }

  loadChecklistDivisionTaskView(element, details = []) {
    if (details.length > 0) {
      element.html("");
      details.forEach((detail, dinx) => {
        let divisionTaskViewContent = `<div class="checklist-task-col">
                    <div class="card card-body bg-light position-relative py-2 mb-2">
                        <div class="media">
                            <span class="mr-2"><i class="mdi mdi-checkbox-marked-outline"></i></span>
                            <div class="media-body">
                                <p class="mb-0">${detail.name}</p>
                            </div>
                            <div>
                                <span class="small mr-4">${parseValue(
                                  detail.type.name
                                )}</span>
                                <button data-href="${formApiUrl(
                                  "admin/checklist/division/task/delete",
                                  {
                                    checklist_id: detail.checklist_id,
                                    checklist_division_id: detail.division_id,
                                  }
                                )}" data-checklist-division-task="${
          detail.checklist_task_id
        }" class="ml-1 btn btn-sm btn-outline-danger btn-delete-checklist-task"><i class="mdi mdi-delete"></i></button>
                            </div>
                        </div>
                    </div>
                </div>`;
        element.append(divisionTaskViewContent);
      });
    } else {
      let divisionTaskViewContent = `<div class="checklist-task-col">
                <div class="card card-body bg-light py-2"><p class="my-0">No Task</p></div>  
            </div>`;
      element.html(divisionTaskViewContent);
    }

    if (details.length > 0) {
      element.find("button.btn-delete-checklist-task").click((e) => {
        e.preventDefault();
        let ctf = $(e.currentTarget);
        var ctUrl = new URL(ctf.attr("data-href"));

        Swal.fire({
          icon: "question",
          title: "Are you sure to delete task",
          showConfirmButton: true,
          confirmButtonText: "Yes",
          showCancelButton: true,
          cancelButtonText: "No",
          focusCancel: true,
          timer: false,
        }).then((result) => {
          if (result.isConfirmed) {
            let loadSwal;
            $.ajax({
              url: ctf.attr("data-href"),
              type: "post",
              data: {
                checklist_task_id: $(e.currentTarget).attr(
                  "data-checklist-division-task"
                ),
              },
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
                ctf
                  .html('<i class="fa fa-spinner fa-spin"><i>')
                  .attr("disabled", true);
              },
              complete: function () {
                loadSwal.close();
                ctf
                  .html('<i class="mdi mdi-delete"><i>')
                  .attr("disabled", false);
              },
            })
              .then((res) => {
                if (res.status == "success") {
                  toastr.success(res.message);
                  // Load hierarchy user
                  let dtlist = `#division${ctUrl.searchParams.get(
                    "checklist_division_id"
                  )}task-area .checklist-task-row`;
                  this.loadDivisionTasks({
                    element: $(dtlist),
                    checklist_id: ctUrl.searchParams.get("checklist_id"),
                    division_id: ctUrl.searchParams.get(
                      "checklist_division_id"
                    ),
                  });
                } else {
                  toastr.error(res.message);
                }
              })
              .catch((error) => {
                toastr.error(error.statusText);
              });
          }
        });
      });
    }
  }
}

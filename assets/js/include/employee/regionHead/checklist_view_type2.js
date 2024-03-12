/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: checklisttask js
 */

class ChecklistViewType2 {
  loadDivisionAreaView() {
    let mviewContent = `
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
    // const btnResetchecklistDivisionForm = $('#btn-reset-checklisttask-form');
    listPagination.html("");

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
                                            <h6 class="my-0">${listVal.name}</h6>
                                        </div>
                                        <div class="card-body" id="division${listVal.checklist_division_id}task-area">
                                            <div class="collapse task-form mb-3" id="division${listVal.checklist_division_id}TaskFormCollapse">
                                                <div class="card card-body bg-light py-2">
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
                                                                    <div class="form-check-inline"><div class="custom-control custom-radio mr-2"><input value="1" id="division${listVal.checklist_division_id}taskcheck1" class="custom-control-input mx-2" type="radio" name="task_type" checked=""><label for="division${listVal.checklist_division_id}taskcheck1" class="custom-control-label">Checkbox</label></div>
                                                                    <div class="custom-control custom-radio mr-2"><input value="2" id="division${listVal.checklist_division_id}taskcheck2" class="custom-control-input mx-2" type="radio" name="task_type"><label for="division${listVal.checklist_division_id}taskcheck2" class="custom-control-label">Textbox</label></div>
                                                                    <div class="custom-control custom-radio mr-2"><input value="3" id="division${listVal.checklist_division_id}taskcheck3" class="custom-control-input mx-2" type="radio" name="task_type"><label for="division${listVal.checklist_division_id}taskcheck3" class="custom-control-label">None</label></div></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
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
                // this.loadDivisionMethods();
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
  }

  loadDivisionTasks(options) {
    options.element
      .find(".checklist-task-col")
      .attr("data-jy-loader", "timeline");
    let loadSwal;
    $.ajax({
      url: formApiUrl("employee/checklist/division/task/list"),
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
  }
}

/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: checklisttask js
 */

class ChecklistViewType1 {
  loadTaskAreaView() {
    let mviewContent = `
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
    listPagination.html("");

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
                                            )}</span></div>
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
                //         var page_link = formApiUrl('employee/checklist/task/list', { checklist_id: checklist_id, page: pageNumber });
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
  }
}

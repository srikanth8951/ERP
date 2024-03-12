/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: checklist js
 */

var appchecklist = {};

$(function () {
  const listArea = $("#checklist--deatils--area");
  const listContainer = listArea.find('[data-container="checklistArea"]');
  const listPagination = listArea.find('[data-pagination="checklistArea"]');
  listPagination.html("");

  window.loadEmptyDetail = function () {
    listContainer.html("");
    listPagination.html("");
    listContainer.append(
      "<tr>" +
        '<td colspan="6" class="text-center">No Details Found!</td>' +
        "</tr>"
    );
  };

  window.loadChecklistDetails = function (href) {
    listContainer.find("tr").attr("data-jy-loader", "timeline");

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

                var checklistViewLink = formUrl(
                  "employee/nationalHead/checklist/view/" + listVal.checklist_id
                );
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
                  var page_link = formApiUrl("employee/checklist/list", {
                    page: pageNumber,
                  });
                  loadChecklistDetails(page_link);
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
});

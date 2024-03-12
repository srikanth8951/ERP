/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: employee js
 */

var appEmpRegionHead = {};

$(function () {
  const searchForm = $("#searchForm");
  const listArea = $("#empregionhead--deatils--area");
  const listContainer = listArea.find('[data-container="empRegionHeadArea"]');
  const listPagination = listArea.find('[data-pagination="empRegionHeadArea"]');
  listPagination.find(".list-pagination").html("");
  listPagination.find(".list-pagination-label").html("");

  window.loadEmptyDetail = function () {
    listContainer.html("");
    listPagination.find(".list-pagination").html("");
    listPagination.find(".list-pagination-label").html("");
    listContainer.append(`<div class="col-md-12">
            <div class="card m-b-20 card-body" style="padding: 0.75rem;">
                <div class="row align-items-center ">
                    <div class="col text-center"><h6>No Employee available!</h6></div>
                </div>
            </div>
        </div>`);
  };

  window.loadEmpDetails = function (href) {
    let loadSwal;
    let newUrl = href;
    var Url = new URL(href);
    if (parseValue(searchForm.find('[name="search"]').val()) != "") {
      Url.searchParams.set("search", searchForm.find('[name="search"]').val());
      newUrl = Url.toString();
    }

    $.ajax({
      url: newUrl,
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
          if (res.employees) {
            listContainer.html("");
            var details = res.employees.data;
            var pagination = res.employees.pagination;
            if (details.length && pagination.total > 0) {
              let status_badge_class = "";
              $.each(details, function (listIn, listVal) {
                var email;
                if (listVal.email) {
                  email = listVal.email;
                } else {
                  email = `-`;
                }

                var employeeViewLink = formUrl(
                  "employee/aisd/users/regional_head/view/" +
                    listVal.employee_id
                );
                listContainer.append(`<div class="col-md-12">
                                    <div class="card m-b-20 card-body" style="padding: 0.75rem;">
                                        <div class="row align-items-center ">
                                            <div class="col-md-3 clickable" data-link="${employeeViewLink}"><h6>${listVal.first_name}  ${listVal.last_name}</h6></div>
                                            <div class="col-md-3 clickable" data-link="${employeeViewLink}">Email : ${listVal.email}</div>
                                            <div class="col-md-3 clickable" data-link="${employeeViewLink}"> Mobile : ${listVal.mobile}</div>
                                            <div class="col-md-3"> 
                                            <div class="text-center">
                                                <a href="${employeeViewLink}"><i class="fa fa-address-card-o text-info"></i></a>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>`);
              });

              $(".clickable").click(function () {
                location.href = $(this).data("link");
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
                  var page_link = formApiUrl(
                    "employee/aisd/users/region_head/list",
                    {
                      start: parseInt(pagination.length) * (pageNumber - 1) + 1,
                    }
                  );
                  loadEmpDetails(page_link);
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
          toastr.error("No response status!", "Error");
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

  searchForm.submit(function (e) {
    e.preventDefault();
    loadEmpDetails(formApiUrl("employee/aisd/users/region_head/list")); // Load employee details
  });
});

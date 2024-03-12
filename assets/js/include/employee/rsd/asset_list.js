/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: Asset js
 */

var appAsset = {};

$(function () {
    const searchForm = $('#searchForm');
    const listArea = $('#asset--deatils--area');
    const listContainer = listArea.find('[data-container="assetArea"]');
    const listPagination = listArea.find('[data-pagination="assetArea"]');
    listPagination.find(".list-pagination").html("");
    listPagination.find(".list-pagination-label").html("");

    window.loadAssetDetail = function () {
        listContainer.html('');
        listPagination.find(".list-pagination").html("");
        listPagination.find(".list-pagination-label").html("");
        listContainer.append(`<div class="col-md-12">
              <div class="card m-b-20 card-body" style="padding: 0.75rem;">
                  <div class="row align-items-center ">
                      <div class="col text-center"><h6>No asset available!</h6></div>
                  </div>
              </div>
          </div>`);
    }

    window.loadAssetDetails = function (href) {
        let loadSwal; let newUrl = href;
        var Url = new URL(href);
        if (parseValue(searchForm.find('[name="search"]').val()) != '') {
            Url.searchParams.set('search', searchForm.find('[name="search"]').val());
            newUrl = Url.toString();
        }

        $.ajax({
            url: newUrl,
            type: 'get',
            dataType: 'json',
            headers: {
                Authorization: `Bearer ${wapLogin.getToken()}`
            },
            beforeSend: function () {
                loadSwal = Swal.fire({
                    html: '<div class="my-4 text-center d-inline-block">' + loaderContent + '</div>',
                    customClass: {
                        popup: 'col-6 col-sm-5 col-md-3 col-lg-2'
                    },
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false
                });
            },
            success: function (res) {
                if (res.status == 'success') {
                    if (res.assets) {
                        var details = res.assets.data;
                        var pagination = res.assets.pagination;
                        listContainer.html('');

                        if (details.length && pagination.total > 0) {
                            
                            let status_badge_class = '';
                            $.each(details, function (listIn, listVal) {
                                let assetViewLink = formUrl(`employee/rsd/asset/view/${listVal.asset_id}`);
                                if (listVal.status == 1) {
                                    status_badge_class = 'badge-success';
                                } else {
                                    status_badge_class = 'badge-danger';
                                }

                                listContainer.append(`<div class="col-md-12">
                                    <div class="card m-b-20 card-body">
                                        <h3 class="card-title font-20 mt-0">${listVal.name}</h3>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <b class="text-muted">Group</b> : ${listVal.group_name}
                                            </div>
                                            <div class="col-md-3">
                                                <b class="text-muted">Sub-group</b> : ${listVal.sub_group_name}
                                            </div>
                                            <div class="col-md-3">
                                                <b class="text-muted">Location</b> : ${listVal.location}
                                            </div>
                                            <div class="col-md-2">
                                                <a href="${assetViewLink}"  class="btn btn-sm btn-teal btn-view-asset waves-light waves-effect"><i class="mdi mdi-eye"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>`);
                            });

                            listContainer.find('[data-toggle="tooltip"]').tooltip();    // Load tooltip
                            listPagination.find(".list-pagination-label")
                                .html(`Showing ${pagination.start} to ${(parseInt(pagination.start) -1) + pagination.records} of ${pagination.total}`);
                            listPagination.find(".list-pagination").pagination({
                                items: parseInt(pagination.total),
                                itemsOnPage: parseInt(pagination.length),
                                currentPage: Math.ceil(parseInt(pagination.start) / parseInt(pagination.length)),
                                displayedPages: 3,
                                navStyle: 'pagination',
                                listStyle: 'page-item',
                                linkStyle: 'page-link',
                                onPageClick: function (pageNumber, event) {
                                    var page_link = formApiUrl('employee/rsd/asset/list', { start: ((parseInt(pagination.length) * (pageNumber - 1)) + 1)});
                                    loadAssetDetails(page_link);
                                }
                            });

                        } else {
                            loadAssetDetail();
                        }
                    } else {
                        loadAssetDetail();
                    }
                } else if (res.status == 'error') {
                    // toastr.error(res.message);
                    loadAssetDetail();
                } else {
                    toastr.error('No response status!', 'Error');
                    loadAssetDetail();
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                toastr.error(`${textStatus} <br />${errorThrown}`, 'Error');
                loadAssetDetail();
            },
            complete: function () {
                loadSwal.close();
            }
        });
    }

    searchForm.submit(function (e) {
        e.preventDefault();
        loadAssetDetails(formApiUrl('employee/rsd/asset/list'));  // Load contract job details
    });

});


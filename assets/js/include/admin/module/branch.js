/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 Path: module
 File: branch js
 */

"use strict";

class ModuleBranch {
  constructor(options) {
    this.autoloadUrl = options.autoloadUrl;
    this.selectboxElement = options.selectboxElement;
    this.branchForm = "";
    this.branchModal = "";
    this.branchFormValidator = "";
  }

  autocomplete(selectedValues = {}) {
    let selectedValuess =
      typeof selectedValues.selected == "object" &&
      Object.keys(selectedValues.selected).length > 0
        ? selectedValues.selected
        : Object.assign({}, selectedValues.selected);

    $.ajax({
      url: this.autoloadUrl,
      type: "get",
      dataType: "json",
      headers: {
        Authorization: `Bearer ${wapLogin.getToken()}`,
      },
    })
      .then((res) => {
        this.selectboxElement.html("").trigger("change"); // Reset selectbox
        if (res.status == "success") {
          // Set default option
          let branchOptionz = new Option("Select", "", false, false);
          this.selectboxElement.append(branchOptionz);

          if (res.branches) {
            let branches = res.branches;
            let branchOption;

            branches.forEach((branch, bi) => {
              if (
                Object.values(selectedValuess).find((value) => {
                  return value == branch.id;
                })
              ) {
                branchOption = new Option(branch.name, branch.id, true, true);
              } else {
                branchOption = new Option(branch.name, branch.id, false, false);
              }
              this.selectboxElement.append(branchOption);
            });
            this.selectboxElement.trigger("change");
          }
        } else if (res.status == "error") {
          console.log(res.message);
        } else {
          console.log("branch Autocomlete: Something went wrong!");
        }
      })
      .catch((xhr) => {
        console.log(xhr.responseText + " " + xhr.responseText);
      });
  }

  loadPrompt(option) {
    if ($("#branchModal").length <= 0) {
      let prompt = `<!-- branch Modal -->
              <div class="modal fade" id="branchModal">
                  <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h4 class="font-18 modal-title">Add branch</h4>
                              <button type="button" data-dismiss="modal" class="close"><i class="mdi mdi-close"></i></button>
                          </div>
                          <div class="modal-body">
                              <form id="branchForm" action="${option.submitAction}" enctype="multipart/form-data">
                                  <div class="row">
                                      <div class="col-md-12">
                                          <div class="form-group ele-jqValid">
                                             <label class="control-label">Region<span class="text-danger">*</span></label>
                                             <select name="region_id" data-toggle="select2" class="select2 form-control" required></select>
                                          </div>
                                      </div>
                                      <div class="col-md-12">
                                          <div class="form-group">
                                              <label class="control-label">Name<span class="text-danger">*</span></label>
                                              <input type="text" name="branch_name" class="form-control"/>
                                          </div>
                                      </div>
                                      <div class="col-md-12">
                                          <div class="form-group">
                                              <label class="control-label">Code<span class="text-danger">*</span></label>
                                              <input type="text" name="branch_code" class="form-control"/>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <a href="javascript:void(0)" id="btn-reset-branch-form" class="btn btn-secondary btn-sm" style="display:none;">
                                          <i class="mdi mdi-reload"></i>&nbsp;Reset
                                      </a>
                                      <button type="submit" class="btn btn-indigo btn-sm btn-submit">
                                          <i class="mdi mdi-content-save"></i>&nbsp;Save
                                      </button>
                                  </div>
                              </form>
                          </div>
                      </div>
                  </div>
              </div>`;

      $("body").append(prompt);
      this.loadMethods(option);
      
    }

    $("#branchModal").modal({
      backdrop: "static",
      keyboard: false,
      show: true,
    });
    $("#branchForm").find('[data-toggle="select2"]').select2();
  }

  loadMethods(optionz) {
    this.branchForm = $("#branchForm");
    this.branchModal = $("#branchModal");

    this.loadRegionAutocomplete(optionz);

    // branch Form
    this.branchFormValidator = this.branchForm.validate({
      onkeyup: (element) => {
        $(element).valid();
      },
      onclick: (element) => {
        $(element).valid();
      },
      rules: {
        // region_id: {
        //   requried: true,
        // },
        branch_name: {
          required: true,
          // minlength: 3,
        },
        branch_code: {
          required: true,
        },
      },
      messages: {
        // region_id: {
        //   required: "Specify region",
        // },
        branch_name: {
          required: "Specify branch name",
          // minlength: "Specify atleast 3 characters",
        },
        branch_code: {
          required: "Specify code",
        },
      },
      errorPlacement: (error, element) => {
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
    //   submitHandler: () => {
    //       console.log('Ready to go');
    //     // this.save(optionz);
    //   }
    });

    this.branchForm.submit((e) => {
      e.preventDefault();

      if (this.branchFormValidator.valid()) {
        this.save(optionz);
      }
    });

    // Modal Form close
    this.branchModal.find('[data-dismiss="modal"]').click(() => {
      this.resetForm();
    });
  }

  loadRegionAutocomplete(options) {
    var selected;
    var regionSelectbox = this.branchForm.find('[name="region_id"]');

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

            $.each(regions, (bi, region) => {
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
  }

  resetForm(resetAction = false) {
    if (resetAction == true) {
      this.branchForm.attr("action", ""); // Form Attribute
    }

    this.branchForm[0].reset(); // Form
    this.branchForm
      .find('[data-toggle="select2"]')
      .prop("disabled", false)
      .val(null)
      .trigger("change"); // Select2
    this.branchFormValidator.resetForm(); // Jquery validation
  }

  save(optionz) {
      
    var formData = new FormData(this.branchForm[0]);

    $.ajax({
      url: this.branchForm.attr("action"),
      type: "post",
      dataType: "json",
      data: formData,
      headers: {
        Authorization: `Bearer ${wapLogin.getToken()}`,
      },
      processData: false,
      contentType: false,
      cache: false,
    }).then((res) => {
        if (res.status == "success") {
          this.resetForm(); // Reset form
          this.autocomplete(); // Reload selectbox
          this.branchModal.modal("hide"); // Hide modal
          toastr.success(res.message);
        } else if (res.status == "error") {
          toastr.error(res.message);
        } else {
          toastr.error(res.message);
        }
      }).catch((jqXHR) => {
        toastr.error(jqXHR);
      });
  }
}

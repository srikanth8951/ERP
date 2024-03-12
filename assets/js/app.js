/*
 Template Name: Admiria - Bootstrap 4 Admin Dashboard
 Author: Mentric
 File: Main js
 */

var appData = {};
var paramDefaults = {};
var processStatus = false;

// Loader Content
const loaderContent = $("#preloader #status").html();

//Statuses
const qstatuses = qstatusesStr ? JSON.parse(qstatusesStr) : {};
const statuses = statusesStr ? JSON.parse(statusesStr) : {};

function getStatusText($status) {
    return statuses[$status] != 'undefined' ? statuses[$status] : '';
}

function getQStatusText($status) {
    return qstatuses[$status] != 'undefined' ? qstatuses[$status] : '';
}

// App Data
function getAppData(appkey) {
  keydata = "";
  if (appkey && Object.keys(appData).length > 0) {
    appdata = JSON.parse(appData);
    if (typeof appdata[appkey] != "undefined") {
      keydata = appdata[appkey];
    }
  }

  return keydata;
}

function formApiUrl(path, params = {}) {
  let paramStr = "";
  if (Object.keys(params).length > 0) {
    paramStr = "?" + $.param(params);
  }

  return api_base_url + path + paramStr;
}

function formUrl(path, params = {}) {
  let paramStr = "";
  if (Object.keys(params).length > 0) {
    paramStr = "?" + $.param(params);
  }

  return base_url + path + paramStr;
}

// Parse Value
function parseValue(value) {
  if (typeof value != "undefined" && value != null && value != "") {
    return value;
  } else {
    return "";
  }
}

function copyToClipboard(value) {
  return new Promise((resolve, reject) => {
    var $tempInput = $("<textarea>");
    $tempInput.appendTo("body").val(value).select();
    document.execCommand("copy");
    $tempInput.remove();
    resolve(true);
  });
}

// Genrate Random String
function generateRandomString(length) {
  var charset =
      "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
    retVal = "";
  for (var i = 0, n = charset.length; i < length; ++i) {
    retVal += charset.charAt(Math.floor(Math.random() * n));
  }
  return retVal;
}

// Measurement Units
function getMeasurementUnits() {
  return [
    { name: "TR", code: "tr" },
    { name: "CFM", code: "cfm" },
    { name: "HP", code: "hp" },
    { name: "KVA", code: "kva" },
    { name: "KW", code: "kw" },
    { name: "No", code: "no" },
    { name: "KVAR", code: "kvar" },
    { name: "Nos", code: "nos" },
    { name: "W", code: "w" },
    { name: "Hz", code: "hz" },
    { name: "VOLTS", code: "volts" },
    { name: "Ohm", code: "ohm" },
    { name: "Mili Ohm", code: "mili_ohm" },
    { name: "Mega Ohm", code: "mega_ohm" },
    { name: "Giga Ohm", code: "giga_ohm" },
    { name: "Tera Ohm", code: "tera_ohm" },
    { name: "AMP", code: "amp" },
    { name: "TDS", code: "tds" },
    { name: "Ph", code: "ph" },
    { name: "Pa", code: "pa" },
    { name: "Kg/sq cm", code: "kg_sq_cm" },
    { name: "Deg C", code: "deg_c" },
    { name: "Deg F", code: "deg_f" },
    { name: "Deg K", code: "deg_k" },
    { name: "dB", code: "db" },
    { name: "m/s", code: "m_s" },
    { name: "%", code: "precentage" },
    { name: "MP", code: "mp" },
  ];
}

!(function ($) {
  "use strict";

  var MainApp = function () {
    (this.$body = $("body")),
      (this.$wrapper = $("#wrapper")),
      (this.$btnFullScreen = $("#btn-fullscreen")),
      (this.$leftMenuButton = $(".button-menu-mobile")),
      (this.$menuItem = $(".has_sub > a"));
  };
  //scroll
  (MainApp.prototype.initSlimscroll = function () {
    $(".slimscrollleft").slimscroll({
      height: "auto",
      position: "right",
      size: "10px",
      color: "#9ea5ab",
    });
  }),
    //left menu
    (MainApp.prototype.initLeftMenuCollapse = function () {
      var $this = this;
      this.$leftMenuButton.on("click", function (event) {
        event.preventDefault();
        $this.$body.toggleClass("fixed-left-void");
        $this.$wrapper.toggleClass("enlarged");
      });
    }),
    //left menu
    (MainApp.prototype.initComponents = function () {
      $('[data-toggle="tooltip"]').tooltip();
      $('[data-toggle="popover"]').popover();
    }),
    //full screen
    (MainApp.prototype.initFullScreen = function () {
      var $this = this;
      $this.$btnFullScreen.on("click", function (e) {
        e.preventDefault();

        if (
          !document.fullscreenElement &&
          /* alternative standard method */ !document.mozFullScreenElement &&
          !document.webkitFullscreenElement
        ) {
          // current working methods
          if (document.documentElement.requestFullscreen) {
            document.documentElement.requestFullscreen();
          } else if (document.documentElement.mozRequestFullScreen) {
            document.documentElement.mozRequestFullScreen();
          } else if (document.documentElement.webkitRequestFullscreen) {
            document.documentElement.webkitRequestFullscreen(
              Element.ALLOW_KEYBOARD_INPUT
            );
          }
        } else {
          if (document.cancelFullScreen) {
            document.cancelFullScreen();
          } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
          } else if (document.webkitCancelFullScreen) {
            document.webkitCancelFullScreen();
          }
        }
      });
    }),
    //full screen
    (MainApp.prototype.initMenu = function () {
      var $this = this;
      $this.$menuItem.on("click", function () {
        var parent = $(this).parent();
        var sub = parent.find("> ul");

        if (!$this.$body.hasClass("sidebar-collapsed")) {
          if (sub.is(":visible")) {
            sub.slideUp(300, function () {
              parent.removeClass("nav-active");
              $(".body-content").css({ height: "" });
              adjustMainContentHeight();
            });
          } else {
            visibleSubMenuClose();
            parent.addClass("nav-active");
            sub.slideDown(300, function () {
              adjustMainContentHeight();
            });
          }
        }
        return false;
      });

      //inner functions
      function visibleSubMenuClose() {
        $(".has_sub").each(function () {
          var t = $(this);
          if (t.hasClass("nav-active")) {
            t.find("> ul").slideUp(300, function () {
              t.removeClass("nav-active");
            });
          }
        });
      }

      function adjustMainContentHeight() {
        // Adjust main content height
        var docHeight = $(document).height();
        if (docHeight > $(".body-content").height())
          $(".body-content").height(docHeight);
      }
    }),
    (MainApp.prototype.activateMenuItem = function () {
      // === following js will activate the menu in left side bar based on url ====
      $("#sidebar-menu a").each(function (index, element) {
        
        var pageUrl = window.location.href.split(/[?#]/)[0];
        if (element.href == pageUrl) {
          $(element).addClass("active");
          $(element).parent().addClass("active"); // add active to li of the current link
          $(element).parent().parent().prev().addClass("active"); // add active class to an anchor
          $(element).parent().parent().parent().addClass("active"); // add active class to an anchor
          $(element).parent().parent().prev().click(); // click the item to make it drop
        }
      });

      if ($('#sidebar-menu li.active').length > 0) {
        $('#sidebar-menu li.active')[0].scrollIntoView();
      }
      
    }),
    (MainApp.prototype.Preloader = function () {
      $(window).on("load", function () {
        $("#status").fadeOut();
        $("#preloader").delay(350).fadeOut("slow");
        $("body").delay(350).css({
          overflow: "visible",
        });
      });
    }),
    (MainApp.prototype.ToggleSearch = function () {
      $(".toggle-search").on("click", function () {
        var targetId = $(this).data("target");
        var $searchBar;
        if (targetId) {
          $searchBar = $(targetId);
          $searchBar.toggleClass("open");
        }
      });
    }),
    (MainApp.prototype.init = function () {
      this.initSlimscroll();
      this.initLeftMenuCollapse();
      this.initComponents();
      this.initFullScreen();
      this.initMenu();
      this.activateMenuItem();
      this.Preloader();
      this.ToggleSearch();
    }),
    //init
    ($.MainApp = new MainApp()),
    ($.MainApp.Constructor = MainApp);
})(window.jQuery),
  //initializing
  (function ($) {
    "use strict";
    $.MainApp.init();

    //Cookie Defaults
    $.cookie.defaults = {
      expires: 1,
      path: domain_path.substring(0, domain_path.length - 1),
      domain: domain_name,
      secure: false,
    };

    // Validator Regular Expression Method
    $.validator.addMethod(
      "regex",
      function (value, element, param) {
        param = new RegExp(param);
        return this.optional(element) || param.test(value);
      },
      "Invalid format."
    );
  })(window.jQuery);

"use strict";
let card = document.querySelectorAll(".card");
let tableWrapper = document.querySelectorAll(".table-responsive");
let collapseMenu = document.querySelectorAll(".card-header--toggle");
let modal = document.querySelectorAll(".modal");
let modalButton = document.querySelectorAll('button[data-toggle="modal"]');
const pageCheck = document.querySelector('h1[class="page-title"]');
const clearButtons = document.querySelectorAll(".clear__area");
const appContent = document.querySelector(".app-content");
const header = document.querySelector(".header");
const menuButton = document.querySelector(".top-header__button");
const slideMenu = document.querySelector(".js-sidebar");
const statisticButton = document.querySelectorAll(".project-block__item--btn");
const cardBody = document.querySelectorAll(".card-body");
const avatarCheck = document.querySelector(".avatar");
const headerMenu = document.querySelector(".navbar-toggler");
const themeSwitcher = document.querySelectorAll(".section-header__theme--link");
const cardToggle = (elem, padding = false, overflow = "visible") => {
  elem.classList.toggle("_hidden");
  if (elem.style.height === elem.getAttribute("data-height")) {
    elem.style.height = "0px";
    elem.style.overflow = "hidden";
    if (padding) {
      elem.style.padding = "0px";
    }
  } else {
    if (padding) {
      elem.style.padding = padding;
    }
    setTimeout(() => {
      elem.style.overflow = overflow;
    }, 300);
    elem.style.height = elem.getAttribute("data-height");
  }
};

const tableResize = (elem, parent) => {
  const itemTable = elem.querySelector(parent) || elem;
  elem.setAttribute("data-height", itemTable.clientHeight + "px");
  if (elem.clientHeight === 0) {
    elem.style.height = "0px";
  } else {
    elem.style.height = itemTable.clientHeight + "px";
  }
};

const replaceModal = (elem, replaceTo, returnTo, activeClass) => {
  if (!elem.classList.contains(activeClass)) {
    replaceTo.prepend(elem);
    elem.classList.add(activeClass);
  } else {
    returnTo?.append(elem);
    elem.classList.remove(activeClass);
  }
};

const eachModal = (array) => {
  array.forEach((item, index) => {
    item.addEventListener("click", (e) => {
      replaceModal(
        modal[index],
        document.body,
        modalButton[index]?.parentElement,
        "modal_active"
      );
    });
  });
};

const toggleTheme = (elem) => {
  document.body.classList.toggle("theme-dark");
  for (let key of themeSwitcher) {
    const body = document.body;
    if (!body.classList.contains("theme-dark")) {
      key.classList.remove("dark_theme");
      localStorage.removeItem("theme");
    } else {
      key.classList.add("dark_theme");
      localStorage.setItem("theme", true);
    }
  }
};

if (menuButton) {
  menuButton.addEventListener("click", () => {
  menuButton.classList.toggle("_active");
  document.body.classList.toggle("_hide");
  slideMenu.classList.toggle("_active");
});
}



const initModal = () => {
  eachModal(modalButton);
  eachModal(modal);
};

if (clearButtons) {
  clearButtons.forEach((item) => {
    item.addEventListener("click", () => {
      const input =
        item.parentElement.querySelector("input") ||
        item.parentElement.querySelector("textarea");
      input.value = "";
    });
  });
}


if (statisticButton) {
  const closeButtonPopup = document.querySelectorAll("._popup-close-btn");
  statisticButton.forEach((item) => {
    item.addEventListener("click", () => {
      const popup = document.querySelector("._popup");
      popup.classList.add("_active");
    });
  });

  closeButtonPopup.forEach((elem, index) => {
    elem.addEventListener("click", () => {
      const popup = document.querySelector("._popup");
      popup.classList.remove("_active");
    });
  });
}
themeSwitcher.forEach((item) => {
  item.addEventListener("click", () => toggleTheme());
});

if (localStorage.getItem("theme")) {
  toggleTheme();
}

if (pageCheck?.textContent.trim() === "Services") {
  appContent ? appContent.classList.add("page-services") : "";
}

const initTableWrapper = () => {
  tableWrapper.forEach((item) => tableResize(item, ".card-table"));
  window.addEventListener("resize", () => {
    tableWrapper.forEach((item) => tableResize(item, ".card-table"));
  });

  collapseMenu.forEach((item, index) => {
    item.addEventListener("click", () => {
      card[index].classList.toggle("card-collapsed");
      !cardBody.length
        ? cardToggle(tableWrapper[index], "", "visible")
        : cardToggle(cardBody[index], "1.5rem 1.5rem");
    });
  });
};

if (modal) {
  initModal();
}

if (headerMenu && header) {
  headerMenu.addEventListener("click", () => {
    header.classList.toggle("_toggled");
  });
}

if (tableWrapper) {
  initTableWrapper();
}

cardBody && cardBody.forEach((item) => tableResize(item));

function General() {
  var self = this;
  this.init = function () {
    self.General();
    self.AddFunds();
    if ($("#order_resume").length > 0) {
      self.Order();
      self.CalculateOrderCharge();
    }

    if ($(".navbar-side").length > 0) {
      self.MenuOption();
    }
  };

  this.MenuOption = function () {
    const ps1 = new PerfectScrollbar(".navbar-side .scroll-bar", {
      wheelSpeed: 1,
      wheelPropagation: true,
      minScrollbarLength: 10,
      suppressScrollX: true,
    });

    $(document).on("click", ".mobile-menu", function () {
      var _that = $(".navbar.navbar-side");
      if (_that.hasClass("navbar-folded")) {
        _that.removeClass("navbar-folded");
      }
      _that.toggleClass("active");
    });
  };

  this.AddFunds = function () {
    $(document).on("submit", ".actionAddFundsForm", function () {
      pageOverlay.show();
      event.preventDefault();
      var _that = $(this),
        _action = PATH + "add_funds/process",
        _redirect = _that.data("redirect"),
        _data = _that.serialize();
      _data = _data + "&" + $.param({ token: token });
      $.post(_action, _data, function (_result) {
        setTimeout(function () {
          pageOverlay.hide();
        }, 1500);
        if (is_json(_result)) {
          _result = JSON.parse(_result);
          if (
            _result.status == "success" &&
            typeof _result.redirect_url != "undefined"
          ) {
            window.location.href = _result.redirect_url;
          }
          setTimeout(function () {
            notify(_result.message, _result.status);
          }, 1500);
          setTimeout(function () {
            if (
              _result.status == "success" &&
              typeof _redirect != "undefined"
            ) {
              reloadPage(_redirect);
            }
          }, 2000);
        } else {
          setTimeout(function () {
            $(".add-funds-form-content").html(_result);
          }, 100);
        }
      });
      return false;
    });
  };

  this.Order = function () {
    var _total_quantity = 0;
    var _service_price = 0;

    $(document).on("input", ".ajaxQuantity", function () {
      var _that = $(this),
        _quantity = _that.val(),
        _service_id = $("#service_id").val(),
        _service_max = $("#order_resume input[name=service_max]").val(),
        _service_min = $("#order_resume input[name=service_min]").val(),
        _service_price = $("#order_resume input[name=service_price]").val(),
        _is_drip_feed = $("#new_order input[name=is_drip_feed]:checked").val();
      if (_is_drip_feed) {
        var _runs = $("#new_order input[name=runs]").val();
        var _interval = $("#new_order input[name=interval]").val();
        var _total_quantity = _runs * _quantity;
        if (_total_quantity != "") {
          $("#new_order input[name=total_quantity]").val(_total_quantity);
        }
      } else {
        var _total_quantity = _quantity;
      }
      var _total_charge =
        _total_quantity != "" && _service_price != ""
          ? (_total_quantity * _service_price) / 1000
          : 0;
      _total_charge = preparePrice(_total_charge);
      var _currency_symbol = $("#new_order input[name=currency_symbol]").val();
      $("#new_order input[name=total_charge]").val(_total_charge);
      $("#new_order .total_charge span").html(_currency_symbol + _total_charge);
    });

    // callback ajaxDripFeedRuns
    $(document).on("input", ".ajaxDripFeedRuns", function () {
      var _that = $(this),
        _runs = _that.val(),
        _service_id = $("#service_id").val(),
        _quantity = $("#new_order input[name=quantity]").val(),
        _service_max = $("#order_resume input[name=service_max]").val(),
        _service_min = $("#order_resume input[name=service_min]").val(),
        _service_price = $("#order_resume input[name=service_price]").val(),
        _is_drip_feed = $("#new_order input[name=is_drip_feed]:checked").val();
      if (_is_drip_feed) {
        var _interval = $("#new_order input[name=interval]").val();
        var _total_quantity = _runs * _quantity;
        if (_total_quantity != "") {
          $("#new_order input[name=total_quantity]").val(_total_quantity);
        }
      } else {
        var _total_quantity = _quantity;
      }
      var _total_charge =
        _total_quantity != "" && _service_price != ""
          ? (_total_quantity * _service_price) / 1000
          : 0;
      _total_charge = preparePrice(_total_charge);
      var _currency_symbol = $("#new_order input[name=currency_symbol]").val();
      $("#new_order input[name=total_charge]").val(_total_charge);
      $("#new_order .total_charge span").html(_currency_symbol + _total_charge);
    });

    $(document).on("click", ".is_drip_feed", function () {
      var _that = $(this),
        _service_id = $("#service_id").val(),
        _quantity = $("#new_order input[name=quantity]").val(),
        _service_max = $("#order_resume input[name=service_max]").val(),
        _service_min = $("#order_resume input[name=service_min]").val(),
        _service_price = $("#order_resume input[name=service_price]").val();
      if (_that.is(":checked")) {
        var _runs = $("#new_order input[name=runs]").val();
        var _interval = $("#new_order input[name=interval]").val();
        var _total_quantity = _runs * _quantity;
        if (_total_quantity != "") {
          $("#new_order input[name=total_quantity]").val(_total_quantity);
        }
      } else {
        var _total_quantity = _quantity;
      }
      var _total_charge =
        _total_quantity != "" && _service_price != ""
          ? (_total_quantity * _service_price) / 1000
          : 0;
      _total_charge = preparePrice(_total_charge);
      var _currency_symbol = $("#new_order input[name=currency_symbol]").val();
      $("#new_order input[name=total_charge]").val(_total_charge);
      $("#new_order .total_charge span").html(_currency_symbol + _total_charge);
    });

    // callback ajaxChangeCategory
    $(document).on("change", ".ajaxChangeCategory", function () {
      event.preventDefault();
      $("#new_order .drip-feed-option").addClass("d-none");
      if ($("#order_resume").length > 0) {
        $("#order_resume input[name=service_name]").val("");
        $("#order_resume input[name=service_min]").val("");
        $("#order_resume input[name=service_max]").val("");
        $("#order_resume input[name=service_price]").val("");
        $("#order_resume textarea[name=service_desc]").val("");
        $("#order_resume #service_desc").val("");
        $("#new_order input[name=service_price]").val("");
        $("#new_order input[name=service_min]").val("");
        $("#new_order input[name=service_max]").val("");
      }
      var element = $(this);
      var id = element.val();
      if (id == "") {
        return;
      }
      var url = element.data("url") + id;
      var data = $.param({ token: token });
      $.post(url, data, function (_result) {
        setTimeout(function () {
          $("#result_onChange").html(_result);
        }, 100);
      });
    });

    $(document).on("change", ".ajaxChangeService", function () {
      event.preventDefault();
      var _that = $(this);
      var _id = _that.val();
      var _dripfeed = _that.children("option:selected").data("dripfeed");
      var _service_type = _that.children("option:selected").data("type");

      $("#new_order .order-default-quantity input[name=quantity]").attr(
        "disabled",
        false
      );
      $("#new_order .order-usernames-custom").addClass("d-none");
      $("#new_order .order-comments-custom-package").addClass("d-none");

      /*----------  reset quantity  ----------*/
      $("#new_order input[name=service_price]").val();
      $("#new_order input[name=service_min]").val();
      $("#new_order input[name=service_max]").val();

      $("#new_order .order-default-quantity input[name=quantity]").val("");
      var _total_charge = 0;
      var _currency_symbol = $("#new_order input[name=currency_symbol]").val();
      $("#new_order input[name=total_charge]").val(_total_charge);
      $("#new_order .total_charge span").html(_currency_symbol + _total_charge);
      switch (_service_type) {
        case "subscriptions":
          $("#new_order input[name=sub_expiry]").val("");

          // Disable Schedule
          $(".schedule-option").addClass("d-none");

          $("#new_order .order-default-link").addClass("d-none");
          $("#new_order .order-default-quantity").addClass("d-none");
          $("#new_order #result_total_charge").addClass("d-none");

          $("#new_order .order-comments").addClass("d-none");
          $("#new_order .order-usernames").addClass("d-none");
          $("#new_order .order-hashtags").addClass("d-none");
          $("#new_order .order-username").addClass("d-none");
          $("#new_order .order-hashtag").addClass("d-none");
          $("#new_order .order-media").addClass("d-none");

          $("#new_order .order-subscriptions").removeClass("d-none");
          break;

        case "custom_comments":
          $("#new_order .order-default-link").removeClass("d-none");
          $("#new_order .order-comments").removeClass("d-none");
          $("#new_order #result_total_charge").removeClass("d-none");

          $("#new_order .order-usernames").addClass("d-none");
          $("#new_order .order-hashtags").addClass("d-none");
          $("#new_order .order-username").addClass("d-none");
          $("#new_order .order-hashtag").addClass("d-none");
          $("#new_order .order-media").addClass("d-none");

          $("#new_order .order-default-quantity").removeClass("d-none");
          $("#new_order .order-default-quantity input[name=quantity]").attr(
            "disabled",
            true
          );

          $("#new_order .order-subscriptions").addClass("d-none");
          break;

        case "custom_comments_package":
          $("#new_order .order-default-link").removeClass("d-none");
          $("#new_order .order-comments-custom-package").removeClass("d-none");
          $("#new_order #result_total_charge").removeClass("d-none");

          $("#new_order .order-comments").addClass("d-none");
          $("#new_order .order-default-quantity").addClass("d-none");
          $("#new_order .order-usernames").addClass("d-none");
          $("#new_order .order-hashtags").addClass("d-none");
          $("#new_order .order-username").addClass("d-none");
          $("#new_order .order-hashtag").addClass("d-none");
          $("#new_order .order-media").addClass("d-none");
          $("#new_order .order-subscriptions").addClass("d-none");
          break;

        case "mentions_with_hashtags":
          $("#new_order .order-default-link").removeClass("d-none");
          $("#new_order .order-default-quantity").removeClass("d-none");
          $("#new_order .order-usernames").removeClass("d-none");
          $("#new_order .order-hashtags").removeClass("d-none");
          $("#new_order #result_total_charge").removeClass("d-none");

          $("#new_order .order-comments").addClass("d-none");
          $("#new_order .order-username").addClass("d-none");
          $("#new_order .order-hashtag").addClass("d-none");
          $("#new_order .order-media").addClass("d-none");

          $("#new_order .order-subscriptions").addClass("d-none");

          break;

        case "mentions_custom_list":
          $("#new_order .order-default-link").removeClass("d-none");
          $("#new_order .order-usernames-custom").removeClass("d-none");
          $("#new_order #result_total_charge").removeClass("d-none");
          $("#new_order .order-default-quantity").removeClass("d-none");
          $("#new_order .order-default-quantity input[name=quantity]").attr(
            "disabled",
            true
          );

          $("#new_order .order-usernames").addClass("d-none");
          $("#new_order .order-comments").addClass("d-none");
          $("#new_order .order-username").addClass("d-none");
          $("#new_order .order-hashtags").addClass("d-none");
          $("#new_order .order-hashtag").addClass("d-none");
          $("#new_order .order-media").addClass("d-none");

          $("#new_order .order-subscriptions").addClass("d-none");

          break;

        case "mentions_hashtag":
          $("#new_order .order-default-link").removeClass("d-none");
          $("#new_order .order-default-quantity").removeClass("d-none");
          $("#new_order .order-hashtag").removeClass("d-none");
          $("#new_order #result_total_charge").removeClass("d-none");

          $("#new_order .order-comments").addClass("d-none");
          $("#new_order .order-usernames").addClass("d-none");
          $("#new_order .order-hashtags").addClass("d-none");
          $("#new_order .order-username").addClass("d-none");
          $("#new_order .order-media").addClass("d-none");
          $("#new_order .order-subscriptions").addClass("d-none");

          break;

        case "mentions_user_followers":
          $("#new_order .order-default-link").removeClass("d-none");
          $("#new_order .order-default-quantity").removeClass("d-none");
          $("#new_order .order-username").removeClass("d-none");
          $("#new_order #result_total_charge").removeClass("d-none");

          $("#new_order .order-comments").addClass("d-none");
          $("#new_order .order-usernames").addClass("d-none");
          $("#new_order .order-hashtags").addClass("d-none");
          $("#new_order .order-hashtag").addClass("d-none");
          $("#new_order .order-media").addClass("d-none");
          $("#new_order .order-subscriptions").addClass("d-none");
          break;

        case "mentions_media_likers":
          $("#new_order .order-default-link").removeClass("d-none");
          $("#new_order .order-default-quantity").removeClass("d-none");
          $("#new_order .order-media").removeClass("d-none");
          $("#new_order #result_total_charge").removeClass("d-none");

          $("#new_order .order-comments").addClass("d-none");
          $("#new_order .order-usernames").addClass("d-none");
          $("#new_order .order-hashtags").addClass("d-none");
          $("#new_order .order-username").addClass("d-none");
          $("#new_order .order-hashtag").addClass("d-none");
          $("#new_order .order-subscriptions").addClass("d-none");

          break;

        case "package":
          $("#new_order .order-default-link").removeClass("d-none");
          $("#new_order #result_total_charge").removeClass("d-none");

          $("#new_order .order-default-quantity").addClass("d-none");
          $("#new_order .order-comments").addClass("d-none");
          $("#new_order .order-usernames").addClass("d-none");
          $("#new_order .order-hashtags").addClass("d-none");
          $("#new_order .order-username").addClass("d-none");
          $("#new_order .order-hashtag").addClass("d-none");
          $("#new_order .order-media").addClass("d-none");
          $("#new_order .order-subscriptions").addClass("d-none");

          break;

        case "comment_likes":
          $("#new_order .order-default-link").removeClass("d-none");
          $("#new_order .order-default-quantity").removeClass("d-none");
          $("#new_order .order-username").removeClass("d-none");
          $("#new_order #result_total_charge").removeClass("d-none");

          $("#new_order .order-comments").addClass("d-none");
          $("#new_order .order-usernames").addClass("d-none");
          $("#new_order .order-hashtags").addClass("d-none");
          $("#new_order .order-hashtag").addClass("d-none");
          $("#new_order .order-media").addClass("d-none");
          $("#new_order .order-subscriptions").addClass("d-none");
          break;

        default:
          $("#new_order .order-default-link").removeClass("d-none");
          $("#new_order .order-default-quantity").removeClass("d-none");
          $("#new_order #result_total_charge").removeClass("d-none");

          $("#new_order .order-comments").addClass("d-none");
          $("#new_order .order-usernames").addClass("d-none");
          $("#new_order .order-hashtags").addClass("d-none");
          $("#new_order .order-username").addClass("d-none");
          $("#new_order .order-hashtag").addClass("d-none");
          $("#new_order .order-media").addClass("d-none");

          $("#new_order .order-subscriptions").addClass("d-none");

          break;
      }

      if (_dripfeed) {
        $("#new_order .drip-feed-option").removeClass("d-none");
      } else {
        $("#new_order .drip-feed-option").addClass("d-none");
      }

      var _action = _that.data("url") + _id;
      var _data = $.param({ token: token });
      $.post(_action, _data, function (_result) {
        $("#result_onChangeService").html(_result);
        // display min-max on Mobile Reponsive
        var _service_price = $("#order_resume input[name=service_price]").val();
        var _service_min = $("#order_resume input[name=service_min]").val();
        var _service_max = $("#order_resume input[name=service_max]").val();
        $("#new_order input[name=service_price]").val(_service_price);
        $("#new_order input[name=service_min]").val(_service_min);
        $("#new_order input[name=service_max]").val(_service_max);

        setTimeout(function () {
          if (
            _service_type == "package" ||
            _service_type == "custom_comments_package"
          ) {
            _total_charge = _service_price;
            _currency_symbol = $(
              "#new_order input[name=currency_symbol]"
            ).val();
            $("#new_order input[name=total_charge]").val(_total_charge);
            $("#new_order .total_charge span").html(
              _currency_symbol + _total_charge
            );
          }
        }, 100);
      });
    });
  };

  this.CalculateOrderCharge = function () {
    // callback ajax_custom_comments
    $(document).on("keyup", ".ajax_custom_comments", function () {
      var _comments = $(
        "#new_order .order-comments textarea[name=comments]"
      ).val();
      if (_comments == "") {
        var _quantity = 0;
      } else {
        var _quantity = 0;
        $.each(_comments.split("\n"), function (e, t) {
          if ($.trim(t).length > 0) {
            _quantity++;
          }
        });
      }
      var _service_id = $("#service_id").val();
      $("#new_order .order-default-quantity input[name=quantity]").val(
        _quantity
      );
      var _service_max = $("#order_resume input[name=service_max]").val();
      var _service_min = $("#order_resume input[name=service_min]").val();
      var _service_price = $("#order_resume input[name=service_price]").val();

      var _total_charge =
        _quantity != "" && _service_price != ""
          ? (_quantity * _service_price) / 1000
          : 0;
      _total_charge = preparePrice(_total_charge);
      var _currency_symbol = $("#new_order input[name=currency_symbol]").val();
      $("#new_order input[name=total_charge]").val(_total_charge);
      $("#new_order .total_charge span").html(_currency_symbol + _total_charge);
    });

    // callback ajax_custom_lists
    $(document).on("keyup", ".ajax_custom_lists", function () {
      var _quantity = $(
        "#new_order .order-usernames-custom textarea[name=usernames_custom]"
      ).val();
      if (_quantity == "") {
        var _quantity = 0;
      } else {
        var _quantity = _quantity.split("\n").length;
      }

      var _service_id = $("#service_id").val();
      $("#new_order .order-default-quantity input[name=quantity]").val(
        _quantity
      );
      var _service_max = $("#order_resume input[name=service_max]").val();
      var _service_min = $("#order_resume input[name=service_min]").val();
      var _service_price = $("#order_resume input[name=service_price]").val();

      var _total_charge =
        _quantity != "" && _service_price != ""
          ? (_quantity * _service_price) / 1000
          : 0;
      _total_charge = preparePrice(_total_charge);
      var _currency_symbol = $("#new_order input[name=currency_symbol]").val();
      $("#new_order input[name=total_charge]").val(_total_charge);
      $("#new_order .total_charge span").html(_currency_symbol + _total_charge);
    });
  };

  this.General = function () {
    /*----------  View User/back to admin----------*/
    $(document).on("click", ".ajaxViewUser", function () {
      event.preventDefault();
      pageOverlay.show();
      var element = $(this),
        url = element.attr("href"),
        data = $.param({ token: token });
      callPostAjax(element, url, data, "");
    });

    // Insert hyper-link
    $(document).on("focusin", function (e) {
      if ($(event.target).closest(".mce-window").length) {
        e.stopImmediatePropagation();
      }
    });

    // load ajax-Modal
    $(document).on("click", ".ajaxModal", function () {
      var element = $(this);
      var url = element.attr("href");
      $("#modal-ajax").load(url, function () {
        $("#modal-ajax").modal({
          backdrop: "static",
          keyboard: false,
        });
        $("#modal-ajax").modal("show");
      });
      return false;
    });

    /*----------  ajaxChangeTicketSubject  ----------*/
    $(document).on("change", ".ajaxChangeTicketSubject", function () {
      event.preventDefault();
      var element = $(this);
      var type = element.val();
      switch (type) {
        case "subject_order":
          $("#add_new_ticket .subject-order").removeClass("d-none");
          $("#add_new_ticket .subject-payment").addClass("d-none");
          break;

        case "subject_payment":
          $("#add_new_ticket .subject-order").addClass("d-none");
          $("#add_new_ticket .subject-payment").removeClass("d-none");
          break;

        default:
          $("#add_new_ticket .subject-order").addClass("d-none");
          $("#add_new_ticket .subject-payment").addClass("d-none");
          break;
      }
    });

    // ajaxChangeLanguage (footer top)
    $(document).on("change", ".ajaxChangeLanguage", function () {
      event.preventDefault();
      var element = $(this);
      var pathname =
        element.data("url") +
        "?" +
        "ids=" +
        element.val() +
        "&" +
        "redirect=" +
        element.data("redirect");
      window.location.href = pathname;
    });

    // ajaxChangeLanguageSecond (header top)
    $(document).on("click", ".ajaxChangeLanguageSecond", function () {
      event.preventDefault();
      var element = $(this);
      var pathname =
        element.data("url") +
        "?" +
        "ids=" +
        element.data("ids") +
        "&" +
        "redirect=" +
        element.data("redirect");
      window.location.href = pathname;
    });

    // callback ajaxChange
    $(document).on("change", ".ajaxChange", function () {
      pageOverlay.show();
      event.preventDefault();
      var element = $(this);
      var id = element.val();
      if (id == "") {
        pageOverlay.hide();
        return false;
      }
      var url = element.data("url") + id;
      var data = $.param({ token: token });
      $.post(url, data, function (_result) {
        pageOverlay.hide();
        setTimeout(function () {
          $("#result_ajaxSearch").html(_result);
          tableWrapper = document.querySelectorAll(".table-responsive");
          collapseMenu = document.querySelectorAll(".card-header--toggle");
          card = document.querySelectorAll(".card");
          modal = document.querySelectorAll(".modal");
          modalButton = document.querySelectorAll(
            'button[data-toggle="modal"]'
          );
          if (tableWrapper) {
            initTableWrapper();
            initModal();
          }
        }, 100);
      });
    });

    // callback ajaxSearch
    $(document).on("submit", ".ajaxSearchItem", function () {
      pageOverlay.show();
      event.preventDefault();
      var _that = $(this),
        _action = _that.attr("action"),
        _data = _that.serialize();

      _data = _data + "&" + $.param({ token: token });
      $.post(_action, _data, function (_result) {
        setTimeout(function () {
          pageOverlay.hide();
          $("#result_ajaxSearch").html(_result);
        }, 300);
      });
    });

    // callback ajaxSearchItemsKeyUp with keyup and Submit from
    var typingTimer; //timer identifier
    $(document).on("keyup", ".ajaxSearchItemsKeyUp", function () {
      $(window).keydown(function (event) {
        if (event.keyCode == 13) {
          event.preventDefault();
          return false;
        }
      });
      event.preventDefault();
      clearTimeout(typingTimer);
      $(".ajaxSearchItemsKeyUp .btn-searchItem").addClass("btn-loading");
      var _that = $(this),
        _form = _that.closest("form"),
        _action = _form.attr("action"),
        _data = _form.serialize();
      _data = _data + "&" + $.param({ token: token });

      // if ( $("input:text").val().length < 2 ) {
      //     $(".ajaxSearchItemsKeyUp .btn-searchItem").removeClass("btn-loading");
      //     return;
      // }

      typingTimer = setTimeout(function () {
        $.post(_action, _data, function (_result) {
          setTimeout(function () {
            $(".ajaxSearchItemsKeyUp .btn-searchItem").removeClass(
              "btn-loading"
            );
            $("#result_ajaxSearch").html(_result);
          }, 10);
        });
      }, 1500);
    });

    $(document).on("submit", ".ajaxSearchItemsKeyUp", function () {
      event.preventDefault();
    });

    // callback actionForm
    $(document).on("submit", ".actionForm", function () {
      pageOverlay.show();
      event.preventDefault();
      var _that = $(this),
        _action = _that.attr("action"),
        _redirect = _that.data("redirect");
      if ($("#mass_order").hasClass("active")) {
        var _data = $("#mass_order")
          .find("input[name!=mass_order]")
          .serialize();
        var _mass_order_array = [];
        var _mass_orders = $("#mass_order")
          .find("textarea[name=mass_order]")
          .val();
        if (_mass_orders.length > 0) {
          _mass_orders = _mass_orders.split(/\n/);
          for (var i = 0; i < _mass_orders.length; i++) {
            // only push this line if it contains a non whitespace character.
            if (/\S/.test(_mass_orders[i])) {
              _mass_order_array.push($.trim(_mass_orders[i]));
            }
          }
        }
        var _data =
          _data +
          "&" +
          $.param({ mass_order: _mass_order_array, token: token });
      } else {
        var _token = _that.find("input[name=token]").val();
        var _data = _that.serialize();
        if (typeof _token == "undefined") {
          _data = _data + "&" + $.param({ token: token });
        }
      }

      $.post(_action, _data, function (_result) {
        setTimeout(function () {
          pageOverlay.hide();
        }, 1500);

        if (is_json(_result)) {
          _result = JSON.parse(_result);
          setTimeout(function () {
            const submitButton = document.querySelectorAll(".btn-primary");
            if (submitButton) {
              submitButton.forEach((item) => {
                item.removeAttribute("disabled");
              });
            }
            notify(_result.message, _result.status);
          }, 1500);
          setTimeout(function () {
            if (
              _result.status == "success" &&
              typeof _redirect != "undefined"
            ) {
              reloadPage(_redirect);
            }
          }, 2000);
        } else {
          setTimeout(function () {
            $("#result_notification").html(_result);
          }, 1500);
        }
      });
      return false;
    });

    // actionFormWithoutToast
    $(document).on("submit", ".actionFormWithoutToast", function () {
      alertMessage.hide();
      event.preventDefault();
      var _that = $(this),
        _action = _that.attr("action"),
        _data = _that.serialize();
      _data = _data + "&" + $.param({ token: token });
      var _redirect = _that.data("redirect");
      _that.find(".btn-submit").addClass("btn-loading");
      $.post(_action, _data, function (_result) {
        if (is_json(_result)) {
          _result = JSON.parse(_result);
          setTimeout(function () {
            alertMessage.show(_result.message, _result.status);
          }, 1500);

          setTimeout(function () {
            if (
              _result.status == "success" &&
              typeof _redirect != "undefined"
            ) {
              reloadPage(_redirect);
            }
          }, 2000);
        } else {
          setTimeout(function () {
            $("#resultActionForm").html(_result);
          }, 1500);
        }

        setTimeout(function () {
          _that.find(".btn-submit").removeClass("btn-loading");
        }, 1500);
      });
      return false;
    });
  };
}

// =-=-=-=-=-=-=-=-=-=-=-=- <Chart> -=-=-=-=-=-=-=-=-=-=-=-=

const htmlLegendPlugin = {
  id: "htmlLegend",
  afterUpdate(chart, args, options) {
    const ul = getOrCreateLegendList(chart, options.containerID);

    while (ul.firstChild) {
      ul.firstChild.remove();
    }

    const items = chart.options.plugins.legend.labels.generateLabels(chart);

    items.forEach((item) => {
      const li = document.createElement("li");
      li.classList.add("chart-legend-li");
      if (item.hidden) li.classList.add("_disabled");

      li.onclick = () => {
        chart.setDatasetVisibility(
          item.datasetIndex,
          !chart.isDatasetVisible(item.datasetIndex)
        );
        chart.update();
      };

      const boxSpan = document.createElement("span");
      boxSpan.classList.add("chart-legend-box");
      boxSpan.style.setProperty("--color", item.fillStyle);

      const textContainer = document.createElement("span");
      textContainer.classList.add("chart-legend-text");

      const text = document.createTextNode(item.text);
      textContainer.appendChild(text);

      li.appendChild(boxSpan);
      li.appendChild(textContainer);
      ul.appendChild(li);
    });
  },
};

const getOrCreateLegendList = (chart, id) => {
  const legendContainer = document.getElementById(id);
  let listContainer = legendContainer.querySelector("ul");

  if (!listContainer) {
    listContainer = document.createElement("ul");
    listContainer.classList.add("chart-legend-list");

    legendContainer.appendChild(listContainer);
  }

  return listContainer;
};

const ctx = document.querySelector("#statistics-chart");

function dataChart(arg) {
  let result = {
    labels: arg.labels,
    datasets: [],
  };
  for (let i = 0; i < arg.data.length; i++) {
    result.datasets.push({
      label: arg.data[i].label,
      data: arg.data[i].data,

      backgroundColor: [arg.data[i].color],
      borderColor: [arg.data[i].color],
      borderWidth: 4,

      pointRadius: 4,
      pointBackgroundColor: "rgba(0,0,0,0)",
      pointBorderWidth: 0,
      pointHoverBackgroundColor: arg.data[i].color,
      hidden: arg.data[i].hidden,

      cubicInterpolationMode: "monotone",
    });
  }

  return result;
}

let data = dataChart({
  labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov"],
  data: [
    {
      label: "Completed",
      data: [0, 100, 250, 400, 300, 500, 220, 230],
      color: "rgba(187, 57, 188, 1)",
    },
    {
      label: "Processing",
      data: [0, 220, 350, 200, 500, 400, 320, 330],
      color: "rgba(244, 183, 64, 1)",
      hidden: true,
    },
    {
      label: "Pending",
      data: [0, 150, 250, 190, 400, 300, 420, 430],
      color: "rgba(162, 107, 0, 1)",
      hidden: true,
    },
    {
      label: "In progress",
      data: [0, 100, 210, 130, 230, 500, 320, 230],
      color: "rgba(0, 186, 136, 1)",
      hidden: true,
    },
    {
      label: "Partial",
      data: [0, 50, 110, 230, 330, 200, 420, 500],
      color: "rgba(28, 150, 238, 1)",
    },
    {
      label: "Canceled",
      data: [0, 70, 130, 190, 270, 300, 490, 300],
      color: "rgba(255, 76, 156, 1)",
      hidden: true,
    },
    {
      label: "Refunded",
      data: [0, 20, 300, 160, 270, 300, 500, 400],
      color: "rgba(195, 0, 82, 1)",
      hidden: true,
    },
  ],
});

try {
  const chart = new Chart(ctx.getContext("2d"), {
    type: "line",
    data: data,
    options: {
      responsive: true,
      interaction: {
        mode: "index",
        intersect: false,
      },
      scales: {
        y: {
          grid: {
            borderColor: "rgba(0,0,0,0)",
            borderDash: [5],
            color: "rgba(78, 75, 102, 0.5)",
          },
          ticks: {
            stepSize: 100,
          },
        },
        x: {
          grid: {
            display: false,
            borderColor: "rgba(0,0,0,0)",
          },
        },
      },
      plugins: {
        htmlLegend: {
          containerID: "legend-container",
        },
        legend: {},
      },
    },
    plugins: [htmlLegendPlugin],
  });
} catch {}
display: false,
  // =-=-=-=-=-=-=-=-=-=-=-=- </Chart> -=-=-=-=-=-=-=-=-=-=-=-=

  (General = new General());
$(function () {
  General.init();
});

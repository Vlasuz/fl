<style type="text/css">
  .order_btn_group .list-inline-item {
    margin-right: 0px !important;
  }

  .order_btn_group .list-inline-item a.btn {
    font-size: 0.9rem;
    font-weight: 400;
  }
</style>

<section class="order section pe-1 pe-lg-3">
  <div class="order__block">
    <div class="order__block--body _section-block">
      <div class="order__block--header _section-block-header">
        <div class="order__block--header-body d-flex flex-column">
          <h2 class="order__block--title _title ps-md-4 pt-2 pb-4">
            Order logs
          </h2>
          <div class="order__block--nav order__nav">
            <form action="#" class="order__nav--search-form order__search-form ps-2 pe-2 ps-sm-4 pe-sm-4 pb-2">
              <label class="order__search-form--label _form-label">
                <span class="order__search-form--label-body _form-elem-wrapper">
                  <span class="order__search-form--icon _form-icon _icon-close _clear-input-btn"></span>
                  <input type="text" name="search" placeholder="Search" class="order__search-form--input _form-input _form-elem">
                </span>
              </label>
              <label class="order__search-form--label _form-label">
                <span class="order__search-form--label-body _form-elem-wrapper">
                  <span class="order__search-form--icon _form-icon _icon-arrow"></span>
                  <select name="category" class="order__search-form--select _form-select _form-elem">
                    <option value="order-id" selected>Order ID</option>
                    <option value="api-order-id">API Order ID</option>
                    <option value="order-link">Order Link</option>
                    <option value="user-email">User Email</option>
                  </select>
                </span>
              </label>
              <label class="order__search-form--label _form-label">
                <button type="submit" class="order__search-form--submit _form-btn _form-elem _icon-search" title="Search">
                  <svg fill="#a0a3bd" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30" width="20px" height="20px">
                    <path d="M 13 3 C 7.4889971 3 3 7.4889971 3 13 C 3 18.511003 7.4889971 23 13 23 C 15.396508 23 17.597385 22.148986 19.322266 20.736328 L 25.292969 26.707031 A 1.0001 1.0001 0 1 0 26.707031 25.292969 L 20.736328 19.322266 C 22.148986 17.597385 23 15.396508 23 13 C 23 7.4889971 18.511003 3 13 3 z M 13 5 C 17.430123 5 21 8.5698774 21 13 C 21 17.430123 17.430123 21 13 21 C 8.5698774 21 5 17.430123 5 13 C 5 8.5698774 8.5698774 5 13 5 z" />
                  </svg>
                </button>
              </label>
            </form>
            <div class="order__container">
              <ul class="order__nav--list row ps-2 pe-2">
                <li class="order__nav--item col-3 col-md-auto">
                  <a href="#" class="order__nav--link _link">
                    All
                  </a>
                </li>
                <li class="order__nav--item col-3 col-md-auto">
                  <a href="#" class="order__nav--link _link _active">
                    Pending
                  </a>
                </li>
                <li class="order__nav--item col col-md-auto">
                  <a href="#" class="order__nav--link _link">
                    Processing
                  </a>
                </li>
                <li class="order__nav--item col col-md-auto">
                  <a href="#" class="order__nav--link _link">
                    In progress
                  </a>
                </li>
                <li class="order__nav--item col col-md-auto">
                  <a href="#" class="order__nav--link _link">
                    Completed
                  </a>
                </li>
                <li class="order__nav--item col col-md-auto">
                  <a href="#" class="order__nav--link _link">
                    Partial
                  </a>
                </li>
                <li class="order__nav--item col col-md-auto">
                  <a href="#" class="order__nav--link _link">
                    Canceled
                  </a>
                </li>
                <li class="order__nav--item col col-md-auto">
                  <a href="#" class="order__nav--link _link">
                    Refunded
                  </a>
                </li>
                <li class="order__nav--item col col-md-auto">
                  <a href="#" class="order__nav--link _link">
                    Awaiting
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="order__block--table order__table _scrollbar-styles">
        <div class="order__table--body">
          <div class="order__table--head order__head-table ps-4 pt-3">
            <div class="order__head-table--id">
              ID
            </div>
            <div class="order__head-table--api">
              API Order
            </div>
            <div class="order__head-table--user">
              User
            </div>
            <div class="order__head-table--details">
              Order basic details
            </div>
            <div class="order__head-table--created">
              Created
            </div>
            <div class="order__head-table--status">
              Status
            </div>
            <div class="order__head-table--action">
              Action
            </div>
          </div>
          <div class="order__table--body order__body-table">
            <div class="order__body-table--item pt-3 pb-3 ps-4">
              <div class="order__body-table--id order__id">
                1
              </div>
              <div class="order__body-table--api order__api">
                Soft UI Shopify Version
              </div>
              <div class="order__body-table--user order__user">
                Mike
              </div>
              <div class="order__body-table--details order__details">
                <span class="order__details--icon _icon-followers me-3"></span>
                <div class="order__details--block">
                  <span class="order__details--name">
                    Instagram followers
                  </span>
                  <ul class="order__details--list">
                    <li class="order__details--li">
                      info
                    </li>
                    <li class="order__details--li">
                      info
                    </li>
                    <li class="order__details--li">
                      info
                    </li>
                    <li class="order__details--li">
                      info
                    </li>
                    <li class="order__details--li">
                      info
                    </li>
                  </ul>
                </div>
              </div>
              <div class="order__body-table--created order__created">
                $14,000
              </div>
              <div class="order__body-table--status order__status">
                <span class="order__status--elem _status-1">
                  Status
                </span>
              </div>
              <div class="order__body-table--action order__action">
                <a href="#" class="order__action--link _link">
                  <span class="order__action--icon _icon-edit"></span>
                </a>
              </div>
            </div>
            <div class="order__body-table--item pt-3 pb-3 ps-4">
              <div class="order__body-table--id order__id">
                4
              </div>
              <div class="order__body-table--api order__api">
                Launch new Mobile App
              </div>
              <div class="order__body-table--user order__user">
                Mike
              </div>
              <div class="order__body-table--details order__details">
                <span class="order__details--icon _icon-likes me-3 mt-1"></span>
                <div class="order__details--block">
                  <span class="order__details--name">
                    Instagram Likes
                  </span>
                  <ul class="order__details--list">
                    <li class="order__details--li">
                      info
                    </li>
                    <li class="order__details--li">
                      info
                    </li>
                    <li class="order__details--li">
                      info
                    </li>
                    <li class="order__details--li">
                      info
                    </li>
                    <li class="order__details--li">
                      info
                    </li>
                  </ul>
                </div>
              </div>
              <div class="order__body-table--created order__created">
                $20,600
              </div>
              <div class="order__body-table--status order__status">
                <span class="order__status--elem _status-2">
                  Status
                </span>
              </div>
              <div class="order__body-table--action order__action">
                <a href="#" class="order__action--link _link">
                  <span class="order__action--icon _icon-edit"></span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</section>

<?php
$show_search_area = show_search_area($controller_name, $params, 'user');
?>
<!-- <div class="page-title m-b-20">
  <div class="row justify-content-between">
    <div class="col-md-2">
      <h1 class="page-title">
          <span class="fe fe-calendar"></span> <?= lang("order_logs") ?>
      </h1>
    </div>
    <div class="col-md-4">
      <div class="d-flex">
        <a href="<?= cn("order/new_order") ?>" class="ml-auto btn btn-outline-primary">
          <span class="fe fe-plus"></span>
            <?= lang("add_new") ?>
        </a>
      </div>
    </div>
    <div class="col-md-12">
      <div class="row justify-content-between">
        <div class="col-md-10">
          <ul class="list-inline mb-0 order_btn_group">
            <li class="list-inline-item"><a class="nav-link btn <?= ($params['filter']['status'] == 'all') ? 'btn-info' : '' ?>" href="<?= cn($controller_name) ?>"><?= lang('All') ?></a></li>
            <?php
            if (!empty($order_status_array)) {
              foreach ($order_status_array as $row_status) {
            ?>
              <li class="list-inline-item">
                <a class="nav-link btn <?= ($params['filter']['status'] == $row_status) ? 'btn-info' : '' ?>" href="<?= cn($controller_name . "?status=" . $row_status) ?>"><?= order_status_title($row_status) ?>
                </a>
              </li>
            <?php }
            } ?>
          </ul>
        </div>
        <div class="col-md-2">
          <div class="d-flex search-area">
            <?php echo $show_search_area; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> -->
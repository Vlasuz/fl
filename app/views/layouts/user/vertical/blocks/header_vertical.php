<style>
  .search-box div.form-group {
    margin-bottom: 0px !important;
  }

  .search-box .form-control {
    height: auto !important;
  }
</style>

<header class="navbar navbar-expand-xl js-header">
  <div class="header-wrap">

    <ul class="section-header__list pb-lg-0 pt-lg-0 pb-3 pt-3">
      <li class="section-header__list--item">
        <a href="/order" class="section-header__list--link _accent">
          <span class="section-header__list--icon _icon-section-new-order"></span>
          New order
        </a>
      </li>
      <li class="section-header__list--item">
        <a href="#" class="section-header__list--link">
          <span class="section-header__list--icon _icon-orders"></span>
          All orders
        </a>
      </li>
    </ul>

    <ul class="nav navbar-menu align-items-center order-1 order-lg-2">
      <?php
      if (session('uid_tmp')) {
      ?>
        <li class="nav-item">
          <a class="nav-link ajaxViewUser" href="<?= cn("back-to-admin") ?>">
            <span class="nav-icon">
              <i class="icon fe fe-log-out" data-toggle="tooltip" data-placement="bottom" title="<?= lang('Back_to_Admin') ?>"></i>
            </span>
          </a>
        </li>
      <?php } ?>

      <li class="nav-item d-none d-none">
        <a class="nav-link" href="#customize" data-toggle="modal">
          <span class="nav-icon">
            <i class="icon fe fe-sliders" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang('Theme_Customizer'); ?>"></i>
          </span>
        </a>
      </li>

      <?php
      if (get_option("enable_news_announcement") &&  get_option('news_announcement_button_position', "header") == 'header') {
      ?>
        <li class="nav-item notifcation">
          <a class="nav-link ajaxModal" href="<?= cn("news-annoucement") ?>">
            <span class="nav-icon">
              <i class="icon fe fe-bell" data-toggle="tooltip" data-placement="bottom" title="<?= lang("news__announcement") ?>"></i>
              <div class="test">
                <span class="nav-unread <?= (isset($_COOKIE["news_annoucement"]) && $_COOKIE["news_annoucement"] == "clicked") ? "" : "change_color" ?>"></span>
              </div>
            </span>
          </a>
        </li>
      <?php } ?>

      <?php
      $redirect = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      $this->load->model('model');
      $items_languages = $this->model->fetch('id, ids, country_code, code, is_default', LANGUAGE_LIST, ['status' => 1]);
      $lang_current = get_lang_code_defaut();
      ?>
      <li class="nav-item dropdown-lang dropdown">
        <a class="dropdown-toggle d-flex align-items-center" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
          <span class="flag-icon flag-icon-<?php echo strtolower($lang_current->country_code); ?>"></span>
          <p class="dropdown__text">
            English
          </p>
        </a>
        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
          <?php
          foreach ($items_languages as $key => $item) {
          ?>
            <a class="dropdown-item ajaxChangeLanguageSecond" href="javascript:void(0)" data-url="<?php echo cn('set-language'); ?>" data-redirect="<?php echo strip_tags($redirect); ?>" data-ids="<?php echo strip_tags($item->ids); ?>"><i class="flag-icon flag-icon-<?php echo strtolower($item->country_code); ?>"></i> <?php echo language_codes($item->code); ?>
            </a>
          <?php } ?>
        </div>
      </li>

      <ul class="header__list header__list--header pl-3">
        <li class="header__item">
          <a href="#" class="section-header__theme--link _theme-link _light-theme section-header__min-link" title="Change theme">
            <span class="section-header__theme--icon section-header__min-icon _icon-theme-to-dark"></span>
          </a>
        </li>
        <li class="header__item">
          <a href="#" class="section-header__message--link section-header__min-link _has-effect" title="Messages">
            <span class="section-header__message--icon section-header__min-icon _icon-message"></span>
          </a>
        </li>
      </ul>

      <li class="nav-item dropdown">
        <a href="#" data-toggle="dropdown" class="nav-link d-flex align-items-center py-0 px-lg-0 px-2 text-color ml-5">
          <span class="ml-2 leading-none">
            <span class="mt-1 section-header__user--name"><?php echo lang('Hi,'); ?> <span class=""><?php echo current_logged_user()->first_name; ?></span>!</span>
            <small class="badge bg-indigo d-block mt-1">
              Balance:
              <?php
              $balance = current_logged_user()->balance;
              if (empty($balance) || $balance == 0) {
                $balance = 0.00;
              } else {
                $balance = currency_format($balance);
              }
              echo get_option('currency_symbol', "$") . $balance;
              ?>
            </small>
          </span>
          <span class="avatar admin-profile m-l-10"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
          <a class="dropdown-item" href="<?php echo cn('profile'); ?>">
            <i class="icon fe fe-user dropdown-icon"></i>
            <?php echo lang('Profile'); ?>
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="<?php echo cn('auth/logout'); ?>">
            <i class="icon fe fe-log-out dropdown-icon"></i>
            <?php echo lang('Logout'); ?>
          </a>
        </div>
      </li>
    </ul>
  </div>
</header>
<style>
  .tickets-number {
    font-size: 14px !important;
  }
</style>

<?php
$CI = &get_instance();
$CI->load->model('model');
$total_unread_tickets = $CI->model->count_results('id', TICKETS, ['user_read' => 1, 'uid' => session('uid')]);
$enable_item_api_menu = get_option('enable_api_tab');
?>

<?php
$sidebar_elements = app_config('controller')['user'];
$xhtml = '<ul class="navbar-nav mb-md-4" id="menu">';
foreach ($sidebar_elements as $key => $item) {
  $item_name = lang($item['name']);
  if ($item['area_title']) {
    $xhtml .= sprintf('<h6 class="navbar-heading first"><span class="text">%s</span></h6>', $item_name);
  } else {
    if ($key == 'api' && !$enable_item_api_menu) {
      continue;
    }

    $route_name = $item['route-name'];
    $class_active = ($route_name == segment(1)) ? 'active' : '';

    $xmtml_ticket_unread_numbers = null;
    if ($key == 'tickets') {
      $xmtml_ticket_unread_numbers = sprintf('<span class="ml-auto badge badge-warning ticket__count">%s</span>', $total_unread_tickets);
    }

    $xhtml .= sprintf(
      '<li class="nav-item">
          <a class="nav-link %s" href="%s" data-toggle="tooltip" data-placement="right" title="%s">
            <span class="nav-icon">
              <i class="%s"></i>
            </span>
            <span class="nav-text">
              %s
              %s
            </span>
          </a>
        </li>',
      $class_active,
      cn($route_name),
      $item_name,
      $item['icon'],
      $item_name,
      $xmtml_ticket_unread_numbers
    );
  }
}
$xhtml .= '</ul>';
?>
<aside class="navbar navbar-side navbar-fixed js-sidebar" id="aside">
  <div class="mobile-logo">
    <a href="<?php echo cn('statistics'); ?>" class="navbar-brand text-inherit">
      <img src="<?= get_option('website_logo', BASE . "assets/images/logo.png") ?>" alt="Website Logo" class="hide-navbar-folded navbar-brand-logo">
      <img src="<?= get_option('website_logo_mark', BASE . "assets/images/logo-mark.png") ?>" alt="Website Logo" class="hide-navbar-expanded navbar-brand-logo">
    </a>
  </div>
  <div class="flex-fill scroll-bar">
    <?= $xhtml ?>
  </div>

  <ul class="navbar-nav">
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
    <div class="sidebar__wrapper">
      <ul class="header__list pl-3">
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
    </div>
    <div class="user__wrapper">
      <img class="user__image" src="http://127.0.0.1:5500/img/user/avatar.png" alt="">
      <div class="user__block">
        <p class="user__name">Hi, Mark</p>
        <p class="user__balance">Balance: $1000</p>
        <a class="user__logout" href="<?php echo cn('auth/logout'); ?>">Log out</a>
      </div>
    </div>
  </ul>


</aside>
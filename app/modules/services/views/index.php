<?php
  $items_category = array_column($items_category, 'id', 'name');
  $items_category = array_flip(array_intersect_key($items_category, array_flip(array_keys($items))));
?>
<section class="page-title">
  <div class="row justify-content-between">
    <div class="col-md-7">
      <?php
        if (get_option("enable_explication_service_symbol")) {
      ?>
      <div class="btn-list">
        <span class="btn round btn-secondary ">⭐ = <?=lang("__good_seller")?></span>
        <span class="btn round btn-secondary ">⚡️ = <?=lang("__speed_level")?></span>
        <span class="btn round btn-secondary ">🔥 = <?=lang("__hot_service")?></span>
        <span class="btn round btn-secondary ">💎 = <?=lang("__best_service")?></span>
        <span class="btn round btn-secondary ">💧 = <?=lang("__drip_feed")?></span>
      </div>
      <?php } ?>
    </div>
  </div>
</section>
<div class="row m-t-5" id="result_ajaxSearch">
  <?php 
    if(!empty($items)){
      $data = array(
        "controller_name"     => $controller_name,
        "params"              => $params,
        "columns"             => $columns,
        "items"               => $items,
        "items_custom_rate"   => $items_custom_rate,
      );
      $this->load->view('child/index', $data);
    }else{
      echo show_empty_item();
    }
  ?>
</div>
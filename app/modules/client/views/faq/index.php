<div class="row faqs__page trueToggle" id="result_ajaxSearch">

  <div class="faqs__body">
    <div class="page-header">
      <h1 class="page-title">
        <span></span>
        <?= lang("FAQs") ?>
      </h1>
    </div>
    <?php if (!empty($items)) {
      foreach ($items as $key => $item) {
    ?>
        <div class="col-md-12 col-xl-12 tr_<?= $item['ids'] ?>">
          <div class="card card-collapsed faq__item--body">
            <div class="card-header card-header--toggle faqs__card-header">
              <h3 class="card-title" data-toggle="card-collapse">
                <span class="bg-question"></span>
                <?= $item['question'] ?>
              </h3>
              <div class="card-options">
                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
              </div>
            </div>
            <div class="card-body">
              <?= html_entity_decode($item['answer'], ENT_QUOTES) ?>
            </div>
          </div>
        </div>
    <?php }
    } else {
      echo show_empty_item();
    } ?>
  </div>
</div>
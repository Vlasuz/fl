<section class="page-title">
  <div class="row justify-content-between">
    <div class="col-md-6">

    </div>
    <div class="col-md-3">
    </div>
  </div>
</section>

<?php
$class_element = app_config('template')['form']['class_element'];
$form_subjects = [
  'subject_order'   => lang("Order"),
  'subject_payment' => lang("Payment"),
  'subject_service' => lang("Service"),
  'subject_other'   => lang("Other"),
];
$form_request = [
  'refill'         => lang("Refill"),
  'cancellation'   => lang("Cancellation"),
  'speed_up'       => lang("Speed_Up"),
  'other'          => lang("Other"),
];
$form_payments = [
  'paypal'         => lang("Paypal"),
  'stripe'         => lang("Cancellation"),
  'speed_up'       => lang("Stripe"),
  'other'          => lang("Other"),
];

$elements = [
  [
    'label'      => form_label(lang('Subject')),
    'element'    => form_dropdown('subject', $form_subjects, '', ['class' => $class_element . ' ajaxChangeTicketSubject']),
    'class_main' => "col-md-12 col-sm-12 col-xs-12",
  ],
  [
    'label'      => form_label(lang('Request')),
    'element'    => form_dropdown('request', $form_request, '', ['class' => $class_element]),
    'class_main' => "col-md-12 col-sm-12 col-xs-12 subject-order",
  ],
  [
    'label'      => form_label(lang('order_id')),
    'element'    => form_input(['name' => 'orderid', 'value' => '', 'placeholder' => lang("for_multiple_orders_please_separate_them_using_comma_example_123451234512345"), 'type' => 'text', 'class' => $class_element]),
    'class_main' => "col-md-12 col-sm-12 col-xs-12 subject-order",
  ],
  [
    'label'      => form_label(lang('Payment')),
    'element'    => form_dropdown('payment', $form_payments, '', ['class' => $class_element]),
    'class_main' => "col-md-12 col-sm-12 col-xs-12 subject-payment d-none",
  ],
  [
    'label'      => form_label(lang('Transaction_ID')),
    'element'    => form_input(['name' => 'transaction_id', 'value' => '', 'placeholder' => lang("enter_the_transaction_id"), 'type' => 'text', 'class' => $class_element]),
    'class_main' => "col-md-12 col-sm-12 col-xs-12 subject-payment d-none",
  ],
  [
    'label'      => form_label(lang("Description")),
    'element'    => form_textarea(['name' => 'description', 'value' => '', 'class' => $class_element]),
    'class_main' => "col-md-12 ticket__textarea",
  ],
];
$form_url     = cn($controller_name . "/store/");
$redirect_url = cn($controller_name);
$form_attributes = ['class' => 'form actionForm', 'data-redirect' => $redirect_url, 'method' => "POST"];
?>

<div class="row justify-content-between add__tickets">
  <div class="w-48 d-block">
    <div class="card">
      <div class="card-header ticket__card-header">
        <h3 class="card-title">
          <h4 class="modal-title"><?= lang("add_new_ticket") ?></h4>
        </h3>
        <div class="card-options">
          <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
          <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
        </div>
      </div>

      <div class="card-body o-auto ticket__card" style="height: calc(100vh - 180px);">
        <?php echo form_open($form_url, $form_attributes); ?>
        <div class="form-body" id="add_new_ticket">
          <div class="row justify-content-md-center">
            <?php echo render_elements_form($elements); ?>
            <div class="col-md-12 col-sm-12 col-xs-12 d-flex align-items-center justify-content-between">
              <h1 class="page-title d-flex ticket__status">
                <a href="<?= cn($controller_name . "/add") ?>" class="d-inline-block d-sm-none ajaxModal "><span class="add-new" data-toggle="tooltip" data-placement="bottom" title="<?= lang("add_new") ?>" data-original-title="Add new"></span></a>
                <!--        &nbsp;--><? //=lang("Tickets")
                                      ?>
                <?php
                if ($supportOnline) {
                  echo "<p style='font-size: 17px; line-height: 24px; font-weight: 600;'>Support <span style='color: green;'>online</span></p>";
                } else {
                  echo "<p style='font-size: 17px; line-height: 24px; font-weight: 600;'>Support <span style='color: red'>offline</span>, <br> time left <span>" . (round($timeLeft / 100)) . "H:" . ($timeLeft % 100) . "m</span></p>";
                }
                ?>
              </h1>
              <button type="submit" class="btn btn-primary _large-btn btn-min-width mr-1 mb-1"><?= lang('Submit') ?></button>
            </div>
          </div>
        </div>
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
  <div class="w-50">
    <div class="row" id="result_ajaxSearch">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header ticket__card-header">
            <h3 class="card-title"><?= lang("Lists") ?></h3>
            <div class="card-options">
              <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
              <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
            </div>
          </div>
          <div class="card-body o-auto ticket__card" style="height: calc(100vh - 180px);">
            <?php if (!empty($items)) { ?>
              <div class="ticket-lists">
                <?php
                foreach ($items as $key => $item) {
                  $this->load->view('child/index', ['controller_name' => $controller_name, 'item' => $item]);
                }
                ?>
              </div>
            <?php } else {
              echo show_empty_item();
            } ?>
          </div>
        </div>
      </div>
      <?php echo show_pagination($pagination); ?>
    </div>
  </div>
</div>
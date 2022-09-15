<?php
  $form_url = admin_url($controller_name."/store/");
  $form_attributes = array('class' => 'form actionForm', 'data-redirect' => get_current_url(), 'method' => "POST");
?>
<div class="card content">
  <div class="card-header">
    <h3 class="card-title"><i class="fe fe-credit-card"></i> <?=lang("payment_integration")?></h3>
  </div>
  <?php echo form_open($form_url, $form_attributes); ?>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12 col-lg-12">
          <h5 class="text-info"><i class="fe fe-link"></i> <?=lang("manual_payment")?></h5 class="text-info">
          <div class="form-group">
            <div class="form-label"><?=lang("Status")?></div>
            <div class="custom-controls-stacked">
              <label class="custom-control custom-checkbox">
                <input type="hidden" name="is_active_manual" value="0">
                <input type="checkbox" class="custom-control-input" name="is_active_manual" value="1" <?=(get_option('is_active_manual', "") == 1)? "checked" : ''?>>
                <span class="custom-control-label"><?=lang("Active")?></span>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label"><?=lang("Content")?></label>
            <textarea rows="3" name="manual_payment_content" id="manual_payment_content" class="form-control plugin_editor"><?=get_option('manual_payment_content', lang("you_can_make_a_manual_payment_to_cover_an_outstanding_balance_you_can_use_any_payment_method_in_your_billing_account_for_manual_once_done_open_a_ticket_and_contact_with_administrator"))?>
            </textarea>
          </div>

        </div> 
      </div>
    </div>
    <div class="card-footer text-end">
      <button class="btn btn-primary btn-min-width text-uppercase"><?=lang("Save")?></button>
    </div>
  <?php echo form_close(); ?>

    <div class="card-body"  style="float: right;">
        <div class="row">
            Paypal day limit factor <input id="paypal_daily_limit" placeholder="50" value="<?=$paypal_daily_limit?>">

            <button class="btn btn-primary btn-min-width btn-lg" onclick="changePaypalLimit()">Change</button>
        </div>
        <br/>

        <div class="row">
            Paypal transaction limit factor <input id="paypal_transaction_limit" placeholder="50" value="<?=$paypal_transaction_limit?>">

            <button class="btn btn-primary btn-min-width btn-lg" onclick="changePaypalTransactionLimit()">Change</button>
        </div>
    </div>
</div>
<script>
  $(document).ready(function() {
    plugin_editor('.plugin_editor', {height: 200, toolbar: 'code'});
  });

  function changePaypalLimit(){

      var limit = $('#paypal_daily_limit').val()

      $.ajax({
          type: "POST",
          url: "<?=cn("support/paypal_daily_limit")?>",
          data: {"limit" : limit}
      }).done(function() {
          notify("done", "success");
      });

  }

  function changePaypalTransactionLimit(){
      var limit = $('#paypal_transaction_limit').val()

      $.ajax({
          type: "POST",
          url: "<?=cn("support/paypal_transaction_limit")?>",
          data: {"limit" : limit}
      }).done(function() {
          notify("done", "success");
      });
  }
</script>

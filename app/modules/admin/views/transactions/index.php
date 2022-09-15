<?php 
  // Page header
  echo show_page_header($controller_name, ['page-options' => '', 'page-options-type' => '']);
  // Page header Filter
  echo show_page_header_filter($controller_name, ['items_status_count' => $items_status_count, 'params' => $params]);
?>

<div class="row">
  <?php if(!empty($items)){
  ?>
    <div class="col-md-12 col-xl-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><?=lang("Lists")?></h3>
        </div>
        <div class="table-responsive">
          <table class="table table-hover table-bordered table-vcenter card-table">
            <?php echo render_table_thead($columns, false); ?>
              <?php if (get_role('admin')) { ?>
                  <form action="<?php echo base_url().'admin/transactions';?>" method="get">
                      <tr>
                          <td>
<!--                              <select name="order">-->
<!--                                  <option>Order by</option>-->
<!--                                  --><?php //if (!empty($columns)) {
//                                      foreach ($columns as $key => $row) {
//                                          ?>
<!--                                          <option --><?php //if($order === $key) {echo 'selected';}?><!-- value="--><?//=$key?><!--">--><?//=$row?><!--</option>-->
<!--                                      --><?php //}
//                                  }?>
<!--                              </select>-->
                          </td>
                          <td><input type="text" name="user" placeholder="User" value="<?=$filter['user']?>"></td>
                          <td><input type="text" name="transaction_id" placeholder="Transaction ID" value="<?=$filter['transaction_id']?>"></td>
                          <td>
                              <select name="payment_method">
                                  <option>all</option>
                                  <?php foreach ($payments as $payment) {?>
                                      <option <?php if($filter['payment_method'] == $payment->type){echo 'selected';}?> id="<?=$payment->type?>">
                                          <?=$payment->type?>
                                      </option>
                                  <?php } ?>
                              </select>
                          </td>
                          <td><input type="text" name="amount" placeholder="Amount (Includes Fee)" value="<?=$filter['amount']?>"></td>
                          <td><input type="text" name="transaction_fee" placeholder="Transaction Fee" value="<?=$filter['transaction_fee']?>"></td>
                          <td><input type="text" name="note" placeholder="Note" value="<?=$filter['note']?>"></td>
                          <td></td>
                          <td>
                              <select name="status">
                                  <option>all</option>
                                  <?php foreach ($statuses as $key => $status) {?>
                                      <option id="<?=$status?>">
                                          <?=$key?>
                                      </option>
                                  <?php } ?>
                              </select>
                          </td>
                          <td><button type="submit">Filter</button></td>
                      </tr>
                  </form>
              <?php }?>
            <tbody>
              <?php if (!empty($items)) {
                $i = $from;
                foreach ($items as $key => $item) {
                  $i++;
                  $item_payment_type  = show_item_transaction_type($item['type']);
                  $created            = show_item_datetime($item['created'], 'long');
                  $show_item_buttons  = show_item_button_action($controller_name, $item['id']);
                  $item_status        = show_item_status($controller_name, $item['id'], $item['status'], '', '');
              ?>
                <tr class="tr_<?php echo esc($item['ids']); ?>">
                  <td class="text-center text-muted"><?=$item['id']?></td>
                  <td><?php echo show_high_light(esc($item['email']), $params['search'], 'email'); ?></td>
                  <td class="text-center w-10p"><?php echo show_high_light(esc($item['transaction_id']), $params['search'], 'transaction_id'); ?></td>
                  <td class="text-center w-10p"><?php echo $item_payment_type ; ?></td>
                  <td class="text-center w-10p"><?php echo $item['amount']; ?></td>
                  <td class="text-center w-5p text-muted"><?php echo $item['txn_fee']; ?></td>
                  <td class="text-center w-10p"><?php echo show_high_light(esc($item['note']), $params['search'], 'note'); ?></td>
                  <td class="text-center w-10p text-muted"><?php echo $created; ?></td>
                  <td class="text-center w-5p"><?php echo $item_status; ?></td>
                  <td class="text-center w-5p"><?php echo $show_item_buttons; ?></td>
                </tr>
              <?php }}?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <?php echo show_pagination($pagination); ?>
  <?php }else{
    echo show_empty_item();
  }?>
</div>

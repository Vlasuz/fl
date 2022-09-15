<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class coinpayments extends MX_Controller {
	public $tb_users;
	public $tb_transaction_logs;
	public $tb_payments;
	public $tb_payments_bonuses;
	public $paypal;
	public $payment_type;
	public $payment_id;
	public $payment_params;
	public $currency_code;
	public $payment_lib;
	public $mode;

	public function __construct($payment = ""){
		parent::__construct();
		$this->load->model('add_funds_model', 'model');

		$this->tb_users            = USERS;
		$this->tb_transaction_logs = TRANSACTION_LOGS;
		$this->tb_payments         = PAYMENTS_METHOD;
		$this->tb_payments_bonuses = PAYMENTS_BONUSES;
		$this->payment_type		   = get_class($this);
		$this->currency_code       = get_option("currency_code", "USD");
		if ($this->currency_code == "") {
			$this->currency_code = 'USD';
		}
		if (!$payment) {
			$payment = $this->model->get('id, type, name, params', $this->tb_payments, ['type' => $this->payment_type]);
		}
		$this->payment_id 		= $payment->id;
		$this->payment_params  	= $payment->params;
		$option             	= get_value($this->payment_params, 'option');
		$this->mode         	= get_value($option, 'environment');
		$this->payment_fee  	= get_value($option, 'tnx_fee');

		$this->load->library("coinpayments_api");
		$this->payment_lib = new coinpayments_api(get_value($option, 'secret_key'), get_value($option, 'public_key'));
	}

	public function index(){
		redirect(cn('add_funds'));
	}

	/**
	 *
	 * Create payment
	 *
	 */
	public function create_payment($data_payment = ""){
		_is_ajax($data_payment['module']);

		$currency2    = post('currency2');
		$amount       = $data_payment['amount'];
		if (!$amount) {
			_validation('error', lang('There_was_an_error_processing_your_request_Please_try_again_later'));
		}

		// Check Coinpayment Accept
		$option             	   = get_value($this->payment_params, 'option');
		$coinpayments_acceptance   = get_value($option, 'coinpayments_acceptance');
		if (!in_array($currency2, $coinpayments_acceptance)) {
			_validation('error', lang('There_was_an_error_processing_your_request_Please_try_again_later'));
		}
		$website_name = get_option('website_name');
		$users        = session('user_current_info');
	    $data_create_transaction = array(
	    	"currency1"        => 'USD',
	    	"currency2"        => $currency2,
	    	"buyer_email"      => $users['email'],
	    	"buyer_name"       => $users['first_name'] . ' ' . $users['last_name'],
	    	"item_number"      => "#".rand(10000,99999999),
	    	"item_name"        => lang('Deposit_to_').$website_name. ' ('.$users['email'].')',
	    	"invoice"          => convert_timezone(NOW, 'user'),
	    	"amount"           => $amount,
	    );
		$result = $this->payment_lib->create_payment($data_create_transaction);
		if (!$result) {
			_validation('error', lang('There_was_an_error_processing_your_request_Please_try_again_later'));
		}

		if ($result->status == 'error') {
			_validation('error', $result->message);
		}

		if ($result->status == 'success') {
			$data = array(
				"ids" 				=> ids(),
				"uid" 				=> session("uid"),
				"type" 				=> $this->payment_type,
				"transaction_id" 	=> $result->data["txn_id"],
				"amount" 	        => $amount,
				'txn_fee'           => $amount * ($this->payment_fee / 100),
				"status" 	        => 0,
				"created" 			=> NOW,
			);
			$this->db->insert($this->tb_transaction_logs, $data);
			$this->load->view("redirect", ['redirect_url' => $result->data['checkout_url'] ]);
		}
		
	}

	public function cron(){
		$transaction_ids = $this->model->fetch('ids, uid, transaction_id, amount, txn_fee', $this->tb_transaction_logs, ['status' => 0, 'type' => $this->payment_type]);
		if ($transaction_ids) {
			foreach ($transaction_ids as $key => $row) {
				$result = $this->payment_lib->get_transaction_detail_info($row->transaction_id);
				if (isset($result->status) && $result->status == 'error' && isset($result->message)) {
					pr($result->message);
				}
				if ($result->status == 'success') {
					/*----------  Add funds if TX complete  ----------*/
					if ($result->data['status'] == 100) {
						$tx_status = 1;

						// Update Balance 
						require_once 'add_funds.php';
						$add_funds = new add_funds();
						$add_funds->add_funds_bonus_email($row, $this->payment_id);
					}
					
					if ($result->data['status'] == -1 || $result->data['status'] == 100) {
						$tx_status = $result->data['status'];
						if ($tx_status == 100) {
							$tx_status = 1;
						}
						$this->db->update($this->tb_transaction_logs,['status' => $tx_status] ,['ids' => $row->ids, 'type' => $this->payment_type]);
					}
				}
			}
		}else{
			echo "There is no Transaction at the present.<br>";
		}
		echo "Successfully";
	}

    public function check()
    {
//        get_transaction_detail_info
        $result = $this->payment_lib->get_transaction_detail_info('CPGG07OOVFP42WFQLMJBMRC6SJ');
        var_dump($result);
        die();
    }
}



<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class unitpay extends MX_Controller {
	public $tb_users;
	public $tb_transaction_logs;
	public $tb_payments;
	public $tb_payments_bonuses;
	public $paypal;
	public $payment_type;
	public $payment_id;
	public $currency_code;
	public $payment_lib;
	public $mode;
	
	public $public_key;
	public $secret_key;
	public $midtrans_payment_channels;
	public $currency_rate_to_usd;

	public function __construct($payment = ""){
		parent::__construct();
		$this->load->model('add_funds_model', 'model');
		require_once APPPATH."../app/modules/add_funds/libraries/UnitPay.php";
		$this->tb_users            = USERS;
		$this->tb_transaction_logs = TRANSACTION_LOGS;
		$this->tb_payments         = PAYMENTS_METHOD;
		$this->tb_payments_bonuses = PAYMENTS_BONUSES;
		$this->payment_type		   = get_class($this);
		
		if (!$payment) {
			$payment = $this->model->get('id, type, name, params', $this->tb_payments, ['type' => $this->payment_type]);
		}
		$this->payment_id 	      = $payment->id;
		$params  			      = $payment->params;
		$option                   = get_value($params, 'option');
		$this->mode               = get_value($option, 'environment');
		$this->payment_fee        = get_value($option, 'tnx_fee');
		$this->currency_code      = get_value($option, "currency_code");
		if ($this->currency_code == "") {
			$this->currency_code = 'USD';
		}
		// Payment Option
		$this->public_key          			= get_value($option, 'public_key');
		$this->secret_key       			= get_value($option, 'secret_key');
		$this->currency_rate_to_usd     	= get_value($option, 'rate_to_usd');
	}

	public function index(){
    	redirect(cn('add_funds'));
	}


	/*----------  Create payment  ----------*/
	public function create_payment($data_payment = ""){
		_is_ajax($data_payment['module']);
		$amount = $data_payment['amount'];
		if (!$amount) {
			_validation('error', lang('There_was_an_error_processing_your_request_Please_try_again_later'));
		}

		if (!$this->public_key || !$this->secret_key) {
			_validation('error', lang('this_payment_is_not_active_please_choose_another_payment_or_contact_us_for_more_detail'));
		}
		$users  = session('user_current_info');
		$amount = (double)$data_payment['amount'];
		$domain     = 'unitpay.money';
		$projectId  = 1;
		$secretKey  = $this->secret_key;
		$publicId   = $this->public_key;

		// My Order Data
		$orderId        = 'TNX'.strtotime(NOW);
		$orderSum       = $amount;
		$orderDesc      = lang('Deposit_to_').get_option('website_name').'. ('.$users['email'].')';
		$orderCurrency  = $this->currency_code;
		$locale         = 'en';

		$unitPay = new UnitPay_RU($domain, $secretKey);
		$redirectUrl = $unitPay->form($publicId, $orderSum, $orderId, $orderDesc, $orderCurrency , $locale);

		$converted_amount = $amount / $this->currency_rate_to_usd;
		$data_tnx_log = array(
			"ids" 				=> ids(),
			"uid" 				=> session("uid"),
			"type" 				=> $this->payment_type,
			"transaction_id" 	=> $orderId,
			"amount" 	        => round($converted_amount, 4) ,
			'txn_fee'           => round($converted_amount * ($this->payment_fee / 100), 4),
			"note" 	            => $amount,
			"status" 	        => 0,
			"created" 			=> NOW,
		);
		$transaction_log_id = $this->db->insert($this->tb_transaction_logs, $data_tnx_log);
		if ($this->input->is_ajax_request()) {
			ms(['status' => 'success', 'redirect_url' => $redirectUrl]);
	    } 
	}

	public function unitpay_ipn(){
		// Project Data
		$domain = 'unitpay.money';
		$projectId  = 1;
		$secretKey  = $this->secret_key;
		$unitPay = new UnitPay_RU($domain, $secretKey);
		if (!isset($_GET['method']) || !isset($_GET['params'])) {
			echo $unitPay->getSuccessHandlerResponse("Error params!");
		        exit();
		}
		$method = $_GET['method'];
		$params = $_GET['params'];
		
		// Get transaction id
		$tnx_id = $params['account'];
		$transaction = $this->model->get('*', $this->tb_transaction_logs, ['transaction_id' => $tnx_id, 'status' => 0, 'type' => $this->payment_type]);
		try {
		    // Validate request (check ip address, signature and etc)
		    $unitPay->checkHandlerRequest();
		    if (!$transaction ) {
		        echo $unitPay->getSuccessHandlerResponse("Order validation Error, Transaction doesn't exists!");
		        exit();
		    }
		    switch ($method) {
		        // Just check order (check server status, check order in DB and etc)
		        case 'check':
		        	$tnx_data = [
		        		'data'   => json_encode($_GET),
		        		'note'   => 'Check Success. Ready to pay.',
		        	];
		            $this->db->update($this->tb_transaction_logs, $tnx_data,  ['id' => $transaction->id]);
		            echo $unitPay->getSuccessHandlerResponse('Check Success. Ready to pay.');
		            break;
		        // Method Pay means that the money received
		        case 'pay':
		        	$tnx_data = [
		        		'status' => 1,
		        		'data'   => json_encode($_GET),
		        		'note'   => 'Pay Success',
		        	];
		            $this->db->update($this->tb_transaction_logs, $tnx_data,  ['id' => $transaction->id]);
		            require_once 'add_funds.php';
		            $add_funds = new add_funds();
		            $add_funds->add_funds_bonus_email($transaction, $this->payment_id);
	             	echo $unitPay->getSuccessHandlerResponse('Pay Success');

		            break;
		        // Method Error means that an error has occurred.
		        case 'error':
		            $tnx_data = [
		            	'status' => -1,
		        		'data'   => json_encode($_GET),
		        		'note'   => $unitPay->getSuccessHandlerResponse('Error logged'),
		        	];
		            $this->db->update($this->tb_transaction_logs, $tnx_data,  ['id' => $transaction->id]);
		            echo $unitPay->getSuccessHandlerResponse('Error logged');
		            break;
		    }
		} catch (Exception $e) {
		    echo $unitPay->getErrorHandlerResponse($e->getMessage());
		}
	}
	
}
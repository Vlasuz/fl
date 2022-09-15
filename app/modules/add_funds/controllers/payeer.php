<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class payeer extends MX_Controller {
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
	
	public $pm_merchant_id;
	public $pm_secret_key;
	public $currency_rate_to_usd;

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
		$this->payment_id 	      = $payment->id;
		$params  			      = $payment->params;
		$option                   = get_value($params, 'option');
		$this->mode               = get_value($option, 'environment');
		$this->payment_fee        = get_value($option, 'tnx_fee');

		// Payment Option
		$this->pm_merchant_id           = get_value($option, 'merchant_id');
		$this->pm_secret_key       		= get_value($option, 'secret_key');
		$this->currency_rate_to_usd     = get_value($option, 'rate_to_usd');

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
		$amount = $data_payment['amount'];
		if (!$amount) {
			_validation('error', lang('There_was_an_error_processing_your_request_Please_try_again_later'));
		}

		if (!$this->pm_merchant_id || !$this->pm_secret_key) {
			_validation('error', lang('this_payment_is_not_active_please_choose_another_payment_or_contact_us_for_more_detail'));
		}

		$users = session('user_current_info');

		$m_shop    = $this->pm_merchant_id;
		$m_orderid = "ORDS" . strtotime(NOW);
		$m_amount  = number_format($amount, 2, '.', '');
		$m_curr    = 'USD';
		$m_desc    = base64_encode("Balance recharge - ".  $users['email']);


		$m_key     = $this->pm_secret_key;
		$arHash = array(
			$m_shop,
			$m_orderid,
			$m_amount,
			$m_curr,
			$m_desc
		);
		
		$arHash[] = $m_key;
		$sign = strtoupper(hash('sha256', implode(':', $arHash)));

		$paramList = [
			"m_shop" 		         => $m_shop,
			"m_orderid" 			 => $m_orderid,
			"m_amount" 	             => $m_amount,
			"m_curr" 	             => $m_curr,
			'm_desc'                 => $m_desc,
			'm_sign'                 => $sign,
		];

		$data_redirect = [
			"action_url" 	         => 'https://payeer.com/merchant/',
			"paramList" 	         => $paramList,
		];
		/*----------  Insert Transaction logs  ----------*/
		$data_tnx_log = array(
			"ids" 				=> ids(),
			"uid" 				=> session("uid"),
			"type" 				=> $this->payment_type,
			"transaction_id" 	=> $m_orderid,
			"amount" 	        => $amount ,
			'txn_fee'           => round($amount * ($this->payment_fee / 100), 4),
			"status" 	        => 0,
			"created" 			=> NOW,
		);
		$transaction_log_id = $this->db->insert($this->tb_transaction_logs, $data_tnx_log);

		$this->load->view($this->payment_type ."/redirect", $data_redirect);
	}

	public function complete(){

		if (!isset($_REQUEST['m_orderid']) || !isset($_REQUEST['m_shop']) || !isset($_REQUEST['m_amount']) || !isset($_REQUEST['m_status']) ) {
			redirect(cn("add_funds"));
		}
		$m_key = $this->pm_secret_key;
		$arHash = array(
			$_REQUEST['m_operation_id'],
			$_REQUEST['m_operation_ps'],
			$_REQUEST['m_operation_date'],
			$_REQUEST['m_operation_pay_date'],
			$_REQUEST['m_shop'],
			$_REQUEST['m_orderid'],
			$_REQUEST['m_amount'],
			$_REQUEST['m_curr'],
			$_REQUEST['m_desc'],
			$_REQUEST['m_status']
		);

		if (isset($_REQUEST['m_params'])) {
			$arHash[] = $_REQUEST['m_params'];
		}

		$arHash[] = $m_key;
		$sign_hash = strtoupper(hash('sha256', implode(':', $arHash)));
		$tx_order_id = strip_tags($_REQUEST['m_orderid']);
		$transaction = $this->model->get('*', $this->tb_transaction_logs, ['transaction_id' => $tx_order_id, 'status' => 0, 'type' => $this->payment_type]);
		if (!$transaction) {
            $arrContextOptions=array(
                "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                ),
            );

            $res = file_get_contents(
                'https://new.follow.sale/api/transaction/check?transaction_id='.$_REQUEST['m_orderid'],
                false,
                stream_context_create($arrContextOptions)
            );

            $checkStatus = json_decode($res, true);

            if ($checkStatus['check']) {
                $dataArray = [
                    'transaction_id' => $_GET['m_orderid']
                ];

                $dataArray = array_merge($dataArray, $_REQUEST);

                header('Location: https://new.follow.sale/transaction/success?'.http_build_query($dataArray));
            }
			redirect(cn("add_funds"));
		}

		if( $sign_hash == $_REQUEST['m_sign'] && $_REQUEST['m_status'] == 'success' && $transaction && $_REQUEST['m_shop'] == $this->pm_merchant_id ) {
			$this->db->update($this->tb_transaction_logs, ['status' => 1],  ['id' => $transaction->id]);
			
			// Update Balance 
    		require_once 'add_funds.php';
    		$add_funds = new add_funds();
    		$add_funds->add_funds_bonus_email($transaction, $this->payment_id);

			set_session("transaction_id", $transaction->id);
			redirect(cn("add_funds/success"));

		} else {
            $arrContextOptions=array(
                "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                ),
            );

            $res = file_get_contents(
                'https://new.follow.sale/api/transaction/check?transaction_id='.$_REQUEST['m_orderid'],
                false,
                stream_context_create($arrContextOptions)
            );

            $checkStatus = json_decode($res, true);

            if ($checkStatus['check']) {
                $dataArray = [
                    'transaction_id' => $_GET['m_orderid']
                ];

                $dataArray = array_merge($dataArray, $_REQUEST);

                header('Location: https://new.follow.sale/transaction/success?'.http_build_query($dataArray));
            }
			$this->db->update($this->tb_transaction_logs, ['status' => -1],  ['id' => $transaction->id]);
			redirect(cn("add_funds/unsuccess"));
		}


	}

}
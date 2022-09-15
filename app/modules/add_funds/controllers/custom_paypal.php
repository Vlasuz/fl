<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'modules/custom_paypal/PPGate/PayPalGatewayClient.php';
require_once APPPATH.'modules/custom_paypal/PPGate/HMAC.php';

class custom_paypal extends MX_Controller
{
    private $payment;

    private $apiUrl = 'https://au107.dock-automate.xyz/p2';

    public function __construct($payment = ""){
        parent::__construct();
        $this->payment = $payment;
    }

    public function create_payment($data_payment = "")
    {

	    $this->db->select("*");
        $this->db->from(USERS);
        $this->db->where("id", session('uid'));
        $user = $this->db->get();

        if ($user->row()->settings != 'null' && !(bool)json_decode($user->row()->settings, true)['limit_payments']['custom_paypal']) {
            _validation('error', 'payment blocked');
        }

        $this->db->select("*");
        $this->db->from(TRANSACTION_LOGS);
        $this->db->where("type != ", 'custom_paypal');
        $this->db->where("status", 1);
        $this->db->where("uid", session('uid'));
        $query = $this->db->get();

	
	$paypalMinimumAmountSumIgnore = isset(json_decode($user->row()->settings, true)['minimum_amount_sum_ignore'])
            ? json_decode($user->row()->settings, true)['minimum_amount_sum_ignore']
            : 0;
	
        if (array_sum(array_column($query->result_array(), 'amount')) < 50 && $paypalMinimumAmountSumIgnore != 1) {
		_validation('error', 'To pay via PayPal, you need to top up your balance for another '
			.(50-array_sum(array_column($query->result_array(), 'amount'))).'$ in any other way');
        }

	/*
        if (array_sum(array_column($query->result_array(), 'amount')) < 200) {
            _validation('error', 'where is money lebovski');
        }
	*/

        $this->db->select("*");
        $this->db->from(TRANSACTION_LOGS);
        $this->db->where("type = ", 'custom_paypal');
        $this->db->where("status", 1);
        $this->db->where("created > ", date('Y-m-d')." 00:00:00");
        $this->db->where("created < ", date('Y-m-d')." 23:59:59");
        $current = $this->db->get();

        $this->db->select("*");
        $this->db->from(PAYMENTS_METHOD);
        $this->db->where("type", 'custom_paypal');
        $payment = $this->db->get();

        $limit_paypal = 60;
        if (isset(json_decode($payment->row()->params, true)['limit'])) {
            $limit_paypal = json_decode($payment->row()->params, true)['limit'];
        }

        if ( ($limit_paypal - array_sum(array_column($current->result_array(), 'amount'))) < $data_payment['amount']) {
            _validation('error', 'limit');
        }

        _is_ajax($data_payment['module']);

        $params = json_decode($this->payment->params, true);

        $c = new PPGate\PayPalGatewayClient($this->apiUrl, $params['option']['client_id'], $params['option']['secret_key']);

        $transactionId = time();

        $r = $c->getCheckoutUrl([
            'transactionId' => $transactionId,
            'amount' => $data_payment['amount'],
            'currency' => 'USD'
        ]);
        
        if (gettype($r) === 'boolean') {
            _validation('error',lang("server_connection_error"));
        }

	if ($r->status === 'limit') {
            $this->db->update(PAYMENTS_METHOD, array('status' => 0), array("type" => 'custom_paypal'));
            ms(array(
                "status"  => "error",
                "message" => lang("paypal_limit"),
                "redirect_url" => cn("add_funds"),
            ));
        }

        $data_tnx_log = array(
            "ids"                 => $r->data->id,
            "uid"                 => session("uid"),
            "type"                 => 'custom_paypal',
            "transaction_id"     => $transactionId,
            "amount"             => $data_payment['amount'],
            "status"             => 0,
            "created"             => NOW,
        );

        $this->db->insert(TRANSACTION_LOGS, $data_tnx_log);

        ms(array(
            "status"  => "success",
            "redirect_url" => $r->data->redirectUrl,
        ));
    }
}

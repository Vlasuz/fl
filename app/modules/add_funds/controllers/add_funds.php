<?php
defined('BASEPATH') or exit('No direct script access allowed');
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
class add_funds extends My_UserController
{
    public $tb_users;
    public $tb_transaction_logs;
    public $tb_payments;
    public $tb_payments_bonuses;
    public $module;
    public $module_icon;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(get_class($this) . '_model', 'model');
        $this->module                       = get_class($this);
        $this->tb_users                     = USERS;
        $this->tb_transaction_logs          = TRANSACTION_LOGS;
        $this->tb_payments                  = PAYMENTS_METHOD;
        $this->tb_payments_bonuses          = PAYMENTS_BONUSES;
    }

    public function index()
    {
        $this->db->select("*");
        $this->db->from(TRANSACTION_LOGS);
        $this->db->where("type = ", 'custom_paypal');
        $this->db->where("status", 1);
        $this->db->where("created > ", date('Y-m-d')." 00:00:00");
        $this->db->where("created < ", date('Y-m-d')." 23:59:59");
        $current = $this->db->get();
        	
        /*----------  Get Payment Gate Way for user  ----------*/
        $payments = $this->model->fetch('type, name, id, params', $this->tb_payments, ['status' => 1], 'sort', 'ASC');
        $user_settings = $this->model->get('settings', $this->tb_users, ['id' => session('uid')])->settings;
        $user_settings = json_decode($user_settings);
        
        $current_paypal_sum = array_sum(array_column($current->result_array(), 'amount'));
        
        $this->db->select("*");
        $this->db->from(OPTIONS);
        $this->db->where("name", "paypal_limit_factor");
        $paypalLimitFactor = $this->db->get();

        $limit_paypal = $paypalLimitFactor->row()->value;
        
        if (isset($user_settings->limit_payments)) {
            $limit_payments = (array) $user_settings->limit_payments;
            foreach ($payments as $key => $payment) {
                if (isset($limit_payments[$payment->type]) && !$limit_payments[$payment->type]) {
                    unset($payments[$key]);
                }
            }
        }
        
        $this->db->select("*");
        $this->db->from(TRANSACTION_LOGS);
        $this->db->where("type != ", 'custom_paypal');
        $this->db->where("status", 1);
        $this->db->where("uid", session('uid'));
        $query = $this->db->get();
        
        $this->db->select("*");
        $this->db->from(USERS);
        $this->db->where("id", session('uid'));
        $user = $this->db->get();
        
         $paypalMinimumAmountSumIgnore = isset(json_decode($user->row()->settings, true)['minimum_amount_sum_ignore'])
            ? json_decode($user->row()->settings, true)['minimum_amount_sum_ignore']
            : 0;
        
        
        $data = array(
            "module"          => get_class($this),
            "payments"        => $payments,
            "currency_code"   => get_option("currency_code", 'USD'),
            "currency_symbol" => get_option("currency_symbol", '$'),
            "minimum_amount_sum_ignore"          =>  $paypalMinimumAmountSumIgnore,
            "sum_without_paypal"    => array_sum(array_column($query->result_array(), 'amount')),
            "current_paypal_sum"    => $current_paypal_sum,
            "limit_paypal"          => $limit_paypal
        );
        $this->template->build('index', $data);
    }

    public function process()
    {
        _is_ajax($this->module);
        $payment_id     = (int)post("payment_id");
        
        $amount         = (double)post("amount");
        $agree = post("agree");
        if ($amount == "") {
            ms(array(
                "status" => "error",
                "message" => lang("amount_is_required"),
            ));
        }

        if ($amount < 0) {
            ms(array(
                "status" => "error",
                "message" => lang("amount_must_be_greater_than_zero"),
            ));
        }

        /*----------  Check payment method  ----------*/
        $payment = $this->model->get('id, type, name, params', $this->tb_payments, ['id' => $payment_id]);
        if (!$payment) {
            _validation('error', lang('There_was_an_error_processing_your_request_Please_try_again_later'));
        }

        $min_payment = get_value($payment->params, 'min');
        $max_payment = get_value($payment->params, 'max');

        if ($amount < $min_payment) {
            _validation('error', lang("minimum_amount_is") . " " . $min_payment);
        }

        if ($max_payment > 0 && $amount > $max_payment) {
            _validation('error', 'Maximal amount is' . " " . $max_payment);
        }

        if (!$agree) {
            _validation('error', lang("you_must_confirm_to_the_conditions_before_paying"));
        }

        $data_payment = array(
            "module" => get_class($this),
            "amount" => $amount,
        );
        $payment_method = $payment->type;
        require_once $payment_method . '.php';
        $payment_module = new $payment_method($payment);
        $payment_module->create_payment($data_payment);

    }

    public function success()
    {
        $id = session("transaction_id");
        $transaction = $this->model->get("*", $this->tb_transaction_logs, "id = '{$id}' AND uid ='" . session('uid') . "'");
        if (!empty($transaction)) {
            $data = array(
                "module" => get_class($this),
                "transaction" => $transaction,
            );
            unset_session("transaction_id");
            $this->template->build('payment_successfully', $data);
        } else {
            redirect(cn("add_funds"));
        }
    }

    public function unsuccess()
    {
        $data = array(
            "module" => get_class($this),
        );
        $this->template->build('payment_unsuccessfully', $data);
    }
}

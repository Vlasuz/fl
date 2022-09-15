<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'modules/custom_paypal/PPGate/PayPalGatewayClient.php';
require_once APPPATH.'modules/custom_paypal/PPGate/HMAC.php';

class custom_paypal extends MX_Controller
{
    private $apiUrl = 'https://au107.dock-automate.xyz/p2';
    private $clientId = 2;
    private $secret = 'gftjQTgEUR8ypG7Cs6QtnLwAS45UCGgZKySBPeWBNZRz3pzpK85cAQKLcRjk24xGAVNpkrdjgmx8CGYPJx3hKJ3hRz';

    public function success()
    {
        $c = new PPGate\PayPalGatewayClient($this->apiUrl, $this->clientId, $this->secret);

        $t = $c->getTransactionInfo($_GET['transactionId']);

        if (gettype($t) === 'boolean' || (int)$t->data->status !== 10) {
            redirect(cn("add_funds/unsuccess"));
        }

        $this->db->select("*");
        $this->db->from(TRANSACTION_LOGS);
        $this->db->where("ids", $t->data->t_id);
        $this->db->where("type", 'custom_paypal');
        $this->db->where("amount", $t->data->amount);
        $this->db->where("status", 0);
        $query = $this->db->get();

        if ($query->conn_id->affected_rows === 0) {
            redirect(cn("add_funds/unsuccess"));
        }

        $this->db->update(TRANSACTION_LOGS, array('transaction_id' => $_GET['transactionId']), array("id" => $query->row()->id));

        $this->db->select("*");
        $this->db->from(TRANSACTION_LOGS);
        $this->db->where("transaction_id", $_GET['transactionId']);
        $query = $this->db->get();

        $this->db->select("*");
        $this->db->from(PAYMENTS_METHOD);
        $this->db->where("type", 'custom_paypal');
        $payment = $this->db->get();

        require_once APPPATH.'modules/add_funds/controllers/add_funds.php';
        $add_funds = new add_funds();
//        $add_funds->add_funds_bonus_email($query->row(), $payment->row()->id);

        $this->db->select("*");
        $this->db->from(USERS);
        $this->db->where("id", $query->row()->uid);
        $user = $this->db->get();

        $add_funds->update_user_balance($query->row()->uid, $user->row()->balance, $t->data->amount);

        $this->db->update(TRANSACTION_LOGS, array('status' => 1), array("id" => $query->row()->id));

        set_session("transaction_id", $query->row()->id);
        redirect(cn("add_funds/success"));
    }

    public function unsuccess()
    {
        $c = new PPGate\PayPalGatewayClient($this->apiUrl, $this->clientId, $this->secret);

        $t = $c->getTransactionInfo($_GET['transactionId']);

        if (gettype($t) === 'boolean' || (int)$t->data->status !== 8) {
            redirect(cn("add_funds/unsuccess"));
        }

        $this->db->select("*");
        $this->db->from(TRANSACTION_LOGS);
        $this->db->where("ids", $t->data->t_id);
        $this->db->where("type", 'custom_paypal');
        $this->db->where("amount", $t->data->amount);
        $this->db->where("status", 0);
        $query = $this->db->get();

        if ($query->conn_id->affected_rows === 0) {
            redirect(cn("add_funds/unsuccess"));
        }

        $this->db->update(TRANSACTION_LOGS, array('transaction_id' => $_GET['transactionId']), array("id" => $query->row()->id));
        $this->db->update(TRANSACTION_LOGS, array('status' => -1), array("id" => $query->row()->id));
    }
}


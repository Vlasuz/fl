<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class transactions_model extends MY_Model {

    private $statuses = [
        'waiting' => -1,
        'pending' => 0,
        'paid' => 1
    ];
    public function __construct()
    {
        parent::__construct();
        $this->tb_main     = TRANSACTION_LOGS;
        $this->tb_users               = USERS;
        $this->payments              = PAYMENTS_METHOD;
    }

    public function list_items($params = null, $option = null, $filter = [])
    {
        $result = null;
        if ($option['task'] == 'list-items') {
            $this->db->select('id, uid, amount, status, type, txn_fee, created');
            $this->db->from($this->tb_main." as tl");

            $this->db->where('uid', session('uid'));

            if (isset($filter['transaction_id']) && $filter['transaction_id']) {
                $this->db->like("tl.transaction_id", $filter['transaction_id'], 'both');
            }

            if (isset($filter['amount']) && $filter['amount']) {
                $this->db->where("tl.amount", $filter['amount']);
            }

            if (isset($filter['transaction_fee']) && $filter['transaction_fee']) {
                $this->db->where("tl.txn_fee", $filter['transaction_fee']);
            }

            if (isset($filter['payment_method']) && $filter['payment_method'] && $filter['payment_method'] != 'all') {
                $this->db->join($this->payments." p", "p.type = tl.type", 'left');
                $this->db->where("p.type", $filter['payment_method']);
            }

//            if (isset($filter['status']) && $filter['status'] && $filter['status'] != 'all') {
////                $this->db->where("tl.status", $filter['status']);
//            }

//            $this->db->join($this->tb_users." as u", "u.id = tl.uid", 'left');
//            if (isset($filter['user']) && $filter['user']) {
//                $this->db->like("u.email", $filter['user'], 'both');
//            }

            $this->db->order_by('id', 'DESC');
            if ($params['pagination']['limit'] != "" && $params['pagination']['start'] >= 0) {
                $this->db->limit($params['pagination']['limit'], $params['pagination']['start']);
            }

            $query = $this->db->get();
//            var_dump($query);
//            die();
            $result = $query->result_array();
        }
        return $result;
    }

    public function count_items($params = null, $option = null)
    {
        $result = null;
        // Count items for pagination
        if ($option['task'] == 'count-items-for-pagination') {
            $this->db->select('id');
            $this->db->from($this->tb_main);
            $this->db->where('status', 1);
            $this->db->where('uid', session('uid'));
            $query = $this->db->get();
            $result = $query->num_rows();
        }
        return $result;
    }
    
    function delete_unpaid_payment($day = ""){
        if ($day == "") {
            $day = 7;
        }
        $SQL   = "DELETE FROM ".$this->tb_main." WHERE `status` != 1 AND created < NOW() - INTERVAL ".$day." DAY";
        $query = $this->db->query($SQL);
        return $query;
    }

}

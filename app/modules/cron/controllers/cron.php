<?php
defined('BASEPATH') OR exit('No direct script access allowed');
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
require_once APPPATH.'modules/custom_paypal/PPGate/PayPalGatewayClient.php';
require_once APPPATH.'modules/custom_paypal/PPGate/HMAC.php';

class cron extends MX_Controller 
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(get_class($this).'_model', 'main_model');
        $this->provider = new Smm_api();
    }

    public function index()
    {
        redirect(cn());
    }

    public function status()
    {
        lock_file(['file_name' => 'status', 'title_message' => 'Status (single) already running!']);
        $params = [
            'limit' => 15,
            'start' => 0,
        ];
        $items = $this->main_model->list_items($params, ['task' => 'list-items-status']);
        if (!$items) {
            echo "There is no order at the present.<br>";
            exit();
        }
        foreach ($items as $key => $item) {
            $api = $this->main_model->get_item(['id' => $item['api_provider_id']], ['task' => 'get-item-provider']);
            if (!$api) {
                $response = ['error' => "API Provider does not exists"];
                $this->main_model->save_item(['item' => $item, 'response' => $response], ['task' => 'item-status']);
                continue;
            }
            $response = $this->provider->status($api, $item['api_order_id']);
            $this->main_model->save_item(['item' => $item, 'response' => $response], ['task' => 'item-status']);
        }
        echo "Successfully";
    }

    public function dripfeed()
    {
        lock_file(['file_name' => 'dripfeed', 'title_message' => 'Dripfeed status already running!']);
        $params = [
            'limit' => 15,
            'start' => 0,
        ];
        $items = $this->main_model->list_items($params, ['task' => 'list-items-dripfeed-status']);
        if (!$items) {
            echo "There is no order at the present.<br>";
            exit();
        }
        foreach ($items as $key => $item) {
            $api = $this->main_model->get_item(['id' => $item['api_provider_id']], ['task' => 'get-item-provider']);
            if (!$api) {
                $response = ['error' => "API Provider does not exists"];
                $this->main_model->save_item(['order_id' => $item['id'], 'response' => $response], ['task' => 'item-dripfeed-status']);
                continue;
            }
            $response = $this->provider->status($api, $item['api_order_id']);
            $this->main_model->save_item(['item' => $item, 'item_api' => $api,'response' => $response], ['task' => 'item-dripfeed-status']);
        }
        echo "Successfully";
    }

    public function subscriptions()
    {
        $params = [
            'limit' => 15,
            'start' => 0,
        ];
        $items = $this->main_model->list_items($params, ['task' => 'list-items-subscriptions-status']);
        if (!$items) {
            echo "There is no order at the present.<br>";
            exit();
        }
        foreach ($items as $key => $item) {
            $api = $this->main_model->get_item(['id' => $item['api_provider_id']], ['task' => 'get-item-provider']);
            if (!$api) {
                $response = ['error' => "API Provider does not exists"];
                $this->main_model->save_item(['order_id' => $item['id'], 'response' => $response], ['task' => 'item-subscriptions-status']);
                continue;
            }
            $response = $this->provider->status($api, $item['api_order_id']);
            $this->main_model->save_item(['item' => $item, 'item_api' => $api,'response' => $response], ['task' => 'item-subscriptions-status']);
        }
        echo "Successfully";
    }

    public function multiple_status()
    {
        lock_file(['file_name' => 'multiple_status', 'title_message' => 'Multiple status already running!']);
        $params = [
            'limit' => 100,
            'start' => 0,
        ];
        $items = $this->main_model->list_items($params, ['task' => 'list-items-multiple-status']);
        if (!$items) {
            echo "There is no order at the present.<br>";
            exit();
        }
        
        $items_group_by_api = group_by_criteria($items, 'api_provider_id');
        foreach ($items_group_by_api as $api_id => $items_group) {
            $api = $this->main_model->get_item(['id' => $api_id], ['task' => 'get-item-provider']);
            if (!$api) {
                $response = ['error' => "API Provider does not exists"];
                $params = [
                    'order_ids'          => array_column($items_group, 'id'),
                    'response'           => $response,
                ];
                $this->main_model->save_item($params, ['task' => 'item-multiple_status']);
                continue;
            }
            $response = $this->provider->multiStatus($api, array_column($items_group, 'api_order_id'));
            if ($response) {
                $exist_items = [];
                foreach ($items_group as $key => $item) {
                    if (isset($response[$item['api_order_id']]) && !in_array($item['api_order_id'], $exist_items)) {
                        $this->main_model->save_item(['item' => $item, 'response' => $response[$item['api_order_id']]], ['task' => 'item-status']);
                        $exist_items[] = $item['api_order_id'];
                    }
                }
            }
        }
        echo "Successfully";
    }

    //Send
    public function order()
    {
        lock_file(['file_name' => 'order', 'title_message' => 'Order already running!']);
        $items = $this->main_model->list_items('', ['task' => 'list-items-new-order']);
        if (!$items) {
            echo "There is no order at the present.<br>";
            exit();
        }
        foreach ($items as $key => $row) {
            $api = $this->main_model->get_item(['id' => $row->api_provider_id], ['task' => 'get-item-provider']);
            if (!$api) {
                $response = ['error' => "API Provider does not exists"];
                $this->main_model->save_item(['order_id' => $row->id, 'response' => $response], ['task' => 'item-new-update']);
                continue;
            }
            $data_post = [
                'action'   => 'add',
                'service'  => $row->api_service_id,
            ];
            switch ($row->service_type) {
                case 'subscriptions':
                    $data_post["username"] = $row->username;
                    $data_post["min"]      = $row->sub_min;
                    $data_post["max"]      = $row->sub_max;
                    $data_post["posts"]    = ($row->sub_posts == -1) ? 0 : $row->sub_posts ;
                    $data_post["delay"]    = $row->sub_delay;
                    $data_post["expiry"]   = (!empty($row->sub_expiry))? date("d/m/Y",  strtotime($row->sub_expiry)) : "";//change date format dd/mm/YYYY
                    break;

                case 'custom_comments':
                    $data_post["link"]     = $row->link;
                    $data_post["comments"] = json_decode($row->comments);
                    break;

                case 'mentions_with_hashtags':
                    $data_post["link"]         = $row->link;
                    $data_post["quantity"]     = $row->quantity;
                    $data_post["usernames"]    = $row->usernames;
                    $data_post["hashtags"]     = $row->hashtags;
                    break;

                case 'mentions_custom_list':
                    $data_post["link"]         = $row->link;
                    $data_post["usernames"]    = json_decode($row->usernames);
                    break;

                case 'mentions_hashtag':
                    $data_post["link"]         = $row->link;
                    $data_post["quantity"]     = $row->quantity;
                    $data_post["hashtag"]      = $row->hashtag;
                    break;
                    
                case 'mentions_user_followers':
                    $data_post["link"]         = $row->link;
                    $data_post["quantity"]     = $row->quantity;
                    $data_post["username"]     = $row->username;
                    break;

                case 'mentions_media_likers':
                    $data_post["link"]         = $row->link;
                    $data_post["quantity"]     = $row->quantity;
                    $data_post["media"]        = $row->media;
                    break;

                case 'package':
                    $data_post["link"]         = $row->link;
                    break;	

                case 'custom_comments_package':
                    $data_post["link"]         = $row->link;
                    $data_post["comments"]     = json_decode($row->comments);
                    break;

                case 'comment_likes':
                    $data_post["link"]         = $row->link;
                    $data_post["quantity"]     = $row->quantity;
                    $data_post["username"]     = $row->username;
                    break;
                
                default:
                    $data_post["link"] = $row->link;
                    $data_post["quantity"] = $row->quantity;
                    if (isset($row->is_drip_feed) && $row->is_drip_feed == 1) {
                        $data_post["runs"]     = $row->runs;
                        $data_post["interval"] = $row->interval;
                        $data_post["quantity"] = $row->dripfeed_quantity;
                    }else{
                        $data_post["quantity"] = $row->quantity;
                    }
                    break;
            }
            $response = $this->provider->order($api, $data_post);
            $this->main_model->save_item(['order_id' => $row->id, 'response' => $response], ['task' => 'item-new-update']);
        }
        echo "Successfully";
    }
    
    public function paypalStatus()
    {
              $this->db->select("*");
                $this->db->from(USERS);
                $this->db->where('id', 45);
                $query = $this->db->get();
                $result = $query->result();
                
                var_dump($result);
                die();
              require_once APPPATH.'modules/add_funds/controllers/add_funds.php';
              $add_funds = new add_funds();

        
              $this->db->select("*");
              $this->db->from(PAYMENTS_METHOD);
              $this->db->where("type", 'custom_paypal');
              $payment = $this->db->get();

              $params = json_decode($payment->row()->params, true);
            
              $apiUrl = 'https://au107.dock-automate.xyz/p2';

              $this->db->select("*");
              $this->db->from(TRANSACTION_LOGS);
              $this->db->where("type", 'custom_paypal');
              $this->db->where("status", 0);
              $this->db->where("transaction_id != ", '');
              $query = $this->db->get();
              $transactions = $query->result_array();
              
            
              $c = new PPGate\PayPalGatewayClient($apiUrl, $params['option']['client_id'], $params['option']['secret_key']);
            
              foreach($transactions as $transaction) {
                  if ($transaction['transaction_id']) {
                        
                        $result = $c->getTransactionInfo($transaction['transaction_id']);
                        
                        if ($result->data->status == 10) {
                        
                            print_r($result->data);
                            print_r($transaction);
                        
                            $this->db->select("*");
                            $this->db->from(USERS);
                            $this->db->where("id", $transaction['uid']);
                            $user = $this->db->get();

                            $add_funds->update_user_balance($transaction['uid'], $user->row()->balance, $transaction['amount']);

                            $this->db->update(TRANSACTION_LOGS, array('status' => 1), array("id" => $transaction['id']));  
                        }
                     }
              }
    }
    
    public function limit()
    {
        $apiUrl = 'https://au107.dock-automate.xyz/p2';

                $this->db->select("*");
                $this->db->from(PAYMENTS_METHOD);
                $this->db->where("type", 'custom_paypal');
                $payment = $this->db->get();

                $params = json_decode($payment->row()->params, true);

                $c = new PPGate\PayPalGatewayClient($apiUrl, $params['option']['client_id'], $params['option']['secret_key']);

                $limits = $c->getLimits();

                $this->db->select("*");
                $this->db->from(OPTIONS);
                $this->db->where("name", "paypal_limit_factor");
                $paypalLimitFactor = $this->db->get();

                $limitValue = ceil($paypalLimitFactor->row()->value * rand(10, 14) / 10);

                if (gettype($limits) !== 'boolean') {
                    $limitValue = $limits->data->daily_limit;
                }

                $params = json_decode($payment->row()->params,true);

                $params['limit'] = $limitValue;
                $params['max'] = ( $params['limit'] < $payment->row()->max ) ? $params['limit'] : $payment->row()->max;

                $this->db->update(PAYMENTS_METHOD, array('params' => json_encode($params), 'max' => $params['limit']), array("id" => $payment->row()->id));

			/*
				$this->db->select("*");
                		$this->db->from(OPTIONS);
                		$this->db->where("name", "paypal_limit_factor");
                		$paypalLimitFactor = $this->db->get();

                		$this->db->select("*");
                		$this->db->from(PAYMENTS_METHOD);
                		$this->db->where("type", 'custom_paypal');
                		$query = $this->db->get();

                		$params = json_decode($query->row()->params,true);

                		$params['limit'] = $paypalLimitFactor->row()->value * rand(3, 17) / 10;
                		$params['max'] = ( $params['limit'] < $query->row()->max ) ? $params['limit'] : $query->row()->max;

                		$this->db->update(PAYMENTS_METHOD, array('params' => json_encode($params)), array("id" => $query->row()->id));
                		break;

			
			case 'limit':
                	$this->db->select("*");
                	$this->db->from(PAYMENTS_METHOD);
                	$this->db->where("type", 'custom_paypal');
                	$query = $this->db->get();

                	$params = json_decode($query->row()->params,true);
                	$params['limit'] = 100 * rand(3, 17) / 10;

                	$this->db->update(PAYMENTS_METHOD, array('params' => json_encode($params)), array("id" => $query->row()->id));
                	break;

			///////////////////////////////////////////////////////////////
			*/
    }

    public function support_setting()
    {
        $this->db->select("*");
        $this->db->from(OPTIONS);
        $this->db->where("name", "support_status");
        $supportStatus = $this->db->get();

        $this->db->update(OPTIONS, array('value' => ($supportStatus->row()->value == 1 ? 0 : 1)), array("id" => $supportStatus->row()->id));
    }

    public function paypal_daily_limit()
    {
        $this->db->select("*");
        $this->db->from(OPTIONS);
        $this->db->where("name", "paypal_limit_factor");
        $paypalLimitFactor = $this->db->get();

        $this->db->update(OPTIONS, array('value' => $_POST['limit']), array("id" => $paypalLimitFactor->row()->id));

        echo_json_string(array(
            'status' => "success",
        ));
    }

    public function paypal_transaction_limit()
    {
        $this->db->select("*");
        $this->db->from(OPTIONS);
        $this->db->where("name", "paypal_transaction_limit");
        $paypalLimitFactor = $this->db->get();

        $this->db->update(OPTIONS, array('value' => $_POST['limit']), array("id" => $paypalLimitFactor->row()->id));


        $this->db->select("*");
        $this->db->from(PAYMENTS_METHOD);
        $this->db->where("type", "custom_paypal");
        $paypal = $this->db->get();

        $paypalParams = json_decode($paypal->row()->params);
        $paypalParams->limit = $_POST['limit'];
        $paypalParams->max = $_POST['limit'];

        $this->db->update(PAYMENTS_METHOD, ['params' => json_encode($paypalParams), 'max' => $_POST['limit']], ["id" => $paypal->row()->id]);

        echo_json_string(array(
            'status' => "success",
        ));
    }

    public function paypal_ignore()
    {
        $this->db->select("*");
        $this->db->from(USERS);
        $this->db->where("id", $_POST['user_id']);
        $user = $this->db->get();

        $settings = json_decode($user->row()->settings, true);

        if (isset($settings['minimum_amount_sum_ignore'])) {
            $settings['minimum_amount_sum_ignore'] = $settings['minimum_amount_sum_ignore'] == 1 ? 0 : 1;
        } else {
            $settings['minimum_amount_sum_ignore'] = 1;
        }

        $this->db->update(USERS, array('settings' => json_encode($settings)), array("id" => $_POST['user_id']));
    }
}
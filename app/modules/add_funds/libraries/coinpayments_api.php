<?php defined('BASEPATH') OR exit('No direct script access allowed');

// require_once 'coinpayments/autoload.php';
require('coinpayments/src/CoinpaymentsAPI.php');
require('coinpayments/src/keys.php');
/**
 * coinpayment
 */
class coinpayments_api{
	private $cps_api;
	
	public function __construct($private_key = null, $public_key = null) {
		if ($private_key != "" && $public_key != "") {
			try {
			    $this->cps_api = new CoinpaymentsAPI($private_key, $public_key, 'json');
			} catch (Exception $e) {
			    echo 'Error: ' . $e->getMessage();
			    exit();
			}
		}
	}

	public function get_all_coin_balances(){
		$coins = $this->cps_api->GetRatesWithAccepted();
		return $coins;
	}

	public function create_payment($data = array('')){
		$amount = $data['amount'];
		// The currency for the amount above (original price)
		$currency1 = $data['currency1'];
		// Litecoin Testnet is a no value currency for testing
		// The currency the buyer will be sending equal to amount of $currency1
		$currency2 = $data['currency2'];
		// Enter buyer email below
		$buyer_email = $data['buyer_email'];
		// Set a custom address to send the funds to.
		// Will override the settings on the Coin Acceptance Settings page
		$address = '';
		// Enter a buyer name for later reference
		$buyer_name = $data['buyer_name'];
		// Enter additional transaction details
		$item_name = $data['item_name'];
		$item_number = $data['item_number'];
		$custom = 'Express order';
		$invoice = $data['invoice'];
		$ipn_url = 'https://not-a-real-website.com/your_ipn_handler_script.php';

		// Make call to API to create the transaction
		try {
		    $response = $this->cps_api->CreateComplexTransaction($amount, $currency1, $currency2, $buyer_email, $address, $buyer_name, $item_name, $item_number, $invoice, $custom, $ipn_url);
		} catch (Exception $e) {
		    echo 'Error: ' . $e->getMessage();
		    exit();
		}
		// Output the response of the API call
		if ($response["error"] == "ok") {
			$result = array(
				'status' => 'success',	
				'data' => $response['result'],	
			);
		}else{
			$result = array(
				'status' => 'error',	
				'message' => $response["error"],	
			);
		}

		return (object)$result;
	}

	public function get_transaction_detail_info($transaction_id){
		try {
		    $response = $this->cps_api->GetTxInfoSingle($transaction_id);
		} catch (Exception $e) {
		    echo 'Error: ' . $e->getMessage();
		    exit();
		}
		if ($response["error"] == "ok") {
			$result = array(
				'status' => 'success',	
				'data' => $response['result'],	
			);
		}else{
			$result = array(
				'status' => 'error',	
				'message' => $response["error"],	
			);
		}

		return (object)$result;
	} 
}
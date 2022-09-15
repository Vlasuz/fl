<?php
namespace PPGate;

class PayPalGatewayClient {

	private $apiUrl; // api url without slash in the end
	private $clientId; // client id
	private $secret; // secret

	public $error;

	public function __construct($apiUrl, $clientId, $secret)
	{
		$this->apiUrl = $apiUrl;
		$this->clientId = $clientId;
		$this->secret = $secret;
	}

	public function validateIPN()
	{
		if (!isset($_POST['hmac']))
			return false;
	
		$hmac = new HMAC($this->secret);
		$post_hmac = $hmac->add($_POST);

		if ($post_hmac['hmac'] !== $_POST['hmac'])
			return false;

		return true;
	}

	public function getTransactionInfo($t_id)
	{
		$r = $this->_request('info', ['transactionId' => $t_id]);
		if (!$r)
			return false;

		return $r;
	}

	public function getCheckoutUrl($c)
	{
		$r = $this->_request('checkout_url', $c);
		if (!$r)
			return false;

		if (!isset($r->data->redirectUrl))
			return false;

		return $r;
	}

	public function getLimits()
    	{
        	$r = $this->_request('limits', []);
        	if (!$r)
            	return false;

        	return $r;
    	}

	private function _request($method, $data)
	{
		// reset error
		$this->error = null;

		$data['clientId'] = $this->clientId;

		$hmac = new HMAC($this->secret);
		$data = $hmac->add($data);

		$ch = curl_init($this->apiUrl . '/' . $method);
		curl_setopt_array($ch, [
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_AUTOREFERER    => true,
				CURLOPT_CONNECTTIMEOUT => 15,
				CURLOPT_TIMEOUT        => 90,
				CURLOPT_TIMEOUT_MS     => 90000,
				CURLOPT_MAXREDIRS      => 5,
				CURLOPT_SSL_VERIFYPEER => false,
				//CURLOPT_SSL_VERIFYHOST => 0,
				CURLINFO_HEADER_OUT    => false,
				CURLOPT_POST           => true,
				CURLOPT_POSTFIELDS     => http_build_query($data)
			]);
		$response = curl_exec($ch);
		if (!$response || curl_errno($ch))
		{
			$this->error = curl_error($ch);
			return false;
		}
		curl_close($ch);

		// decode
		$r = json_decode($response);

		// error -> json
		if (!$r || json_last_error())
		{
			$this->error = $response;
			return false;
		}

		// error -> request status
		if (!isset($r->status) || $r->status !== 'success')
		{
			$this->error = (isset($r->message)) ? $r->message : 'Unknown error, response: '.$response;
			return false;
		}

		return $r;
	}

}


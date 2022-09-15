<?php
namespace PPGate;

class HMAC {

	protected $secret;
	protected $field = 'hmac';

	/**
	 * HMAC constructor.
	 * @param string $secret
	 */
	public function __construct($secret)
	{
		$this->secret = $secret;
	}

	private static function stringify($v)
	{
		if (is_array($v))
			return empty($v) ? '' : array_map('PPGate\HMAC::stringify', $v);

		return trim((string)$v);
	}

	/**
	 * @param array $data
	 * @return mixed
	 */
	public function add($data)
	{
		if (array_key_exists($this->field, $data))
			unset($data[$this->field]);

		ksort($data);

		$data = array_map('PPGate\HMAC::stringify', $data);

		$encoded = json_encode($data, JSON_UNESCAPED_UNICODE);

		$data[$this->field] = hash_hmac('sha256', $encoded, $this->secret);

		return $data;
	}

	/**
	 * @param array $data
	 * @return null|array
	 */
	public function validate($data)
	{
		if (empty($data[$this->field]))
			return null;

		$newData = $this->add($data);

		return ($newData[$this->field] === $data[$this->field]);
	}

}


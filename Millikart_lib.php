<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Millikart {
	// config variables
		private $mid;
		private $secretkey;
		private $status;
		private $currency;
		private $language;
		private $test_url;
		private $pro_url;
	// end config
	public 	$description;
	public 	$amount;
	public 	$reference;
	public  $ci;

	public function __construct () {
		$CI = &get_instance();
		$CI->load->config('millikart');
		$this->mid 		= $CI->config->item('millikart_mid');
		$this->secretkey 	= $CI->config->item('millikart_secret_key');
		$this->status 		= $CI->config->item('millikart_status');
		$this->currency 	= $CI->config->item('millikart_currency');
		$this->language 	= $CI->config->item('millikart_language');
		$this->test_url 	= $CI->config->item('millikart_test_url');
		$this->pro_url 		= $CI->config->item('millikart_pro_url');
		$this->ci 			= $CI;
	}

	public function set($amount, $reference, $description){
		$this->amount 		= $amount*100;
		$this->description 	= $description;
		$this->reference 	= $reference;
	}
	
	private function signature() {
		
		$data = strlen($this->mid);
		$data .= $this->mid;
		$data .= strlen($this->amount);
		$data .= $this->amount;
		$data .= strlen($this->currency);
		$data .= $this->currency;
		if(!empty($this->description)) {
			$data .= strlen($this->description);
			$data .= $this->description;
		}
		else{
			$data .= "0";
		}
		
		$data .= strlen($this->reference); 	
		$data .= $this->reference;	
		$data .= strlen($this->language);
		$data .= $this->language;	
		$data .= $this->secretkey;
		$data = md5($data);
		$data = strtoupper($data);
		return $data;
	}

	public function getURL(){
		$data_url ="/gateway/payment/register?mid=".$this->mid."&amount=".$this->amount."&currency=".$this->currency."&description=".$this->description."&reference=".$this->reference."&language=".$this->language."&signature=".$this->signature()."&redirect=1";
		if($this->status == "0") {
			$url = $this->test_url.$data_url;
		}
		else {
			$url = $this->pro_url.$data_url;
		}
		return $url;
	}

	public function callback($reference = false)
	{
		if($reference)
		{
			$data_url = "/gateway/payment/status?mid=".$this->mid."&reference=".$reference;
			if($this->status == "0") {
				$url = $this->test_url.$data_url;
			}
			else {
				$url = $this->pro_url.$data_url;
			}

			$xml = file_get_contents($url);
			$xml = simplexml_load_string($xml);
			return $xml;
		}
		return false;
	}
}

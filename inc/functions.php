<?php
	
class curl_func
{
	protected $curl = null;
	
	protected function _curl_init()
	{
		$this->curl = curl_init();
		if ( !$this->curl ) exit( 'cURL could not be initiated.' );
	}
	
	protected function _curl_close()
	{
		if ( $this->curl ) curl_close( $this->curl );
		$this->curl = null;
	}
}

class wpst_api extends curl_func
{
	private $region = '';
	private $keywords = null;

	function __construct()
	{
	
	}
	
	function __destruct()
	{
		$this->_curl_close();
		$this->keywords = null;
	}
	
	public function request( $keyword = null, $wpst_api_key = NULL, $region = "es", $sortfield = "volume", $sorttype = "desc")
	{
		if ( ! $keyword ) exit( 'No keyword defined.' );
		
		$keyword = urlencode($keyword);
		$this->_curl_init();
		curl_setopt( $this->curl, CURLOPT_URL,"http://apidev.wpseotitle.com/suggestions/$keyword?apikey=$wpst_api_key&country=$region&sortfield=$sortfield&sorttype=$sorttype" );
		curl_setopt( $this->curl, CURLOPT_TIMEOUT, 60 );
		curl_setopt( $this->curl, CURLOPT_RETURNTRANSFER, TRUE );
		$curl_return = curl_exec( $this->curl );
		$this->_curl_close();
		
		$this->keywords = $curl_return;
		return $this->keywords;
	}
}

?>

<?php namespace Phpubot\Cli;

class CommandMaster {
	protected $arguments;

	protected $requester;

	protected $bitlyToken = ''; //Need to enter in your bitly token if you want to use the URL Shortener

	public function __construct($arguments, $requester) {
		$this->arguments = $arguments;
		$this->requester = $requester;
	}

	public function shortenUrl($url) {
		$bitly = new \Curl\Curl();
		$token = $this->bitlyToken;
		$url = urlencode($url);
		$bitly->get('https://api-ssl.bitly.com/v3/shorten?access_token=' . $token . '&longUrl=' . $url);
		$results = json_decode($bitly->response, true);
		return $results['data']['url'];
	} 

}

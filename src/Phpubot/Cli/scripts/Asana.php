<?php namespace Phpubot\Cli;

class Asana extends CommandMaster {

	protected $asana;

	protected $asanaToken = '2cT3O3bt.XOzXgZuRH9C2kLpT8AljYGh';

	protected $asanaPassword = ''; //Typically not needed

	public function __construct($arguments, $name) {
		parent::__construct($arguments, $name);
		$this->asana = new \Curl\Curl();
		$this->asana->setBasicAuthentication($this->asanaToken, $this->asanaPassword);
	}
		
	/**
	* go get asana tag {tagname}
	*/
	public function getAsanaTag($tag) {
		$results = $this->asana->get("https://app.asana.com/api/1.0/tags/" . $tag . "/tasks");
		if ($this->asana->error) {
			echo $this->asana->error_code;
		} else {
			$json = json_decode($this->asana->response, true);
			foreach ($json['data'] as $task) { 
				$in = $task['name'];
				$out = strlen($in) > 50 ? substr($in,0,50)."..." : $in;	
				echo $out . PHP_EOL;
				echo '          ' . $this->shortenUrl('https://app.asana.com/0/' . $tag . '/' . $task['id']) . PHP_EOL;
			}
		}
	}
	
	public function getBlahBlahTag() {
		$this->getAsanaTag(1111111111); //Get asana tag number and print out results
	}
}

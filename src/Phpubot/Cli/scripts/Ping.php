<?php namespace Phpubot\Cli;

class Ping extends CommandMaster {

	public function __construct($arguments, $name) {
		parent::__construct($arguments, $name);
	}
	
	public function ping() {
		return 'pong';
	}

	public function echo() {
		return $this->arguments;
	}

	public function time() {
		return 'Server time is: ' . time();
	}
}

<?php namespace Phpubot\Cli;

use Ulrichsg\Getopt\Getopt;
use Ulrichsg\Getopt\Option;
use Phpubot\Cli\Asana;
class PhpubotCli {

	public $getOpt;
	public $operands;

	public $commandClassMap = array();

	public function __construct() {
		// Define all options here
		$this->getOpt = new Getopt(array(
			new Option(null, 'go', Getopt::REQUIRED_ARGUMENT),
		));
		try {
			$this->getOpt->parse();
			$this->operands = $this->getOpt->getOperands();
			$requester = $this->getRequester();
			$classCommand = $this->getClassAndCommand();
			$class = '\Phpubot\Cli\\' . $classCommand['class'];
			if (!class_exists($class)) {
				//echo 'Command not defined' . PHP_EOL;
				exit;
			}
			$phpubot = new $class($classCommand['remaining_arguments'], $requester);
			echo $phpubot->$classCommand['method']() . PHP_EOL;	
		} catch(UnexpectedValueException $e) {
			print_r('here');
		}
	}

	public function getClassCommandMap() {
		$this->commandClassMap = require('commands.php');
	}

	public function getRequester() {
		$operands = implode(' ', $this->operands);
		preg_match('/(?<=\=\=).*(?=\=\=)/', $operands, $matches);
		$name = $matches[0];
		$this->operands = explode(' ', trim(strstr($operands, '==', true)));
		return $name;
	}

	public function getClassAndCommand() {
		$this->getClassCommandMap();
		foreach($this->commandClassMap as $searchTerm => $classCommand) {
			if (stripos(strtolower(implode(' ', $this->operands)), $searchTerm) !== false) {
				return $this->getRemainingArguments($searchTerm, $classCommand);
			}
		}
	}
	
	public function getRemainingArguments($string, $command) {
		$stringArray = explode(' ', $string);
		$remainingArguments = $this->operands;
		foreach ($this->operands as $key => $operand) {
			if (array_key_exists($key, $stringArray) && $operand == $stringArray[$key]) {
				unset($remainingArguments[$key]);
			}
		}
		$arguments = explode('.', $command);
		$arguments = array(
			'class' => $arguments[0],
			'method' => $arguments[1],
			'remaining_arguments' => $remainingArguments,
		);
		return $arguments;
	}
}


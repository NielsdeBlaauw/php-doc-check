<?php
namespace NdB\PhpDocCheck\Findings;

class Warning{
	public function __construct(string $message, int $line){
		$this->message = $message;
		$this->line = $line;
	}

	public function getType():string{
		return 'Warning';
	}
	public function getLine():int{
		return $this->line;
	}
	public function getMessage():string{
		return $this->message;
	}
}
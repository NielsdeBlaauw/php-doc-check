<?php
namespace NdB\PhpDocCheck\Findings;

class Error extends Warning{
	public function getType():string{
		return 'Error';
	}
}
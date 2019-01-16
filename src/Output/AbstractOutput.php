<?php
namespace NdB\PhpDocCheck\Output;

abstract class AbstractOutput{
	public function __construct(array $files){
		$this->files = $files;
	}

	public function display(){
		echo $this->get();
	}

	public function getExitCode(){
		foreach($this->files as $file){
			if($file->has_errors || $file->has_warnings){
				return 1;
			}
		}
		return 0;
	}
}
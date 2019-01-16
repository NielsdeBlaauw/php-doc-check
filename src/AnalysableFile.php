<?php
namespace NdB\PhpDocCheck;

class AnalysableFile{
	public $has_errors = false;
	public $has_warnings = false;
	public $findings = array();

	public function __construct(\SplFileInfo $file, \PhpParser\Parser $parser, $arguments){
		$this->file = $file;
		$this->parser = $parser;
		$this->arguments = $arguments;
	}

	public function analyse(){
		$statements = $this->parser->parse(file_get_contents($this->file->getRealPath()));
		$traverser  = new \PhpParser\NodeTraverser();
		$traverser->addVisitor(new \NdB\PhpDocCheck\NodeVisitor($this));
		$traverser->traverse($statements);
		if(!$this->has_warnings && !$this->has_errors ){
			return '.';
		}elseif(!$this->has_errors){
			return 'W';
		}else{
			return 'E';
		}
	}
}
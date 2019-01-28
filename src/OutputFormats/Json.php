<?php
namespace NdB\PhpDocCheck\OutputFormats;

final class Json extends OutputFormat
{
    public function get() : string
    {
        $output = array();
        $output = array_filter($this->analysisResults, array($this, 'removeEmpty'));
        return (string) json_encode($output);
    }

    public function removeEmpty(\NdB\PhpDocCheck\AnalysisResult $analysisResult) : bool
    {
        return !empty($analysisResult->findings);
    }
}

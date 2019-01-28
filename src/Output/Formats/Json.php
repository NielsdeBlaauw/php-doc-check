<?php
namespace NdB\PhpDocCheck\Output\Formats;

final class Json extends Format
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

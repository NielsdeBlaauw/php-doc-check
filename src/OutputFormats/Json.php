<?php
namespace NdB\PhpDocCheck\OutputFormats;

final class Json extends OutputFormat
{
    public function get() : string
    {
        $output = array();
        foreach ($this->analysisResults as $analysisResult) {
            if (!empty($analysisResult->findings) && !empty($analysisResult->sourceFile)) {
                $output[] = array(
                    'file'=>$analysisResult->sourceFile->file->getRealPath(),
                    'findings'=>$analysisResult->findings,
                );
            }
        }
        return (string) json_encode($output);
    }
}

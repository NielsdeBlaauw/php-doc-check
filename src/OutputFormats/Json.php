<?php
namespace NdB\PhpDocCheck\OutputFormats;

final class Json extends OutputFormat
{
    public function get()
    {
        $output = array();
        foreach ($this->analysisResults as $analysisResult) {
            if (!empty($analysisResult->findings)) {
                $output[] = array(
                    'file'=>$analysisResult->sourceFile->file->getRealPath(),
                    'findings'=>$analysisResult->findings,
                );
            }
        }
        return json_encode($output);
    }
}

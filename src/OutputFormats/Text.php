<?php
namespace NdB\PhpDocCheck\OutputFormats;

final class Text extends OutputFormat
{
    /**
     * Outputs findings for each file analysed in a table form
     */
    public function get() : string
    {
        $output = '';
        foreach ($this->analysisResults as $analysisResult) {
            if (!empty($analysisResult->findings) && !empty($analysisResult->sourceFile)) {
                $output .= "\n";
                $output .= sprintf("File: %s\n", $analysisResult->sourceFile->file->getRealPath());
                $header = array(
                    'Severity',
                    'Message',
                    'Line'
                );
                $rows = array();
                foreach ($analysisResult->findings as $finding) {
                    $rows[] = array(
                        $finding->getType(),
                        $finding->getMessage(),
                        $finding->getLine()
                    );
                }
                $lines = (new \cli\Table($header, $rows))->getDisplayLines();
                foreach ($lines as $line) {
                    $output .= $line. "\n";
                }
            }
        }
        return $output;
    }
}

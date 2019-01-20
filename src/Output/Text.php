<?php
namespace NdB\PhpDocCheck\Output;

class Text extends AbstractOutput
{
    /**
     * Outputs findings for each file analysed in a table form
     */
    public function get()
    {
        $output = '';
        foreach ($this->files as $file) {
            if (!empty($file->findings)) {
                $output .= "\n";
                $output .= sprintf("File: %s\n", $file->file->getRealPath());
                $header = array(
                    'Severity',
                    'Message',
                    'Line'
                );
                $rows = array();
                foreach ($file->findings as $finding) {
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

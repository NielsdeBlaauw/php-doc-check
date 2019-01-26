<?php
namespace NdB\PhpDocCheck\Output;

abstract class AbstractOutput
{
    public function __construct(array $files)
    {
        $this->files = $files;
    }

    public function display()
    {
        echo $this->get();
    }

    /**
     * Determines if this scan has 'failed' and should be fixed. Or if it was
     * flawless. CI will fail when a non zero exit code is returned.
     */
    public function getExitCode()
    {
        foreach ($this->files as $file) {
            if ($file->hasErrors || $file->hasWarnings) {
                return 1;
            }
        }
        return 0;
    }
}

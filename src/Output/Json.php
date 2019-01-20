<?php
namespace NdB\PhpDocCheck\Output;

class Json
{
    public function get()
    {
        $output = array();
        foreach ($this->files as $file) {
            if (!empty($file->findings)) {
                $output[] = array(
                    'file'=>$file->file->getRealPath(),
                    'findings'=>$file->findings,
                );
            }
        }
        return json_encode($output);
    }
}

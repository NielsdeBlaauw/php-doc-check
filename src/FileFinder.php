<?php
namespace NdB\PhpDocCheck;

class FileFinder
{
    /**
     * Builds the iterator that can find all files with multiple directories,
     * extensions and exluded folders.
     */
    public function getFiles(\GetOpt\GetOpt $arguments):\Iterator
    {
        $filesToAnalyse = new \AppendIterator();
        foreach ($arguments->getOperand('directory') as $directory) {
            $directoryIterator = new \RecursiveDirectoryIterator($directory);
            $directoryIterator = new \RecursiveIteratorIterator($directoryIterator);
            $filesToAnalyse->append($directoryIterator);
        }

        foreach ($arguments->getOption('file-extension') as $extension) {
            $filesToAnalyse = new \RegexIterator(
                $filesToAnalyse,
                '/^.+\.'.$extension.'$/i',
                \RecursiveRegexIterator::GET_MATCH
            );
        }

        foreach ($arguments->getOption('exclude') as $exclude) {
            $regex = '/^((?!'. preg_quote($exclude, '/').').)*$/i';
                $filesToAnalyse = new \RegexIterator(
                    $filesToAnalyse,
                    $regex,
                    \RecursiveRegexIterator::GET_MATCH,
                    \RegexIterator::USE_KEY
                );
        }
        return $filesToAnalyse;
    }
}

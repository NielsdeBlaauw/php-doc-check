<?php

namespace NdB\PhpDocCheck;

class ApplicationArgumentsProvider
{
    protected $getOpt;
    public function __construct()
    {
        $this->getOpt = new \GetOpt\GetOpt([
            \GetOpt\Option::create('x', 'exclude', \GetOpt\GetOpt::MULTIPLE_ARGUMENT)
                ->setDescription('Directories to exclude, without slash'),
            \GetOpt\Option::create('f', 'format', \GetOpt\GetOpt::REQUIRED_ARGUMENT)
                ->setDescription('Output format [text, json]')
                ->setDefaultValue('text'),
            \GetOpt\Option::create('o', 'reportFile', \GetOpt\GetOpt::REQUIRED_ARGUMENT)
                ->setDescription('Send report output to a file')
                ->setDefaultValue(''),
            \GetOpt\Option::create('m', 'metric', \GetOpt\GetOpt::REQUIRED_ARGUMENT)
                ->setDescription('Metric to use for determining complexity [cogntive, cyclomatic]')
                ->setDefaultValue('cognitive'),
            \GetOpt\Option::create('w', 'complexity-warning-treshold', \GetOpt\GetOpt::REQUIRED_ARGUMENT)
                ->setDescription('Cyclomatic complexity score which is the lower bound for a warning')
                ->setDefaultValue(4)->setValidation('is_numeric'),
            \GetOpt\Option::create('e', 'complexity-error-treshold', \GetOpt\GetOpt::REQUIRED_ARGUMENT)
                ->setDescription('Cyclomatic complexity score which is the lower bound for an error')
                ->setDefaultValue(6)->setValidation('is_numeric'),
            \GetOpt\Option::create('$', 'file-extension', \GetOpt\GetOpt::MULTIPLE_ARGUMENT)
                ->setDescription('Valid file extensions to scan')
                ->setDefaultValue('php'),
            \GetOpt\Option::create('i', 'ignore-violations-on-exit', \GetOpt\GetOpt::NO_ARGUMENT)
                ->setDescription('Will exit with a zero code, even if any violations are found'),
            \GetOpt\Option::create('?', 'help', \GetOpt\GetOpt::NO_ARGUMENT)
                ->setDescription('Show this help and quit'),
            \GetOpt\Option::create('q', 'quiet', \GetOpt\GetOpt::NO_ARGUMENT)
                ->setDescription('Don\'t show any output'),
        ]);
        $this->getOpt->addOperand(
            new \GetOpt\Operand(
                'directory',
                \GetOpt\Operand::MULTIPLE+\GetOpt\Operand::REQUIRED
            )
        );
    }

    /**
     * @SuppressWarnings(PHPMD.ExitExpression)
     */
    public function getArguments($arguments = null):\GetOpt\GetOpt
    {
        try {
            $this->getOpt->process($arguments);
        } catch (\GetOpt\ArgumentException $e) {
            echo $e->getMessage() . "\n";
            echo $this->getOpt->getHelpText();
            exit;
        }
        return $this->getOpt;
    }
}

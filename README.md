# php-doc-check

[![Latest Stable Version](https://poser.pugx.org/niels-de-blaauw/php-doc-check/v/stable)](https://packagist.org/packages/niels-de-blaauw/php-doc-check)
[![Build Status](https://travis-ci.org/NielsdeBlaauw/php-doc-check.svg?branch=master)](https://travis-ci.org/NielsdeBlaauw/php-doc-check)
[![License](https://poser.pugx.org/niels-de-blaauw/php-doc-check/license)](https://packagist.org/packages/niels-de-blaauw/php-doc-check)

PHP Doc Check is an automated command line tool to determine which functions and
methods could use some more documentation. 

By default this script:

- Emits a warning if there is no docblock for non trivial functions (score > 4)
- Emits an error if there is no docblock for complex functions (score > 6)

You can gradually improve documenation on projects by starting with relatively
high limits and slowly moving these limits down.

## Metrics

- [Cognitive Complexity](https://www.sonarsource.com/docs/CognitiveComplexity.pdf) `<default>` is 
used to find functions that are complex to read and understand.
- [Cyclomatic Complexity](https://en.wikipedia.org/wiki/Cyclomatic_complexity) is 
used to find complex functions with a more traditionl algorithm.


## Installation

### Composer

For now you have to install the beta version.

`composer require --dev niels-de-blaauw/php-doc-check:^0.2.0@dev`

### Phive

`phive install php-doc-check`

## Usage

```
$vendor/bin/php-doc-check -?
Usage: vendor/bin/php-doc-check [options] <directory> [<directory>...]

Options:
  -x, --exclude <arg>                       Directories to exclude, without
                                            slash
  -f, --format <arg>                        Output format [text, json]
                                            [default: text]
  -o, --reportFile <arg>                    Send report output to a file
  -m, --metric <arg>                        Metric to use for determining
                                            complexity [cognitive, cyclomatic]
                                            [default: cognitive]
  -w, --complexity-warning-threshold <arg>  Cyclomatic complexity score which
                                            is the lower bound for a warning
                                            [default: 4]
  -e, --complexity-error-threshold <arg>    Cyclomatic complexity score which
                                            is the lower bound for an error
                                            [default: 6]
  -$, --file-extension <arg>                Valid file extensions to scan
                                            [default: php]
  -g, --grouping-method <arg>               Allows different grouping of the
                                            results list [file, none, metric,
                                            severity, fileline] [default: file]
  -s, --sorting-method <arg>                Sorting for the results. Natural
                                            sorts by name for groups and line
                                            for findings. Value uses the
                                            cumulative group score, and finding
                                            score as sorting value. [natural,
                                            value] [default: natural]
  -i, --ignore-violations-on-exit           Will exit with a zero code, even if
                                            any violations are found
  -?, --help                                Show this help and quit
  -q, --quiet                               Don't show any output
```

Example first use: `vendor/bin/php-doc-check --exclude vendor ./`

## Examples

This is fine without docblocks (trivial method)

```php
public function get_title() : string{
    return strtoupper($this->title);
}
```

This could use some explanation

```php
/**
 * Limits the length of the title to a normal sentence, because older titles
 * tend to be longer then we can currently show.
 */
public function get_title() : string{
    if(strlen($this->title) > 20 ){
        if(strpos($this->title,'.') !== false && strpos($this->title,'.') < 20){
            [$title] = explode('.', $this->title, 2);
        }else{
            $title = substr($this->title, 0, 17) . '...';
        }
    }else{
        $title = $this->title;
    }
    return strtoupper($title);
}
```

## FAQ

Q: Why dont you want if there is no comment at all, regardless of complexity?

A: You can set this software to warn for all functions that are undocumented by
setting `--complexity-error-threshold 1`. However, if you want to force
documentation, you probably want to look into a tool like [php CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
in combination with documentation standards.

Q: Why isn't there a warning/error about complex functions and refactoring, 
regardless if they have a DocBlock?

A: You *should* refactor very complex functions. However, adding DocBlocks
for complex function is often easier and safer. This tool only checks the 
availability of this type of documentation. Other tools, like [php Mess Detector](https://github.com/phpmd/phpmd), 
can help you limit complexity.

## Issues

Issues are in the GitHub tracker: https://github.com/NielsdeBlaauw/php-doc-check/issues

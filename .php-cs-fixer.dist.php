<?php
declare(strict_types=1);

use PhpCsFixer\Config;

$finder = new PhpCsFixer\Finder()
    ->in(__DIR__)
    ->exclude("Examples")
;

$linterRules = json_decode(file_get_contents(__DIR__ . '/linter_rules.json'), true);

$presets = $linterRules['presets'];
$customRules = $linterRules['rules'];

$rules = array_merge($presets, $customRules);

return new PhpCsFixer\Config()
    ->setRules($rules)
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
    ->setUnsupportedPhpVersionAllowed(true)
;
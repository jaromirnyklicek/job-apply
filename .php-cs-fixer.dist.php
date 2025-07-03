<?php

$finder = PhpCsFixer\Finder::create()
    ->in([__DIR__ . '/src', __DIR__ . '/tests']);

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'strict_param' => true,
        'declare_strict_types' => true,
        'ordered_imports' => true,
    ])
    ->setFinder($finder);

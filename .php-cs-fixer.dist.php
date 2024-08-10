<?php
$header = <<<EOF
This file is part of tomkyle/find-run-test
EOF;

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests'
    ]);

return (new PhpCsFixer\Config())->setRules([
    '@PER-CS' => true,

    'header_comment' => [
        'comment_type' => 'PHPDoc',
        'header' => $header,
        'location' => 'after_open',
        'separate' => 'both',
    ]
])->setFinder($finder);

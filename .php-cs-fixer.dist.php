<?php

require_once __DIR__ . "/vendor/autoload.php";

$dirs = array_filter([
    __DIR__ . "/classes"
], static function (string $dir): bool {
    return is_dir($dir);
});

$finder = PhpCsFixer\Finder::create()
                           ->exclude([__DIR__ . "/vendor"])
                           ->in($dirs);

return (new PhpCsFixer\Config())
    ->setUsingCache(false)
    ->setFinder($finder)
    ->setRules([
        "@PSR12" => true,
        "strict_param" => false,
        "cast_spaces" => true,
        "concat_space" => ["spacing" => "one"],
        "unary_operator_spaces" => true,
        "function_typehint_space" => true,
        "binary_operator_spaces" => true,
        "array_syntax" => ["syntax" => "short"],
        "fully_qualified_strict_types" => ["import_symbols" => true],
        "no_superfluous_phpdoc_tags" => [
            "allow_mixed" => true,
            "remove_inheritdoc" => true,
        ],
        "no_empty_phpdoc" => true
    ]);

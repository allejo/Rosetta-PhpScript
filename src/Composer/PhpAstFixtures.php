<?php

namespace allejo\Rosetta\Composer;

use PhpParser\ParserFactory;
use Symfony\Component\Finder\Finder;

class PhpAstFixtures
{
    private const TESTS_DIR = __DIR__ . '/../../tests';

    public static function writePhpAstFixtures(): void
    {
        $parser = (new ParserFactory())->create(ParserFactory::ONLY_PHP5);
        $phpFiles = new Finder();
        $phpFiles
            ->in(self::TESTS_DIR . '/fixtures/php/')
            ->name('*.php')
            ->files()
        ;

        foreach ($phpFiles as $phpFile)
        {
            $content = $phpFile->getContents();
            $ast = $parser->parse($content);
            $targetFile = self::TESTS_DIR . '/fixtures/php-ast/' . $phpFile->getBasename('.php') . '.json';

            file_put_contents($targetFile, json_encode($ast, JSON_PRETTY_PRINT));
        }
    }
}

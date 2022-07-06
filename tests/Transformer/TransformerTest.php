<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\test\Transformer;

use allejo\Rosetta\Transformer\Transformer;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard as PrettyPrinter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

/**
 * @internal
 * @covers Transformer
 */
class TransformerTest extends TestCase
{
    private const BABEL_DIRECTORY = __DIR__ . '/../fixtures/babel/';
    private const PHP_DIRECTORY = __DIR__ . '/../fixtures/php/';

    private static Parser $parser;
    private static PrettyPrinter $printer;

    public static function setUpBeforeClass(): void
    {
        self::$parser = (new ParserFactory())->create(ParserFactory::ONLY_PHP5);
        self::$printer = new PrettyPrinter();
    }

    public function dataProvider_testTransformer(): iterable
    {
        foreach ($this->getBabelFixturePaths() as $babelFixture)
        {
            $fileNameNoExt = $babelFixture->getBasename('.json');

            yield [
                $this->getBabelFixture($babelFixture->getFilename()),
                $this->getPhpFixture($fileNameNoExt . '.php'),
            ];
        }
    }

    /**
     * @dataProvider dataProvider_testTransformer
     */
    public function testTransformer(string $babelJsonFixture, string $expectedPhpFixture): void
    {
        $transformer = new Transformer();
        $phpAST = $transformer->fromJsonStringToPhpAst($babelJsonFixture);

        self::assertPhpAstEqualsSource($phpAST, $expectedPhpFixture);
    }

    private function getBabelFixture(string $filename): string
    {
        return file_get_contents(self::BABEL_DIRECTORY . $filename);
    }

    private function getBabelFixturePaths(): \Traversable
    {
        return (new Finder())
            ->in(self::BABEL_DIRECTORY)
            ->files()
            ->getIterator()
        ;
    }

    private function getPhpFixture(string $filename): string
    {
        return file_get_contents(self::PHP_DIRECTORY . $filename);
    }

    private static function assertPhpAstEqualsSource($ast, string $source): void
    {
        $sourceAst = self::$parser->parse($source);

        $actualCode = self::$printer->prettyPrint($ast);
        $expectedCode = self::$printer->prettyPrint($sourceAst);

        self::assertEquals($expectedCode, $actualCode);
    }
}

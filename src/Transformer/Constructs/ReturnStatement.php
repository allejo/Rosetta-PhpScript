<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Transformer\Constructs;

use allejo\Rosetta\Babel\ReturnStatement as BabelReturnStatement;
use allejo\Rosetta\Transformer\Transformer;
use PhpParser\Node\Stmt\Return_;

/**
 * @implements PhpConstructInterface<BabelReturnStatement, Return_>
 */
class ReturnStatement implements PhpConstructInterface
{
    /**
     * @param BabelReturnStatement $babelConstruct
     */
    public static function fromBabel($babelConstruct, Transformer $transformer): Return_
    {
        return new Return_($transformer->fromBabelAstToPhpAst($babelConstruct->argument));
    }

    public static function getConstructName(): string
    {
        return 'ReturnStatement';
    }
}

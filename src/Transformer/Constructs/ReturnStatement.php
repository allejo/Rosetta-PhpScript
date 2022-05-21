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
 * @implements ConstructInterface<BabelReturnStatement, Return_>
 */
class ReturnStatement implements ConstructInterface
{
    /**
     * @param BabelReturnStatement $babelConstruct
     */
    public static function fromBabel($babelConstruct): Return_
    {
        return new Return_(Transformer::babelAstToPhp($babelConstruct->argument));
    }
}

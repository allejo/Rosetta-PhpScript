<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Transformer\Constructs;

use allejo\Rosetta\Babel\Identifier as BabelIdentifier;
use allejo\Rosetta\Transformer\Transformer;
use allejo\Rosetta\Utilities\PhpAstHelpers;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\Variable;

/**
 * @implements PhpConstructInterface<BabelIdentifier, ConstFetch|Variable>
 */
class Identifier implements PhpConstructInterface
{
    /**
     * @param BabelIdentifier $babelConstruct
     *
     * @return ConstFetch|Variable
     */
    public static function fromBabel($babelConstruct, Transformer $transformer)
    {
        // `undefined` in JS according to Babel is just a special identifier
        if ($babelConstruct->name === 'undefined')
        {
            return PhpAstHelpers::makeNullAst();
        }

        return new Variable($babelConstruct->name);
    }

    public static function getConstructName(): string
    {
        return 'Identifier';
    }
}

<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Transformer\Constructs;

use allejo\Rosetta\Babel\Identifier as BabelIdentifier;
use PhpParser\Node\Expr\Variable;

/**
 * @implements ConstructInterface<BabelIdentifier, Variable>
 */
class Identifier implements ConstructInterface
{
    /**
     * @param BabelIdentifier $babelConstruct
     */
    public static function fromBabel($babelConstruct): Variable
    {
        return new Variable($babelConstruct->name);
    }
}

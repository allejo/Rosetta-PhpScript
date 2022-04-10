<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Transformer\Constructs;

use allejo\Rosetta\Babel\BooleanLiteral as BabelBooleanLiteral;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;

/**
 * @implements ConstructInterface<BabelBooleanLiteral, ConstFetch>
 */
class BooleanLiteral implements ConstructInterface
{
    /**
     * @param BabelBooleanLiteral $babelConstruct
     */
    public static function fromBabel($babelConstruct): ConstFetch
    {
        return new ConstFetch(new Name($babelConstruct->value ? 'true' : 'false'));
    }
}

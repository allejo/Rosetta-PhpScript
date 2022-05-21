<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Transformer\Constructs;

use allejo\Rosetta\Babel\StringLiteral as BabelStringLiteral;
use PhpParser\Node\Scalar\String_;

/**
 * @implements ConstructInterface<BabelStringLiteral, String_>
 */
class StringLiteral implements ConstructInterface
{
    /**
     * @param BabelStringLiteral $babelConstruct
     */
    public static function fromBabel($babelConstruct): String_
    {
        return new String_($babelConstruct->value);
    }
}
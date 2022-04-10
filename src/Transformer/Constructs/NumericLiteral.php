<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Transformer\Constructs;

use allejo\Rosetta\Babel\NumericLiteral as BabelNumericLiteral;
use allejo\Rosetta\Exception\UnsupportedConstructException;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\LNumber;

/**
 * @implements ConstructInterface<BabelNumericLiteral, DNumber|LNumber>
 */
class NumericLiteral implements ConstructInterface
{
    /**
     * @param BabelNumericLiteral $babelConstruct
     *
     * @throws UnsupportedConstructException
     *
     * @return DNumber|LNumber
     */
    public static function fromBabel($babelConstruct)
    {
        if (is_float($babelConstruct->value))
        {
            return new DNumber($babelConstruct->value);
        }

        if (is_int($babelConstruct->value))
        {
            return new LNumber($babelConstruct->value);
        }

        throw new UnsupportedConstructException("Could not determine type of number for: {$babelConstruct->value}");
    }
}

<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Transformer\Constructs;

use allejo\Rosetta\Babel\BinaryExpression as BabelBinaryExpression;
use allejo\Rosetta\Transformer\Transformer;
use PhpParser\Node\Expr\BinaryOp\Concat;

/**
 * @implements ConstructInterface<BabelBinaryExpression, Concat>
 */
class BinaryExpression implements ConstructInterface
{
    /**
     * @param BabelBinaryExpression $babelConstruct
     */
    public static function fromBabel($babelConstruct): Concat
    {
        $leftConstruct = Transformer::babelAstToPhp($babelConstruct->left);
        $rightConstruct = Transformer::babelAstToPhp($babelConstruct->right);
        $result = null;

        if ($babelConstruct->operator === '+')
        {
            $result = new Concat($leftConstruct, $rightConstruct);
        }

        return $result;
    }
}

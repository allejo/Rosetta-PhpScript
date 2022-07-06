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
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\BinaryOp\Plus;
use PhpParser\Node\Scalar\String_;

/**
 * @implements PhpConstructInterface<BabelBinaryExpression, BinaryOp>
 */
class BinaryExpression implements PhpConstructInterface
{
    /**
     * @param BabelBinaryExpression $babelConstruct
     */
    public static function fromBabel($babelConstruct, Transformer $transformer): BinaryOp
    {
        $leftConstruct = $transformer->babelAstToPhp($babelConstruct->left);
        $rightConstruct = $transformer->babelAstToPhp($babelConstruct->right);
        $result = null;

        if ($babelConstruct->operator === '+')
        {
            $areBothStrings = $leftConstruct instanceof String_ && $rightConstruct instanceof String_;
            $oneIsConcat = $leftConstruct instanceof Concat || $rightConstruct instanceof Concat;

            if ($areBothStrings || $oneIsConcat)
            {
                $result = new Concat($leftConstruct, $rightConstruct);
            }
            else
            {
                $result = new Plus($leftConstruct, $rightConstruct);
            }
        }

        return $result;
    }

    public static function getConstructName(): string
    {
        return 'BinaryExpression';
    }
}

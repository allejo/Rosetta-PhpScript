<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Transformer\Constructs;

use allejo\Rosetta\Babel\VariableDeclarator as BabelVariableDeclarator;
use allejo\Rosetta\Transformer\Transformer;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\Variable as PHPVariable;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Expression as PHPExpression;

/**
 * @implements ConstructInterface<BabelVariableDeclarator, PHPExpression>
 */
class VariableDeclarator implements ConstructInterface
{
    /**
     * @param BabelVariableDeclarator $babelConstruct
     */
    public static function fromBabel($babelConstruct): PHPExpression
    {
        $variable = new PHPVariable($babelConstruct->id->name);
        $value = null;

        if ($babelConstruct->init !== null)
        {
            $value = Transformer::babelAstToPhp($babelConstruct->init);
        }

        if ($value === null)
        {
            $value = new ConstFetch(new Name('null'));
        }

        return new PHPExpression(new Assign($variable, $value));
    }
}

<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Transformer\Constructs;

use allejo\Rosetta\Babel\AssignmentExpression as BabelAssignmentExpressionAlias;
use allejo\Rosetta\Transformer\Transformer;
use PhpParser\Node\Expr\Assign;

/**
 * @implements PhpConstructInterface<BabelAssignmentExpressionAlias, Assign>
 */
class AssignmentExpression implements PhpConstructInterface
{
    public static function getConstructName(): string
    {
        return 'AssignmentExpression';
    }

    /**
     * @param BabelAssignmentExpressionAlias $babelConstruct
     */
    public static function fromBabel($babelConstruct, Transformer $transformer): Assign
    {
        return new Assign($transformer->fromBabelAstToPhpAst($babelConstruct->left), $transformer->fromBabelAstToPhpAst($babelConstruct->right));
    }
}

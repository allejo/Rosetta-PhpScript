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
use PhpParser\Comment\Doc;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Expression;

/**
 * @implements PhpConstructInterface<BabelVariableDeclarator, Expression>
 */
class VariableDeclarator implements PhpConstructInterface
{
    /**
     * @param BabelVariableDeclarator $babelConstruct
     */
    public static function fromBabel($babelConstruct, Transformer $transformer): Expression
    {
        $variable = new Variable($babelConstruct->id->name);
        $value = null;

        if ($babelConstruct->init !== null)
        {
            $value = $transformer->babelAstToPhp($babelConstruct->init);
        }

        $addWarning = false;

        if ($value === null)
        {
            $value = new ConstFetch(new Name('null'));
            $addWarning = true;
        }

        $exp = new Expression(new Assign($variable, $value));

        if ($addWarning)
        {
            $msg = sprintf('Rosetta-PhpScript :: Unsupported variable type, defaulting to null (%s)', $babelConstruct->init->type);
            $exp->setDocComment(new Doc($msg));
        }

        return $exp;
    }

    public static function getConstructName(): string
    {
        return 'VariableDeclarator';
    }
}

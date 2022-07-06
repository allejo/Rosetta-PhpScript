<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Transformer\Constructs;

use allejo\Rosetta\Babel\ArrowFunctionExpression as BabelArrowFunctionExpression;
use allejo\Rosetta\Transformer\Transformer;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;

/**
 * @implements PhpConstructInterface<BabelArrowFunctionExpression, Closure>
 */
class ArrowFunctionExpression implements PhpConstructInterface
{
    /**
     * @param BabelArrowFunctionExpression $babelConstruct
     */
    public static function fromBabel($babelConstruct, Transformer $transformer): Closure
    {
        /** @var Param[] $params */
        $params = [];

        foreach ($babelConstruct->params as $param)
        {
            $params[] = new Param(new Variable($param->name));
        }

        return new Closure([
            'params' => $params,
            'stmts' => $transformer->babelAstToPhp($babelConstruct->body),
        ]);
    }

    public static function getConstructName(): string
    {
        return 'ArrowFunctionExpression';
    }
}

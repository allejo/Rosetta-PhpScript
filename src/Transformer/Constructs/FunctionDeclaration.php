<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Transformer\Constructs;

use allejo\Rosetta\Babel\FunctionDeclaration as BabelFunctionDeclaration;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Function_;

/**
 * @implements ConstructInterface<BabelFunctionDeclaration, Function_>
 */
class FunctionDeclaration implements ConstructInterface
{
    /**
     * @param BabelFunctionDeclaration $babelConstruct
     */
    public static function fromBabel($babelConstruct): Function_
    {
        $function = new Function_($babelConstruct->id->name);

        foreach ($babelConstruct->params as $param)
        {
            if ($param->type !== 'Identifier')
            {
                continue;
            }

            $function->params[] = new Param(new Variable($param->name));
        }

        return $function;
    }
}
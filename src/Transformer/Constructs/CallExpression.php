<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Transformer\Constructs;

use allejo\Rosetta\Babel\CallExpression as BabelCallExpression;
use allejo\Rosetta\Exception\UnsupportedConstructException;
use allejo\Rosetta\Transformer\Transformer;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;

/**
 * @implements PhpConstructInterface<BabelCallExpression, MethodCall>
 */
class CallExpression implements PhpConstructInterface
{
    public static function getConstructName(): string
    {
        return 'CallExpression';
    }

    /**
     * @param BabelCallExpression $babelConstruct
     *
     * @throws UnsupportedConstructException
     */
    public static function fromBabel($babelConstruct, Transformer $transformer): MethodCall
    {
        if ($babelConstruct->type !== 'CallExpression')
        {
            throw new UnsupportedConstructException("No support for handling a callee of type {$babelConstruct->type}");
        }

        /** @var PropertyFetch $callee */
        $callee = $transformer->fromBabelAstToPhpAst($babelConstruct->callee);

        return new MethodCall($callee->var, $callee->name);
    }
}

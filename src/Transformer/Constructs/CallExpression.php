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
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;

/**
 * @implements PhpConstructInterface<BabelCallExpression, FuncCall|MethodCall>
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
    public static function fromBabel($babelConstruct, Transformer $transformer): FuncCall|MethodCall
    {
        if ($babelConstruct->type !== 'CallExpression')
        {
            throw new UnsupportedConstructException("No support for handling a callee of type {$babelConstruct->type}");
        }

        /** @var PropertyFetch|Variable $callee */
        $callee = $transformer->fromBabelAstToPhpAst($babelConstruct->callee);
        $args = array_map(static fn ($arg) => $transformer->fromBabelAstToPhpAst($arg), $babelConstruct->arguments);

        if ($callee instanceof PropertyFetch)
        {
            return new MethodCall($callee->var, $callee->name, $args);
        }

        if ($callee instanceof Variable)
        {
            return new FuncCall(new Name($callee->name), $args);
        }

        throw new UnsupportedConstructException(sprintf('No support for transforming a CallExpression from a %s', $callee::class));
    }
}

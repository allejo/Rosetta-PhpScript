<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Transformer\Constructs;

use allejo\Rosetta\Babel\MemberExpression as BabelMemberExpression;
use allejo\Rosetta\Exception\UnsupportedConstructException;
use allejo\Rosetta\Transformer\Transformer;
use PhpParser\Node\Expr\PropertyFetch;

/**
 * @implements PhpConstructInterface<BabelMemberExpression, PropertyFetch>
 *
 * @see https://github.com/babel/babel/blob/v7.17.8/packages/babel-parser/ast/spec.md#memberexpression
 */
class MemberExpression implements PhpConstructInterface
{
    public static function getConstructName(): string
    {
        return 'MemberExpression';
    }

    /**
     * @param BabelMemberExpression $babelConstruct
     *
     * @throws UnsupportedConstructException
     */
    public static function fromBabel($babelConstruct, Transformer $transformer): PropertyFetch
    {
        $target = $transformer->fromBabelAstToPhpAst($babelConstruct->object);
        $property = $babelConstruct->property;

        // In JS, a computed property is `a[b]` meaning we should translate this to a PHP AST object
        if ($babelConstruct->computed)
        {
            $phpProperty = $transformer->fromBabelAstToPhpAst($babelConstruct->property);
        }
        elseif ($property->type === 'PrivateName')
        {
            $phpProperty = $property->id->name;
        }
        elseif ($property->type === 'Identifier')
        {
            $phpProperty = $property->name;
        }

        if (!isset($phpProperty))
        {
            throw new UnsupportedConstructException("Could not handle given property type for Babel construct of type {$babelConstruct->type}");
        }

        return new PropertyFetch($target, $phpProperty);
    }
}

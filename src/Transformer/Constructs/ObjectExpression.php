<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Transformer\Constructs;

use allejo\Rosetta\Babel\ObjectExpression as BabelObjectExpression;
use allejo\Rosetta\Transformer\Transformer;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Cast\Object_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;

/**
 * @implements PhpConstructInterface<BabelObjectExpression, Object_>
 */
class ObjectExpression implements PhpConstructInterface
{
    /**
     * @param BabelObjectExpression $babelConstruct
     */
    public static function fromBabel($babelConstruct, Transformer $transformer): Object_
    {
        /** @var ArrayItem[] $items */
        $items = [];

        foreach ($babelConstruct->properties as $property)
        {
            if ($property->key->type !== 'Identifier')
            {
                continue;
            }

            $key = $property->computed
                ? new Variable($property->key->name)
                : new String_($property->key->name);
            $value = $transformer->fromBabelAstToPhpAst($property->value);

            $items[] = new ArrayItem($value, $key);
        }

        return new Object_(new Array_($items, ['kind' => Array_::KIND_SHORT]));
    }

    public static function getConstructName(): string
    {
        return 'ObjectExpression';
    }
}

<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Transformer\Constructs;

use allejo\Rosetta\Babel\ArrayExpression as BabelArrayExpression;
use allejo\Rosetta\Transformer\Transformer;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;

/**
 * @implements ConstructInterface<BabelArrayExpression, Array_>
 */
class ArrayExpression implements ConstructInterface
{
    /**
     * @param BabelArrayExpression $babelConstruct
     */
    public static function fromBabel($babelConstruct): Array_
    {
        /** @var ArrayItem[] $items */
        $items = [];

        foreach ($babelConstruct->elements as $element)
        {
            $element = Transformer::babelAstToPhp($element);
            $items[] = new ArrayItem($element);
        }

        return new Array_($items, ['kind' => Array_::KIND_LONG]);
    }
}

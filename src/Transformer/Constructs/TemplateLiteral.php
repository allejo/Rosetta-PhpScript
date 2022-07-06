<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Transformer\Constructs;

use allejo\Rosetta\Babel\TemplateLiteral as BabelTemplateLiteral;
use allejo\Rosetta\Transformer\Transformer;
use allejo\Rosetta\Utilities\ArrayUtils;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\Encapsed;
use PhpParser\Node\Scalar\EncapsedStringPart;
use PhpParser\Node\Scalar\String_;

/**
 * @implements PhpConstructInterface<BabelTemplateLiteral, Encapsed|String_>
 */
class TemplateLiteral implements PhpConstructInterface
{
    /**
     * @param BabelTemplateLiteral $babelConstruct
     *
     * @return Encapsed|String_
     */
    public static function fromBabel($babelConstruct, Transformer $transformer)
    {
        /** @var array<int, array<int, Variable>> $parts */
        $parts = [];

        /** @var null|string $concatenated */
        $concatenated = null;
        $hasExpressions = false;

        foreach ($babelConstruct->expressions as $expression)
        {
            $hasExpressions = true;

            if ($expression->type === 'Identifier')
            {
                $position = $expression->loc->start;
                $parts[$position->line][$position->column] = $transformer->babelAstToPhp($expression);
            }
        }

        foreach ($babelConstruct->quasis as $quasi)
        {
            $position = $quasi->loc->start;

            if ($hasExpressions)
            {
                $parts[$position->line][$position->column] = new EncapsedStringPart($quasi->value->cooked);
            }
            else
            {
                $concatenated .= $quasi->value->cooked;
            }
        }

        if ($hasExpressions)
        {
            ArrayUtils::recursiveKsort($parts);

            return new Encapsed(ArrayUtils::flatten($parts));
        }

        return new String_($concatenated);
    }

    public static function getConstructName(): string
    {
        return 'TemplateLiteral';
    }
}

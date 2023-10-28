<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Transformer\Constructs;

use allejo\Rosetta\Babel\ExpressionStatement as BabelExpressionStatement;
use allejo\Rosetta\Exception\UnsupportedConstructException;
use allejo\Rosetta\Transformer\Transformer;
use PhpParser\Node\Stmt\Expression;

/**
 * @implements PhpConstructInterface<BabelExpressionStatement, Expression>
 */
class ExpressionStatement implements PhpConstructInterface
{
    public static function getConstructName(): string
    {
        return 'ExpressionStatement';
    }

    /**
     * @param BabelExpressionStatement $babelConstruct
     *
     * @throws UnsupportedConstructException
     */
    public static function fromBabel($babelConstruct, Transformer $transformer)
    {
        return match ($babelConstruct->expression->type) {
            'AssignmentExpression', 'CallExpression' => new Expression($transformer->fromBabelAstToPhpAst($babelConstruct->expression)),
            default => throw new UnsupportedConstructException(sprintf('No support for transforming a ExpressionStatement from a %s', $babelConstruct->expression::class)),
        };
    }
}

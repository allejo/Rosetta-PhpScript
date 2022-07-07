<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Transformer\Constructs;

use allejo\Rosetta\Babel\NullLiteral as BabelNullLiteral;
use allejo\Rosetta\Transformer\Transformer;
use allejo\Rosetta\Utilities\PhpAstHelpers;
use PhpParser\Node\Expr\ConstFetch;

/**
 * @implements PhpConstructInterface<BabelNullLiteral, ConstFetch>
 */
class NullLiteral implements PhpConstructInterface
{
    public static function getConstructName(): string
    {
        return 'NullLiteral';
    }

    /**
     * @param BabelNullLiteral $babelConstruct
     */
    public static function fromBabel($babelConstruct, Transformer $transformer): ConstFetch
    {
        return PhpAstHelpers::makeNullAst();
    }
}

<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

use allejo\Rosetta\Babel\RegExpLiteral as BabelRegExpLiteral;
use allejo\Rosetta\Transformer\Constructs\PhpConstructInterface;
use allejo\Rosetta\Transformer\Transformer;
use PhpParser\Node\Scalar\String_;

/**
 * @implements PhpConstructInterface<BabelRegExpLiteral, String_>
 */
class RegExpLiteral implements PhpConstructInterface
{
    public static function getConstructName(): string
    {
        return 'RegExpLiteral';
    }

    /**
     * @param BabelRegExpLiteral $babelConstruct
     */
    public static function fromBabel($babelConstruct, Transformer $transformer): String_
    {
        $strVal = sprintf('/%s/%s', preg_quote($babelConstruct->pattern, '/'), $babelConstruct->flags);

        return new String_($strVal);
    }
}

$transformer = new Transformer();
$transformer->registerTransformer(RegExpLiteral::class);

return $transformer;

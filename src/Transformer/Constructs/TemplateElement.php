<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Transformer\Constructs;

use allejo\Rosetta\Babel\TemplateElement as BabelTemplateElement;
use allejo\Rosetta\Transformer\Transformer;
use PhpParser\Node\Scalar\String_;

/**
 * @implements PhpConstructInterface<BabelTemplateElement, String_>
 */
class TemplateElement implements PhpConstructInterface
{
    /**
     * @param BabelTemplateElement $babelConstruct
     */
    public static function fromBabel($babelConstruct, Transformer $transformer)
    {
        // TODO: Implement fromBabel() method.
    }

    public static function getConstructName(): string
    {
        return 'TemplateElement';
    }
}

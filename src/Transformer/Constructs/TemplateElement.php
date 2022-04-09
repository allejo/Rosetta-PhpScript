<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Transformer\Constructs;

use allejo\Rosetta\Babel\TemplateElement as BabelTemplateElement;
use PhpParser\Node\Scalar\String_;

/**
 * @implements ConstructInterface<BabelTemplateElement, String_>
 */
class TemplateElement implements ConstructInterface
{
    /**
     * @param BabelTemplateElement $babelConstruct
     */
    public static function fromBabel($babelConstruct)
    {
        // TODO: Implement fromBabel() method.
    }
}

<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Babel;

class TemplateElement extends Node
{
    public $type = 'TemplateElement';

    /** @var bool */
    public $tail;

    /** @var array{cooked: null|string, raw: string} */
    public $value;
}

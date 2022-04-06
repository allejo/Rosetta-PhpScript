<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Babel;

class ClassProperty extends Node
{
    public $type = 'ClassProperty';

    /** @var Expression */
    public $key;

    /** @var Expression */
    public $value;

    /** @var bool */
    public $static;

    /** @var bool */
    public $computed;
}

<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Babel;

class ObjectMember extends Node
{
    /** @var Expression */
    public $key;

    /** @var bool */
    public $computed;

    /** @var [ Decorator ] */
    public $decorators;
}

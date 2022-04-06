<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Babel;

class ClassPrivateMethod extends Function_
{
    public $type = 'ClassPrivateMethod';

    /** @var PrivateName */
    public $key;

    /** @var "get"|"method"|"set" */
    public $kind;

    /** @var bool */
    public $static;

    /** @var [ Decorator ] */
    public $decorators;
}

<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Babel;

class ClassMethod extends Function_
{
    public $type = 'ClassMethod';

    /** @var Expression */
    public $key;

    /** @var "constructor"|"get"|"method"|"set" */
    public $kind;

    /** @var bool */
    public $computed;

    /** @var bool */
    public $static;

    /** @var [ Decorator ] */
    public $decorators;
}

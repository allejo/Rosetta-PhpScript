<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Babel;

class ConditionalExpression extends Expression
{
    public $type = 'ConditionalExpression';

    /** @var Expression */
    public $test;

    /** @var Expression */
    public $alternate;

    /** @var Expression */
    public $consequent;
}

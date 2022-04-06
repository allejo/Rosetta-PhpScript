<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Babel;

class AssignmentExpression extends Expression
{
    public $type = 'AssignmentExpression';

    /** @var AssignmentOperator */
    public $operator;

    /** @var Expression|Pattern */
    public $left;

    /** @var Expression */
    public $right;
}

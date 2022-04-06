<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Babel;

class TryStatement extends Statement
{
    public $type = 'TryStatement';

    /** @var BlockStatement */
    public $block;

    /** @var null|CatchClause */
    public $handler;

    /** @var null|BlockStatement */
    public $finalizer;
}

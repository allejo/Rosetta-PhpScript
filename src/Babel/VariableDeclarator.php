<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Babel;

class VariableDeclarator extends Node
{
    public $type = 'VariableDeclarator';

    /** @var Identifier|Pattern */
    public $id;

    public ?Expression $init;
}

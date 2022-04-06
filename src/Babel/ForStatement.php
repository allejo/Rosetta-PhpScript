<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Babel;

class ForStatement extends Statement
{
    public $type = 'ForStatement';

    /** @var null|Expression|VariableDeclaration */
    public $init;

    /** @var null|Expression */
    public $test;

    /** @var null|Expression */
    public $update;

    /** @var Statement */
    public $body;
}

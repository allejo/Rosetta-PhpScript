<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Utilities;

use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;

/**
 * @internal
 */
abstract class PhpAstHelpers
{
    public static function makeNullAst(): ConstFetch
    {
        return new ConstFetch(new Name('null'));
    }
}

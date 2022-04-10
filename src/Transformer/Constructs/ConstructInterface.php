<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Transformer\Constructs;

use allejo\Rosetta\Exception\UnsupportedConstructException;

/**
 * @template B
 * @template P
 */
interface ConstructInterface
{
    /**
     * @param B $babelConstruct
     *
     * @throws UnsupportedConstructException
     *
     * @return P
     */
    public static function fromBabel($babelConstruct);
}

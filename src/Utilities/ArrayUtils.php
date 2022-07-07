<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Utilities;

/**
 * @internal
 */
abstract class ArrayUtils
{
    /**
     * @template T
     *
     * @param array<T> $arr
     *
     * @return array<T>
     */
    public static function flatten(array &$arr): array
    {
        $flattened = [];
        array_walk_recursive($arr, function ($a) use (&$flattened) {
            $flattened[] = $a;
        });

        return $flattened;
    }

    public static function recursiveKsort(array &$arr): void
    {
        foreach ($arr as &$item)
        {
            if (is_array($item))
            {
                self::recursiveKsort($item);
            }
        }

        unset($item);
        ksort($arr);
    }
}

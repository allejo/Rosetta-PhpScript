<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Transformer\Constructs;

use allejo\Rosetta\Babel\BlockStatement as BabelBlockStatementAlias;
use allejo\Rosetta\Transformer\Transformer;
use PhpParser\Node\Stmt;

/**
 * @implements ConstructInterface<BabelBlockStatementAlias, Stmt[]>
 */
class BlockStatement implements ConstructInterface
{
    /**
     * @param BabelBlockStatementAlias $babelConstruct
     *
     * @return Stmt[]
     */
    public static function fromBabel($babelConstruct): array
    {
        /** @var Stmt[] $statements */
        $statements = [];

        foreach ($babelConstruct->body as $stmt)
        {
            $statements[] = Transformer::babelAstToPhp($stmt);
        }

        return $statements;
    }
}

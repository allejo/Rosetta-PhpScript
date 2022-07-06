<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Transformer\Constructs;

use allejo\Rosetta\Babel\VariableDeclaration as BabelVariableDeclaration;
use allejo\Rosetta\Transformer\Transformer;
use PhpParser\Node\Expr\Variable;

/**
 * @implements PhpConstructInterface<BabelVariableDeclaration, Variable[]>
 */
class VariableDeclaration implements PhpConstructInterface
{
    /**
     * @param BabelVariableDeclaration $babelConstruct
     *
     * @return Variable[]
     */
    public static function fromBabel($babelConstruct, Transformer $transformer): array
    {
        return array_map(static function ($declaration) use ($transformer) {
            return $transformer->fromBabelAstToPhpAst($declaration);
        }, $babelConstruct->declarations);
    }

    public static function getConstructName(): string
    {
        return 'VariableDeclaration';
    }
}

<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Transformer;

use allejo\Rosetta\Babel\Program;
use allejo\Rosetta\Transformer\Constructs\FunctionDeclaration;
use allejo\Rosetta\Transformer\Constructs\StringLiteral;
use allejo\Rosetta\Transformer\Constructs\VariableDeclaration;
use allejo\Rosetta\Transformer\Constructs\VariableDeclarator;

class Transformer
{
    private static array $transformers = [
        'FunctionDeclaration' => FunctionDeclaration::class,
        'StringLiteral' => StringLiteral::class,
        'VariableDeclaration' => VariableDeclaration::class,
        'VariableDeclarator' => VariableDeclarator::class,
    ];

    public function fromJsonAST(string $json): array
    {
        $file = json_decode($json);

        if (!property_exists($file, 'program'))
        {
            throw new \Exception('No `program` definition found in this AST.');
        }

        /** @var Program $program */
        $program = $file->program;
        $output = [];

        foreach ($program->body as $element)
        {
            $transformed = self::babelAstToPhp($element);

            if ($transformed === null)
            {
                continue;
            }

            $output[] = $transformed;
        }

        $flattened = [];
        array_walk_recursive($output, function ($a) use (&$flattened) {
            $flattened[] = $a;
        });

        return $flattened;
    }

    public static function babelAstToPhp($babelAst)
    {
        if (!array_key_exists($babelAst->type, self::$transformers))
        {
            return null;
        }

        $transformer = self::$transformers[$babelAst->type];

        return $transformer::fromBabel($babelAst);
    }
}

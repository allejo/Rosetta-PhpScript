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

class Transformer
{
    private static $transformers = [
        'FunctionDeclaration' => FunctionDeclaration::class,
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
            if (!array_key_exists($element->type, self::$transformers))
            {
                continue;
            }

            $transformer = self::$transformers[$element->type];
            $output[] = $transformer::fromBabel($element);
        }

        return $output;
    }
}

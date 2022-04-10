<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Transformer;

use allejo\Rosetta\Babel\Program;
use allejo\Rosetta\Exception\UnsupportedConstructException;
use allejo\Rosetta\Transformer\Constructs\BinaryExpression;
use allejo\Rosetta\Transformer\Constructs\BooleanLiteral;
use allejo\Rosetta\Transformer\Constructs\FunctionDeclaration;
use allejo\Rosetta\Transformer\Constructs\NumericLiteral;
use allejo\Rosetta\Transformer\Constructs\StringLiteral;
use allejo\Rosetta\Transformer\Constructs\TemplateElement;
use allejo\Rosetta\Transformer\Constructs\TemplateLiteral;
use allejo\Rosetta\Transformer\Constructs\VariableDeclaration;
use allejo\Rosetta\Transformer\Constructs\VariableDeclarator;
use allejo\Rosetta\Utilities\ArrayUtils;
use PhpParser\Comment\Doc;
use PhpParser\Node;

class Transformer
{
    private static array $transformers = [
        'BinaryExpression' => BinaryExpression::class,
        'BooleanLiteral' => BooleanLiteral::class,
        'FunctionDeclaration' => FunctionDeclaration::class,
        'NumericLiteral' => NumericLiteral::class,
        'StringLiteral' => StringLiteral::class,
        'TemplateElement' => TemplateElement::class,
        'TemplateLiteral' => TemplateLiteral::class,
        'VariableDeclaration' => VariableDeclaration::class,
        'VariableDeclarator' => VariableDeclarator::class,
    ];

    /**
     * @throws \Exception
     *
     * @return Node[]
     */
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

        return ArrayUtils::flatten($output);
    }

    public static function babelAstToPhp($babelAst)
    {
        if (!array_key_exists($babelAst->type, self::$transformers))
        {
            return null;
        }

        $transformer = self::$transformers[$babelAst->type];

        try
        {
            return $transformer::fromBabel($babelAst);
        }
        catch (UnsupportedConstructException $e)
        {
            return new Doc(sprintf('Rosetta-PhpScript :: %s', $e->getMessage()));
        }
    }
}

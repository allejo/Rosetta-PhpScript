<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Transformer;

use allejo\Rosetta\Babel\Node as BabelNode;
use allejo\Rosetta\Babel\Program;
use allejo\Rosetta\Exception\UnsupportedConstructException;
use allejo\Rosetta\Transformer\Constructs\ArrayExpression;
use allejo\Rosetta\Transformer\Constructs\ArrowFunctionExpression;
use allejo\Rosetta\Transformer\Constructs\BinaryExpression;
use allejo\Rosetta\Transformer\Constructs\BlockStatement;
use allejo\Rosetta\Transformer\Constructs\BooleanLiteral;
use allejo\Rosetta\Transformer\Constructs\ConstructInterface;
use allejo\Rosetta\Transformer\Constructs\FunctionDeclaration;
use allejo\Rosetta\Transformer\Constructs\Identifier;
use allejo\Rosetta\Transformer\Constructs\NumericLiteral;
use allejo\Rosetta\Transformer\Constructs\ObjectExpression;
use allejo\Rosetta\Transformer\Constructs\ReturnStatement;
use allejo\Rosetta\Transformer\Constructs\StringLiteral;
use allejo\Rosetta\Transformer\Constructs\TemplateElement;
use allejo\Rosetta\Transformer\Constructs\TemplateLiteral;
use allejo\Rosetta\Transformer\Constructs\VariableDeclaration;
use allejo\Rosetta\Transformer\Constructs\VariableDeclarator;
use allejo\Rosetta\Utilities\ArrayUtils;
use PhpParser\Comment\Doc;
use PhpParser\Node as PHPNode;
use PhpParser\Node\Expr as PHPExpression;

class Transformer
{
    /** @var array<string, class-string<ConstructInterface>> */
    private static array $transformers = [
        'ArrayExpression' => ArrayExpression::class,
        'ArrowFunctionExpression' => ArrowFunctionExpression::class,
        'BinaryExpression' => BinaryExpression::class,
        'BlockStatement' => BlockStatement::class,
        'BooleanLiteral' => BooleanLiteral::class,
        'FunctionDeclaration' => FunctionDeclaration::class,
        'Identifier' => Identifier::class,
        'ObjectExpression' => ObjectExpression::class,
        'NumericLiteral' => NumericLiteral::class,
        'ReturnStatement' => ReturnStatement::class,
        'StringLiteral' => StringLiteral::class,
        'TemplateElement' => TemplateElement::class,
        'TemplateLiteral' => TemplateLiteral::class,
        'VariableDeclaration' => VariableDeclaration::class,
        'VariableDeclarator' => VariableDeclarator::class,
    ];

    /**
     * @throws \Exception
     *
     * @return PHPNode[]
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

    /**
     * @param null|BabelNode $babelAst
     *
     * @return null|Doc|PHPExpression
     */
    public static function babelAstToPhp($babelAst)
    {
        if ($babelAst === null || !array_key_exists($babelAst->type, self::$transformers))
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

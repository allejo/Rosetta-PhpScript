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
use allejo\Rosetta\Event\UnsupportedConstructEvent;
use allejo\Rosetta\Exception\UnsupportedConstructException;
use allejo\Rosetta\Transformer\Constructs\ArrayExpression;
use allejo\Rosetta\Transformer\Constructs\ArrowFunctionExpression;
use allejo\Rosetta\Transformer\Constructs\BinaryExpression;
use allejo\Rosetta\Transformer\Constructs\BlockStatement;
use allejo\Rosetta\Transformer\Constructs\BooleanLiteral;
use allejo\Rosetta\Transformer\Constructs\FunctionDeclaration;
use allejo\Rosetta\Transformer\Constructs\Identifier;
use allejo\Rosetta\Transformer\Constructs\NumericLiteral;
use allejo\Rosetta\Transformer\Constructs\ObjectExpression;
use allejo\Rosetta\Transformer\Constructs\PhpConstructInterface;
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
use PhpParser\PrettyPrinter\Standard as PrettyPrinter;
use Psr\EventDispatcher\EventDispatcherInterface;

class Transformer
{
    /** @var array<string, class-string<PhpConstructInterface>> */
    private static array $builtinTransformers = [
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

    private ?EventDispatcherInterface $eventDispatcher;

    /** @var array<string, class-string<PhpConstructInterface>> */
    private array $userTransformers = [];
    private bool $updateTransformersList = true;

    /**
     * @param null|BabelNode $babelAst
     *
     * @return null|Doc|PHPExpression
     */
    public function babelAstToPhp($babelAst)
    {
        if ($babelAst === null || !array_key_exists($babelAst->type, self::$builtinTransformers))
        {
            return null;
        }

        $transformer = $this->getTransformers()[$babelAst->type];

        try
        {
            return $transformer::fromBabel($babelAst, $this);
        }
        catch (UnsupportedConstructException $e)
        {
            $event = new UnsupportedConstructEvent($babelAst);

            /** @var UnsupportedConstructEvent $construct */
            $construct = $this->eventDispatcher->dispatch($event);

            return $construct->getPhpConstruct() ?? new Doc(sprintf('Rosetta-PhpScript :: %s', $e->getMessage()));
        }
    }

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
            $transformed = $this->babelAstToPhp($element);

            if ($transformed === null)
            {
                continue;
            }

            $output[] = $transformed;
        }

        return ArrayUtils::flatten($output);
    }

    /**
     * @param PHPNode[] $phpAst
     */
    public function writeAsPHP(array $phpAst): string
    {
        return (new PrettyPrinter())->prettyPrintFile($phpAst);
    }

    /**
     * @param class-string<PhpConstructInterface> $constructCls
     * @param bool                                $force        force a user defined construct to take precedent over a built-in
     *
     * @return bool true if successfully added transformer, false otherwise
     */
    public function registerTransformer(string $constructCls, bool $force = false): bool
    {
        if ($force === false && isset(self::$builtinTransformers[$constructCls::getConstructName()]))
        {
            return false;
        }

        $this->userTransformers[$constructCls::getConstructName()] = $constructCls;
        $this->updateTransformersList = true;

        return true;
    }

    public function getEventDispatcher(): ?EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }

    public function setEventDispatcher(?EventDispatcherInterface $eventDispatcher): self
    {
        $this->eventDispatcher = $eventDispatcher;

        return $this;
    }

    /**
     * @return array<string, class-string<PhpConstructInterface>>
     */
    private function getTransformers(): array
    {
        static $allTransformers = [];

        if ($this->updateTransformersList)
        {
            $allTransformers = array_merge(self::$builtinTransformers, $this->userTransformers);
            $this->updateTransformersList = false;
        }

        return $allTransformers;
    }
}

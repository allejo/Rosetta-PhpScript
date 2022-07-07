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

    private ?EventDispatcherInterface $eventDispatcher = null;

    /** @var array<string, class-string<PhpConstructInterface>> */
    private array $userTransformers = [];
    private bool $updateTransformersList = true;

    /**
     * Convert a Babel AST node to a PhpParser Node.
     *
     * @param null|BabelNode|\stdClass $babelAst an `stdClass` object representing a Babel AST node that is type hinted
     *                                           to placeholder classes within the `allejo\Rosetta\Babel` namespace
     *
     * @return null|Doc|PHPExpression
     */
    public function fromBabelAstToPhpAst(?\stdClass $babelAst)
    {
        if ($babelAst === null)
        {
            return null;
        }

        $transformers = $this->getTransformers();

        if (!array_key_exists($babelAst->type, $transformers))
        {
            return $this->tryEventDispatcher($babelAst, sprintf('Rosetta-PhpScript :: No support for %s', $babelAst->type));
        }

        $transformer = $transformers[$babelAst->type];

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
     * Convert a JSON string of a Babel AST to a PhpParser AST.
     *
     * @throws \JsonException
     * @throws UnsupportedConstructException
     *
     * @return PHPNode[]
     */
    public function fromJsonStringToPhpAst(string $json): array
    {
        $file = json_decode($json, null, 512, JSON_THROW_ON_ERROR);

        if (!property_exists($file, 'program'))
        {
            throw new UnsupportedConstructException('No `program` definition found in this AST.');
        }

        /** @var Program $program */
        $program = $file->program;
        $output = [];

        foreach ($program->body as $element)
        {
            $transformed = $this->fromBabelAstToPhpAst($element);

            if ($transformed === null)
            {
                continue;
            }

            $output[] = $transformed;
        }

        return ArrayUtils::flatten($output);
    }

    /**
     * Convert a JSON string of a Babel AST to PHP source.
     *
     * @param string $json a JSON string containing the Babel AST
     *
     * @throws \JsonException
     * @throws UnsupportedConstructException
     *
     * @return string PHP source code with the `<?php` tag
     */
    public function fromJsonStringToPhp(string $json): string
    {
        $phpAst = $this->fromJsonStringToPhpAst($json);

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

<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Event;

use PhpParser\Comment\Doc;
use PhpParser\Node\Expr as PHPExpression;

class UnsupportedConstructEvent
{
    private \stdClass $babelAst;

    /** @var null|Doc|PHPExpression */
    private $phpConstruct;

    public function __construct(object $babelAst)
    {
        $this->babelAst = $babelAst;
        $this->phpConstruct = null;
    }

    public function getBabelAst(): \stdClass
    {
        return $this->babelAst;
    }

    /**
     * @return null|Doc|PHPExpression
     */
    public function getPhpConstruct()
    {
        return $this->phpConstruct;
    }

    /**
     * @param null|Doc|PHPExpression $construct
     */
    public function setPhpConstruct($construct): void
    {
        $this->phpConstruct = $construct;
    }
}

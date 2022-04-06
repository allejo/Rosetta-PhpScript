<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Babel;

class ExportNamedDeclaration extends ModuleDeclaration
{
    public $type = 'ExportNamedDeclaration';

    /** @var null|Declaration */
    public $declaration;

    /** @var [ ExportSpecifier | ExportNamespaceSpecifier ] */
    public $specifiers;

    /** @var null|StringLiteral */
    public $source;

    /** @var [ ImportAttribute ] */
    public $assertions;
}

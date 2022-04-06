<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Babel;

class ImportDeclaration extends ModuleDeclaration
{
    public $type = 'ImportDeclaration';

    /** @var null|"type"|"typeof"|"value" */
    public $importKind;

    /** @var [ ImportSpecifier | ImportDefaultSpecifier | ImportNamespaceSpecifier ] */
    public $specifiers;

    /** @var StringLiteral */
    public $source;

    /** @var [ ImportAttribute ] */
    public $assertions;
}

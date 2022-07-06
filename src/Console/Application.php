<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Console;

use allejo\Rosetta\Console\Command\TranslateCommand;
use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication
{
    protected function getDefaultCommands(): array
    {
        $commands = parent::getDefaultCommands();
        $commands[] = new TranslateCommand();

        return $commands;
    }
}

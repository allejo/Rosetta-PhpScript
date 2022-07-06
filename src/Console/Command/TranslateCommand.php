<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\Rosetta\Console\Command;

use allejo\Rosetta\Transformer\Transformer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class TranslateCommand extends Command
{
    private const PROJECT_ROOT = __DIR__ . '/../../../';
    private const CACHE_DIR = '.rosetta';

    public function getName(): string
    {
        return 'translate';
    }

    protected function configure(): void
    {
        $this
            ->addArgument('inputFileOrDir', InputArgument::REQUIRED, '')
            ->addArgument('outputDir', InputArgument::OPTIONAL, '', 'output')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $inputFileOrDir = $input->getArgument('inputFileOrDir');
        $outputDir = $input->getArgument('outputDir');

        if (!file_exists($inputFileOrDir))
        {
            $io->error("File or directory does not exist: {$inputFileOrDir}");

            return 1;
        }

        $files = [];

        if (is_file($inputFileOrDir))
        {
            $files[] = $inputFileOrDir;
        }
        else
        {
            $finder = new Finder();
            $finder
                ->ignoreDotFiles(true)
                ->ignoreVCS(true)
                ->in($inputFileOrDir)
                ->files()
            ;

            foreach ($finder->getIterator() as $file)
            {
                $files[] = $file->getRealPath();
            }
        }

        $jsProcess = new Process(
            ['node', 'src/JavaScript/rosetta.js', "--output=\"{$this->getBabelDirectory()}\""],
            realpath(self::PROJECT_ROOT),
            null,
            implode("\n", $files),
            2 * 60, // 5 minutes (in seconds)
        );

        try
        {
            $jsProcess->mustRun(static function ($type, $buffer) use ($io) {
                if ($type === Process::ERR)
                {
                    $io->error($buffer);
                }
                else
                {
                    $io->write($buffer);
                }
            });
        }
        catch (ProcessFailedException $exception)
        {
            $io->error($exception->getMessage());

            return 2;
        }

        @mkdir($outputDir, 0777, true);

        $babelASTs = new Finder();
        $babelASTs
            ->ignoreDotFiles(true)
            ->ignoreVCS(true)
            ->in($this->getBabelDirectory())
            ->name('*.json')
            ->files()
        ;
        $transformer = new Transformer();

        foreach ($babelASTs as $babelAST)
        {
            $astRaw = file_get_contents($babelAST->getRealPath());
            $filename = $babelAST->getBasename('.json') . '.php';

            try
            {
                $phpSrc = $transformer->fromJsonStringToPhp($astRaw);
            }
            catch (\Exception $e)
            {
                $io->error("Error writing file ({$filename}): {$e->getMessage()}");

                continue;
            }

            file_put_contents($outputDir . '/' . $filename, $phpSrc);
        }

        return 0;
    }

    private function getBabelDirectory(): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            realpath(self::PROJECT_ROOT),
            self::CACHE_DIR,
            'babel',
        ]);
    }
}

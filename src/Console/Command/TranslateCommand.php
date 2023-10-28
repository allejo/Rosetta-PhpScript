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
use Symfony\Component\Console\Input\InputOption;
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
            ->addArgument('inputFileOrDir', InputArgument::REQUIRED, 'A JavaScript file or a folder of JS files to translate to PHP')
            ->addArgument('outputDir', InputArgument::OPTIONAL, 'The output directory where converted PHP files should be written to', 'output')
            ->addOption('config', 'c', InputOption::VALUE_OPTIONAL, 'A configuration file that can return a custom Transformer', '.rosetta.dist.php')
            ->addOption('exclude', 'e', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'File patterns to exclude', [])
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $inputFileOrDir = $input->getArgument('inputFileOrDir');
        $outputDir = $input->getArgument('outputDir');

        if (!file_exists($inputFileOrDir)) {
            $io->error("File or directory does not exist: {$inputFileOrDir}");

            return Command::FAILURE;
        }

        $files = [];

        if (is_file($inputFileOrDir)) {
            $files[] = $inputFileOrDir;
        } else {
            $exclusions = $input->getOption('exclude');

            $finder = new Finder();
            $finder
                ->ignoreDotFiles(true)
                ->ignoreVCS(true)
                ->notName($exclusions)
                ->in($inputFileOrDir)
                ->files()
            ;

            foreach ($finder->getIterator() as $file) {
                $files[] = $file->getRealPath();
            }
        }

        if (\Phar::running() !== '') {
            $cwd = \Phar::running();
        } else {
            $cwd = realpath(self::PROJECT_ROOT);
        }

        $jsProcess = new Process(
            ['node', 'src/JavaScript/dist/rosetta.js', "--output=\"{$this->getBabelDirectory()}\""],
            $cwd,
            null,
            implode("\n", $files),
            2 * 60, // 2 minutes (in seconds)
        );

        try {
            $jsProcess->mustRun(static function ($type, $buffer) use ($io) {
                if ($type === Process::ERR) {
                    $io->error($buffer);
                } else {
                    $io->write($buffer);
                }
            });
        } catch (ProcessFailedException $exception) {
            $io->error($exception->getMessage());

            return Command::FAILURE;
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

        $transformer = $this->getTransformer($input->getOption('config'));

        foreach ($babelASTs as $babelAST) {
            $astRaw = file_get_contents($babelAST->getRealPath());
            $filename = $babelAST->getBasename('.json') . '.php';

            try {
                $phpSrc = $transformer->fromJsonStringToPhp($astRaw);
            } catch (\Exception $e) {
                $io->error("Error writing file ({$filename}): {$e->getMessage()}\n");

                continue;
            }

            $io->write("Successful conversion for: {$filename}\n");
            file_put_contents($outputDir . '/' . $filename, $phpSrc);
        }

        return Command::SUCCESS;
    }

    private function getBabelDirectory(): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            getcwd(),
            self::CACHE_DIR,
            'babel',
        ]);
    }

    private function getTransformer(?string $configPath): Transformer
    {
        if ($configPath && file_exists($configPath)) {
            $returnValue = (include $configPath);

            if (!$returnValue instanceof Transformer) {
                throw new \InvalidArgumentException("The return value from the given configuration file ({$configPath}) is not a Transformer object.");
            }

            return $returnValue;
        }

        return new Transformer();
    }
}

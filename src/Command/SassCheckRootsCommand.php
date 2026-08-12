<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;
use Symfonycasts\SassBundle\SassFileHelper;

/**
 * Garde-fou : échoue si un fichier .scss de assets/styles n'est pas déclaré
 * dans symfonycasts_sass.root_sass (config/packages/sass.yaml).
 *
 * Un fichier SCSS absent de root_sass serait servi brut par AssetMapper
 * (SCSS invalide en CSS) : le style ne s'appliquerait pas silencieusement.
 */
#[AsCommand(
    name: 'sass:check-roots',
    description: "Échoue si un fichier .scss de assets/styles n'est pas déclaré dans root_sass."
)]
class SassCheckRootsCommand extends Command
{
    private const ASSETS_STYLES_DIR = 'assets/styles';
    private const SASS_CONFIG_FILE = 'config/packages/sass.yaml';
    private const DEFAULT_ROOT_SASS = '%kernel.project_dir%/assets/styles/app.scss';

    public function __construct(
        private readonly string $projectDir,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $missing = $this->findUndeclaredScssFiles();
        if ($missing !== []) {
            $io->error(
                sprintf(
                    "Fichier(s) SCSS non déclaré(s) dans 'symfonycasts_sass.root_sass' " .
                    "(%s) :\n- %s\n\n" .
                    "Ces fichiers seraient servis bruts (CSS cassé). Ajoutez-les à root_sass " .
                    "puis relancez 'make assets-install'.",
                    self::SASS_CONFIG_FILE,
                    implode("\n- ", $missing)
                )
            );
            return Command::FAILURE;
        }

        $io->success('Tous les fichiers .scss de assets/styles sont déclarés dans root_sass.');
        return Command::SUCCESS;
    }

    /**
     * @return array<int, string> chemins relatifs (projet) des fichiers SCSS
     *                             non déclarés dans root_sass
     */
    private function findUndeclaredScssFiles(): array
    {
        $stylesDir = Path::join($this->projectDir, self::ASSETS_STYLES_DIR);
        if (!is_dir($stylesDir)) {
            return [];
        }

        $configuredFiles = [];
        $helper = new SassFileHelper();
        foreach ($this->getRootSassPaths() as $path) {
            foreach ($helper->resolveSassInput($path, $this->projectDir) as $resolvedFile) {
                $configuredFiles[$this->normalizeRealPath($resolvedFile)] = true;
            }
        }

        $missing = [];
        foreach ($this->findStylesScssFiles($stylesDir) as $file) {
            $realPath = $this->normalizeRealPath($file->getPathname());
            if (!isset($configuredFiles[$realPath])) {
                $missing[] = Path::makeRelative($file->getPathname(), $this->projectDir);
            }
        }

        return $missing;
    }

    /**
     * @return array<int, string> chemins root_sass tels que configurés
     */
    private function getRootSassPaths(): array
    {
        $configFile = Path::join($this->projectDir, self::SASS_CONFIG_FILE);
        if (!is_file($configFile)) {
            return [str_replace('%kernel.project_dir%', $this->projectDir, self::DEFAULT_ROOT_SASS)];
        }

        $config = Yaml::parseFile($configFile);
        $paths = is_array($config) ? ($config['symfonycasts_sass']['root_sass'] ?? null) : null;
        if (!is_array($paths)) {
            throw new \RuntimeException(
                sprintf(
                    'La clé "symfonycasts_sass.root_sass" est introuvable ou invalide dans %s.',
                    $configFile
                )
            );
        }

        $paths = array_values(array_filter($paths, 'is_string'));
        $projectDir = $this->projectDir;

        return array_map(
            static fn (string $path): string => str_replace('%kernel.project_dir%', $projectDir, $path),
            $paths
        );
    }

    /**
     * @return \Iterator<string, \SplFileInfo>
     */
    private function findStylesScssFiles(string $stylesDir): \Iterator
    {
        $finder = new Finder();
        $finder
            ->files()
            ->in($stylesDir)
            ->name('*.scss')
            ->notName('_*')
            ->sortByName();

        return $finder->getIterator();
    }

    private function normalizeRealPath(string $path): string
    {
        return (string) realpath($path);
    }
}

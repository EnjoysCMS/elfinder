<?php

declare(strict_types=1);


namespace EnjoysCMS\Module\ElFinder;


use Enjoys\AssetsCollector\Helpers;
use EnjoysCMS\Core\Components\Helpers\Assets;
use Psr\Container\ContainerInterface;

final class Load
{

    private string $app_path;

    const DIST_FILES = [
        'main.js' => 'main.default.js',
        'connector.minimal.php' => 'connector.minimal.php-dist',
        'main.popup.js' => 'main.popup.default.js',
    ];

    /**
     * @throws \Exception
     */
    public function __construct(ContainerInterface $container)
    {
        $this->checkEnvironment();
        $this->fetchAppPath($container);
        $this->createDirectories();
        $this->copyDistFiles();
        $this->makeSymlink();
    }

    private function copyDistFiles()
    {
        foreach (self::DIST_FILES as $file => $distFile) {
              if (!file_exists($this->app_path . $file)) {
                copy(__DIR__ . '/../dist/' . $distFile, $this->app_path . '/' . $file);
            }
        }
    }

    private function makeSymlink(): void
    {
        Assets::createSymlink($this->app_path . '/js', $_ENV['ELFINDER_VENDOR_DIR'] . '/js');
        Assets::createSymlink($this->app_path . '/css', $_ENV['ELFINDER_VENDOR_DIR'] . '/css');
        Assets::createSymlink($this->app_path . '/img', $_ENV['ELFINDER_VENDOR_DIR'] . '/img');
    }


    /**
     * @throws \Exception
     */
    private function checkEnvironment(): void
    {
        if (!isset($_ENV['ELFINDER_VENDOR_DIR'])) {
            throw new \Exception('Please set to .env ELFINDER_VENDOR_DIR');
        }
        if (!isset($_ENV['ELFINDER_FILES_DIR'])) {
            throw new \Exception('Please set to .env ELFINDER_FILES_DIR');
        }
        if (!isset($_ENV['ELFINDER_FILES_URL'])) {
            throw new \Exception('Please set to .env ELFINDER_FILES_URL');
        }
        if (!isset($_ENV['ELFINDER_TRASH_DIR'])) {
            throw new \Exception('Please set to .env ELFINDER_TRASH_DIR');
        }
        if (!isset($_ENV['ELFINDER_TRASH_URL'])) {
            throw new \Exception('Please set to .env ELFINDER_TRASH_URL');
        }
    }

    private function fetchAppPath($container)
    {
        $this->app_path = $_ENV['PUBLIC_DIR'] . pathinfo(
                (string)$container->get('Router')?->getRouteCollection()?->get('elfinder')?->getPath(),
                PATHINFO_DIRNAME
            );

        if (!is_string($this->app_path)) {
            throw new \InvalidArgumentException(
                sprintf('$app_path must be string %s given', get_debug_type($this->app_path))
            );
        }
    }

    /**
     * @throws \Exception
     */
    private function createDirectories(): void
    {
        Helpers::createDirectory($this->app_path);
        Helpers::createDirectory($_ENV['ELFINDER_FILES_DIR']);
        Helpers::createDirectory($_ENV['ELFINDER_TRASH_DIR']);
    }
}
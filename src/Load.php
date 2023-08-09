<?php

declare(strict_types=1);

namespace EnjoysCMS\Module\ElFinder;

use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use Symfony\Component\Routing\RouteCollection;

use function Enjoys\FileSystem\createDirectory;
use function Enjoys\FileSystem\makeSymlink;

final class Load
{

    private string $app_path;

    private const DIST_FILES = [
        'main.js' => 'main.default.js',
        'connector.minimal.php' => 'connector.minimal.php-dist',
        'main.popup.js' => 'main.popup.default.js',
    ];

    /**
     * @throws Exception
     */
    public function __construct(Container $container)
    {
        $this->checkEnvironment();
        $this->fetchAppPath($container);
        $this->createDirectories();
        $this->copyDistFiles();
        $this->makeSymlink();
    }

    private function copyDistFiles(): void
    {
        foreach (self::DIST_FILES as $file => $distFile) {
            if (!file_exists($this->app_path . '/' . $file)) {
                copy(__DIR__ . '/../dist/' . $distFile, $this->app_path . '/' . $file);
            }
        }
    }

    /**
     * @throws Exception
     */
    private function makeSymlink(): void
    {
        makeSymlink($this->app_path . '/js', $_ENV['ELFINDER_VENDOR_DIR'] . '/js');
        makeSymlink($this->app_path . '/css', $_ENV['ELFINDER_VENDOR_DIR'] . '/css');
        makeSymlink($this->app_path . '/img', $_ENV['ELFINDER_VENDOR_DIR'] . '/img');
    }


    /**
     * @throws Exception
     */
    private function checkEnvironment(): void
    {
        if (!isset($_ENV['ELFINDER_VENDOR_DIR'])) {
            throw new Exception('Please set to .env ELFINDER_VENDOR_DIR');
        }
        if (!isset($_ENV['ELFINDER_FILES_DIR'])) {
            throw new Exception('Please set to .env ELFINDER_FILES_DIR');
        }
        if (!isset($_ENV['ELFINDER_FILES_URL'])) {
            throw new Exception('Please set to .env ELFINDER_FILES_URL');
        }
        if (!isset($_ENV['ELFINDER_TRASH_DIR'])) {
            throw new Exception('Please set to .env ELFINDER_TRASH_DIR');
        }
        if (!isset($_ENV['ELFINDER_TRASH_URL'])) {
            throw new Exception('Please set to .env ELFINDER_TRASH_URL');
        }
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    private function fetchAppPath(Container $container): void
    {
        $this->app_path = $_ENV['PUBLIC_DIR'] . pathinfo(
                (string)$container->get(RouteCollection::class)->get('elfinder')?->getPath(),
                PATHINFO_DIRNAME
            );
    }

    /**
     * @throws Exception
     */
    private function createDirectories(): void
    {
        createDirectory($this->app_path);
        createDirectory($_ENV['ELFINDER_FILES_DIR']);
        createDirectory($_ENV['ELFINDER_TRASH_DIR']);
    }
}

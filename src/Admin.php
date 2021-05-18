<?php

declare(strict_types=1);


namespace EnjoysCMS\Module\ElFinder;


use App\Module\Admin\Core\ModelInterface;
use Enjoys\AssetsCollector\Helpers;
use EnjoysCMS\Core\Components\Helpers\Assets;
use Psr\Container\ContainerInterface;

final class Admin implements ModelInterface
{

    private string $app_path;

    public function __construct(ContainerInterface $container)
    {
        $this->app_path = $_ENV['PUBLIC_DIR'] . pathinfo(
                (string)$container->get('Router')?->getRouteCollection()?->get('admin/elfinder')?->getPath(),
                PATHINFO_DIRNAME
            );

        if (!is_string($this->app_path)) {
            throw new \InvalidArgumentException(
                sprintf('$app_path must be string %s given', get_debug_type($this->app_path))
            );
        }
    }

    private function checkScripts()
    {
        $js_file = $this->app_path . '/main.js';
        if (!file_exists($js_file)) {
            copy($_ENV['ELFINDER_VENDOR_DIR'] . '/main.default.js', $js_file);
        }

        $config_file = $this->app_path . '/connector.minimal.php';
        if (!file_exists($config_file)) {
            copy(__DIR__ . '/../connector.minimal.php.dist', $config_file);
        }
    }

    private function makeSymlink()
    {
        Assets::createSymlink($this->app_path . '/js', $_ENV['ELFINDER_VENDOR_DIR'] . '/js');
        Assets::createSymlink($this->app_path . '/css', $_ENV['ELFINDER_VENDOR_DIR'] . '/css');
        Assets::createSymlink($this->app_path . '/img', $_ENV['ELFINDER_VENDOR_DIR'] . '/img');
    }

    /**
     * @throws \Exception
     */
    public function getContext(): array
    {
        Helpers::createDirectory($this->app_path);
        Helpers::createDirectory($_ENV['ELFINDER_FILES_DIR']);
        Helpers::createDirectory($_ENV['ELFINDER_TRASH_DIR']);

        $this->checkScripts();
        $this->makeSymlink();


        return [];
    }
}
<?php

declare(strict_types=1);


namespace EnjoysCMS\Module\ElFinder;


use App\Module\Admin\Core\ModelInterface;
use Enjoys\AssetsCollector\Helpers;
use EnjoysCMS\Core\Components\Helpers\Assets;

final class Admin implements ModelInterface
{

    private function checkScripts()
    {
        $js_file = $_ENV['PUBLIC_DIR'].'/elfinder/main.js';
        if(!file_exists($js_file)){
            Helpers::createDirectory($_ENV['PUBLIC_DIR'].'/elfinder');
            copy($_ENV['ELFINDER_DIR'].'/main.default.js', $js_file);
        }

        $config_file = $_ENV['PUBLIC_DIR'].'/elfinder/connector.minimal.php';
        if(!file_exists($config_file)){
            Helpers::createDirectory($_ENV['PUBLIC_DIR'].'/elfinder');
            copy($_ENV['ELFINDER_DIR'].'/php/connector.minimal.php-dist', $config_file);
        }
    }

    private function makeSymlink()
    {
        Assets::createSymlink($_ENV['PUBLIC_DIR'] . '/admin/js', $_ENV['ELFINDER_DIR'] . '/js');
        Assets::createSymlink($_ENV['PUBLIC_DIR'] . '/admin/css', $_ENV['ELFINDER_DIR'] . '/css');
        Assets::createSymlink($_ENV['PUBLIC_DIR'] . '/admin/img', $_ENV['ELFINDER_DIR'] . '/img');
    }

    public function getContext(): array
    {
        $this->checkScripts();
        $this->makeSymlink();

        Assets::js(
            [
                'https://cdnjs.cloudflare.com/ajax/libs/require.js/2.3.6/require.min.js',
                $_ENV['PUBLIC_DIR'] . '/elfinder/main.js'
            ]
        );

        return [];
    }
}
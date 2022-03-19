<?php

declare(strict_types=1);

namespace EnjoysCMS\Module\ElFinder;

use App\Module\Admin\BaseController;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Routing\Annotation\Route;

final class Controller extends BaseController
{

    public const ELFINDER_PUBLIC_DIR = 'elfinder';

    /**
     * @throws \Exception
     */
    public function __construct(ContainerInterface $container)
    {
        new Load($container);
        parent::__construct($container);
    }

    private function getVersion()
    {
        $info = json_decode(file_get_contents($_ENV['ELFINDER_VENDOR_DIR'] . '/package.json'));
        return $info->version;
    }

    #[Route(
        path: self::ELFINDER_PUBLIC_DIR . '/elfinder.html',
        name: "elfinder",
        options: [
            "aclComment" => "[admin] elFinder"
        ]
    )]
    public function elFinder(): ResponseInterface
    {
        return $this->responseText($this->view(
            __DIR__ . '/template/elfinder.twig',
            [
                'version' => $this->getVersion()
            ]
        ));
    }

    #[Route(
        path: self::ELFINDER_PUBLIC_DIR . '/popup.html',
        name: "elfinder/popup",
        options: [
            "aclComment" => "[admin] elFinder Popup"
        ]
    )]
    public function elFinderInputText(): ResponseInterface
    {
        return $this->responseText($this->view(
            __DIR__ . '/template/popup.twig',
            [
                'version' => $this->getVersion()
            ]
        ));
    }

    #[Route(
        path: self::ELFINDER_PUBLIC_DIR . '/connector.minimal.php',
        name: "elfinder/connector",
        options: [
            "aclComment" => "[connector file] elFinder"
        ]
    )]
    public function connector(): ResponseInterface
    {
        return $this->responseText();
    }
}

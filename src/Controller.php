<?php

declare(strict_types=1);

namespace EnjoysCMS\Module\ElFinder;

use DI\Container;
use EnjoysCMS\Module\Admin\AdminBaseController;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class Controller extends AdminBaseController
{

    public const ELFINDER_PUBLIC_DIR = 'elfinder';

    /**
     * @throws \Exception
     */
    public function __construct(Container $container)
    {
        new Load($container);
        parent::__construct($container);
    }

    private function getVersion()
    {
        $info = json_decode(file_get_contents($_ENV['ELFINDER_VENDOR_DIR'] . '/package.json'));
        return $info?->version ?? '-';
    }

    #[Route(
        path: self::ELFINDER_PUBLIC_DIR . '/elfinder.html',
        name: "@elfinder",
        options: [
            "aclComment" => "[admin] elFinder"
        ]
    )]
    public function elFinder(UrlGeneratorInterface $urlGenerator): ResponseInterface
    {
        $this->breadcrumbs->setLastBreadcrumb(
            sprintf('elFinder v%s', $this->getVersion())
        );
        return $this->response(
            $this->twig->render(
                __DIR__ . '/template/elfinder.twig',
                [
                    'version' => $this->getVersion(),
                ]
            )
        );
    }

    #[Route(
        path: self::ELFINDER_PUBLIC_DIR . '/popup.html',
        name: "@elfinder_popup",
        options: [
            "aclComment" => "[admin] elFinder Popup"
        ]
    )]
    public function elFinderInputText(): ResponseInterface
    {
        return $this->response(
            $this->twig->render(
                __DIR__ . '/template/popup.twig',
                [
                    'version' => $this->getVersion()
                ]
            )
        );
    }

    #[Route(
        path: self::ELFINDER_PUBLIC_DIR . '/connector.minimal.php',
        name: "@elfinder_connector",
        options: [
            "aclComment" => "[connector file] elFinder"
        ]
    )]
    public function connector(): ResponseInterface
    {
        return $this->response();
    }
}

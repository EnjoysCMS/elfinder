<?php

declare(strict_types=1);

namespace EnjoysCMS\Module\ElFinder;

use DI\Container;
use EnjoysCMS\Core\Routing\Annotation\Route;
use EnjoysCMS\Module\Admin\AdminBaseController;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[Route(self::ELFINDER_PUBLIC_DIR, '@elfinder')]
final class Controller extends AdminBaseController
{

    public const ELFINDER_PUBLIC_DIR = 'elfinder';

    /**
     * @throws Exception
     */
    public function __construct(Container $container)
    {
        new Load($container);
        parent::__construct($container);
    }

    private function getVersion(): string
    {
        $info = json_decode(file_get_contents($_ENV['ELFINDER_VENDOR_DIR'] . '/package.json'));
        return $info?->version ?? '-';
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    #[Route(
        path: '/elfinder.html',
        name: '',
        comment: '[admin] elFinder'
    )]
    public function elFinder(): ResponseInterface
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

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    #[Route(
        path: '/popup.html',
        name: '_popup',
        comment: '[admin] elFinder Popup'
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
        path: '/connector.minimal.php',
        name: '_connector',
        comment: '[connector file] elFinder'
    )]
    public function connector(): void
    {
    }
}

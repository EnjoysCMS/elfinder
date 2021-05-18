<?php

declare(strict_types=1);


namespace EnjoysCMS\Module\ElFinder;


use App\Module\Admin\BaseController;
use Doctrine\ORM\EntityManager;
use Enjoys\Forms\Renderer\RendererInterface;
use Enjoys\Http\ServerRequestInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

final class Controller extends BaseController
{

    private string $templatePath = __DIR__;

    /**
     * @throws \Exception
     */
    public function __construct(
        Environment $twig,
        ServerRequestInterface $serverRequest,
        EntityManager $entityManager,
        UrlGeneratorInterface $urlGenerator,
        RendererInterface $renderer
    ) {
        if (!isset($_ENV['ELFINDER_VENDOR_DIR'])
            || !isset($_ENV['ELFINDER_FILES_DIR'])
            || !isset($_ENV['ELFINDER_FILES_URL'])
            || !isset($_ENV['ELFINDER_TRASH_DIR'])
            || !isset($_ENV['ELFINDER_TRASH_URL'])
        ) {
            throw new \Exception('Please read README.md and set Environment variables');
        }

        parent::__construct($twig, $serverRequest, $entityManager, $urlGenerator, $renderer);
    }

    /**
     * @return string
     */
    public function getTemplatePath(): string
    {
        return realpath($this->templatePath);
    }

//    /**
//     * @Route(
//     *     name="admin/elfinder",
//     *     path="elfinder/elfinder.html",
//     *     options={
//     *          "aclComment": "[admin] elFinder"
//     *     }
//     * )
//     */
    #[Route(
        path: 'elfinder/elfinder.html',
        name: "admin/elfinder",
        options: [
            "aclComment" => "[admin] elFinder"
        ]

    )]
    public function elFinder(
        ContainerInterface $container
    ): string {
        return $this->view(
            $this->getTemplatePath() . '/elfinder.twig',
            $this->getContext($container->get(Admin::class))
        );
    }
}
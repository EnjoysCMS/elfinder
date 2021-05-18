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

    /**
     * @throws \Exception
     */
    public function __construct(ContainerInterface $container)
    {
        new Load($container);
        parent::__construct(
            $container->get(Environment::class),
            $container->get(ServerRequestInterface::class),
            $container->get(EntityManager::class),
            $container->get(UrlGeneratorInterface::class),
            $container->get(RendererInterface::class)
        );
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
    public function elFinder(): string
    {
        return $this->view(__DIR__ . '/elfinder.twig', []);
    }
}
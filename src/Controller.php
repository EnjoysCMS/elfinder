<?php

declare(strict_types=1);


namespace EnjoysCMS\Module\ElFinder;


use App\Module\Admin\BaseController;
use Psr\Container\ContainerInterface;
use Symfony\Component\Routing\Annotation\Route;

final class Controller extends BaseController
{

    private string $templatePath = __DIR__;

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
    public function elFinder(ContainerInterface $container): string
    {
        return $this->view(
            $this->getTemplatePath() . '/elfinder.twig',
            $this->getContext($container->get(Admin::class))
        );
    }
}
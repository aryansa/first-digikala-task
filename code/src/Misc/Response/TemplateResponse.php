<?php

namespace App\Misc\Response;


use App\Misc\Containers\GuestAccount;
use App\Services;
use Symfony\Component\HttpFoundation\Response;

class TemplateResponse extends Response
{

    /**
     * AdminResponse constructor.
     */
    private function __construct(string $template, array $variables)
    {

        $content = Services::twigService()->render(
            $template,
            $variables + [
                'menu' => $this->getMenu()
            ]
        );

        parent::__construct($content);
    }

    public static function make(string $template, array $variables): TemplateResponse
    {
        return new TemplateResponse($template, $variables);
    }

    private function getMenu()
    {
        if (Services::accountService()->getAccount() instanceof GuestAccount) {
            return [
                ['url' => '/', 'title' => 'Products'],
                ['url' => '/login/', 'title' => 'Login'],
            ];
        }

        return [
            ['url' => '/', 'title' => 'Products'],
            ['url' => '/admin/', 'title' => 'Admin'],
            ['url' => '/admin/managevariants/', 'title' => 'ManageVariants'],
            ['url' => '/admin/manageproduct/', 'title' => 'ManageProducts'],
            ['url' => '/admin/managecolors/', 'title' => 'ManageColors']
        ];
    }
}
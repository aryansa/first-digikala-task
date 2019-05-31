<?php
/**
 * Created by PhpStorm.
 * User: aryanpc
 * Date: 5/21/19
 * Time: 3:29 PM
 */

namespace App\Services;

use Twig\Loader\FilesystemLoader;

class TwigService
{

    private $twig;

    /**
     * Twig constructor.
     */
    public function __construct()
    {

        $loader = new FilesystemLoader('../src/Templates');

        $this->twig = new \Twig\Environment($loader, [
            'cache' => '../cache',
            'auto_reload' => true
        ]);
    }

    public function render(string $template, array $var)
    {

        $template = $this->twig->load($template);

        return $template->render($var);
    }
}
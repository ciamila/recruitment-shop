<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class IndexController
{
    /** @var Environment */
    private $twig;

    /**
     * Constructor
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/", name="homepage", methods={"GET"})
     */
    public function __invoke(): Response
    {
        return new Response($this->twig->render('index.html.twig'));
    }
}

<?php

declare(strict_types=1);

namespace App\Controller;

use App\Config\ListingConfig;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ProductListingController
{
    /** @var Environment */
    private $twig;

    /** @var ProductRepository */
    private $productRepository;

    /**
     * Constructor
     */
    public function __construct(Environment $twig, ProductRepository $productRepository)
    {
        $this->twig = $twig;
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/product/listing/{page?1}", name="product_listing", methods={"GET"}, requirements={"page"="\d+"})
     */
    public function __invoke(int $page): Response
    {
        $products = $this->productRepository->findBy(
            [],
            ['id' => 'DESC'],
            ListingConfig::ITEMS_PER_PAGE,
            ($page - 1) * ListingConfig::ITEMS_PER_PAGE
        );
        $productsPages = $this->productRepository->getNumberOfPages(ListingConfig::ITEMS_PER_PAGE);
        $content = $this->twig->render(
            'product/listing.html.twig', 
            ['products' => $products, 'productsPages' => $productsPages]
        );

        return new Response($content);
    }
}

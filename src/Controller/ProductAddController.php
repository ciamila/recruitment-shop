<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\ShopException;
use App\Form\ProductType;
use App\Form\Model\ProductFormModel;
use App\Service\ProductAddService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class ProductAddController
{
    /** @var Environment */
    private $twig;

    /** @var FormFactoryInterface */
    private $form;

    /** @var RouterInterface */
    private $router;

    /** @var SessionInterface */
    private $session;

    /** @var ProductAddService */
    private $productAddService;

    /**
     * @param Environment $twig
     * @param FormFactoryInterface $form
     * @param RouterInterface $router
     * @param SessionInterface $session
     * @param ProductAddService $productAddService
     */
    public function __construct(
        Environment $twig,
        FormFactoryInterface $form,
        RouterInterface $router,
        SessionInterface $session,
        ProductAddService $productAddService
    ) {
        $this->twig = $twig;
        $this->form = $form;
        $this->router = $router;
        $this->session = $session;
        $this->productAddService = $productAddService;
    }

    /**
     * @Route("/product/add", name="product_add", methods={"GET", "POST"})
     */
    public function __invoke(Request $request): Response
    {
        $productFormModel = new ProductFormModel();

        $form = $this->form->create(ProductType::class, $productFormModel);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $productFormModel = $form->getData();
            try {
                $this->productAddService->handleAddingProduct($productFormModel);
            } catch (ShopException $exception) {
                $this->session->getFlashBag()->add('error', $exception->getMessage());
            }

            return new RedirectResponse($this->router->generate('product_listing'), Response::HTTP_FOUND);
        }

        return new Response($this->twig->render('product/add.html.twig', [
            'form' => $form->createView(),
        ]));
    }
}

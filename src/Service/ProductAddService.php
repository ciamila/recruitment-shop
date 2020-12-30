<?php

declare(strict_types=1);

namespace App\Service;

use App\Config\EmailConfig;
use App\Entity\Product;
use App\Form\Model\ProductFormModel;
use App\Repository\ProductRepository;

class ProductAddService
{
    /** @var ProductRepository */
    private $productRepository;

    /** @var EmailSenderService */
    private $emailSenderService;

    /** @var SlackApiService */
    private $slackApiService;

    /**
     * @param ProductRepository $productRepository
     * @param EmailSenderService $emailSenderService
     * @param SlackApiService $slackApiService
     */
    public function __construct(
        ProductRepository $productRepository, 
        EmailSenderService $emailSenderService,
        SlackApiService $slackApiService
        )
    {
        $this->productRepository = $productRepository;
        $this->emailSenderService = $emailSenderService;
        $this->slackApiService = $slackApiService;
    }

    /**
     * @var ProductFormModel $productFormModel
     * 
     * @return Product
     */
    public function handleAddingProduct(ProductFormModel $productFormModel): Product
    {
        $product = Product::createFromFormModel($productFormModel);
        $this->productRepository->save($product);

        $message = $this->prepareMessage($product);
        $this->emailSenderService->sendEmail(
            EmailConfig::EMAIL_FROM,
            EmailConfig::EMAIL_TO, 
            EmailConfig::EMAIL_SUBJECT, 
            $message
        );

        $this->slackApiService->send($message);

        return $product;
    }

    /**
     * @param Product $product
     * 
     * @return string
     */
    private function prepareMessage(Product $product): string
    {
        return sprintf(EmailConfig::MESSAGE_TEMPLATE, $product->getName(), $product->getDescription());
    }
}
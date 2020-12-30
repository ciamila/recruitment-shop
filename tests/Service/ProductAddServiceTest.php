<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Config\EmailConfig;
use App\Entity\Product;
use App\Form\Model\ProductFormModel;
use App\Repository\ProductRepository;
use App\Service\EmailSenderService;
use App\Service\ProductAddService;
use App\Service\SlackApiService;
use PHPUnit\Framework\MockObject;
use PHPUnit\Framework\TestCase;

class ProductAddServiceTest extends TestCase
{
    private const PRODUCT_NAME = 'test';
    private const PRODUCT_DESCRIPTION = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Egestas purus viverra accumsan in nisl nisi scelerisque eu ultrices. Lobortis scelerisque fermentum dui faucibus. Suspendisse in est ante in nibh mauris. Vestibulum rhoncus est pellentesque elit. Dapibus ultrices in iaculis nunc. Velit laoreet id donec ultrices tincidunt arcu. Purus gravida quis blandit turpis cursus in hac. Diam phasellus vestibulum lorem sed. Risus commodo viverra maecenas accumsan lacus vel facilisis volutpat est. Libero id faucibus nisl tincidunt eget nullam non nisi. Condimentum vitae sapien pellentesque habitant morbi tristique senectus. Bibendum ut tristique et egestas quis ipsum suspendisse. Egestas sed tempus urna et.';
    private const PRODUCT_PRICE = 3.4;
    private const PRODUCT_CURRENCY = 'PLN';

    /** @var ProductRepository|MockObject */
    private $productRepositoryMock;

    /** @var EmailSenderService|MockObject */
    private $emailSenderServiceMock;

    /** @var SlackApiService|MockObject */
    private $slackApiServiceMock;

    /** @var ProductAddService */
    private $sut;

    public function setUp(): void
    {
        $this->productRepositoryMock = $this->createMock(ProductRepository::class);
        $this->emailSenderServiceMock = $this->createMock(EmailSenderService::class);
        $this->slackApiServiceMock = $this->createMock(SlackApiService::class);
        $this->sut = new ProductAddService(
            $this->productRepositoryMock, 
            $this->emailSenderServiceMock,
            $this->slackApiServiceMock
        );
    }

    /**
     * @test
     */
    public function handleAddingProduct(): void
    {
        $productFormModel = new ProductFormModel();
        $productFormModel
            ->setName(self::PRODUCT_NAME)
            ->setDescription(self::PRODUCT_DESCRIPTION)
            ->setPrice(self::PRODUCT_PRICE)
            ->setCurrency(self::PRODUCT_CURRENCY);

        $expectedProduct = new Product(
            self::PRODUCT_NAME,
            self::PRODUCT_DESCRIPTION,
            self::PRODUCT_PRICE,
            self::PRODUCT_CURRENCY
        );
        $expectedMessage = sprintf(
            'Product %s (%s) was added in our shop', 
            self::PRODUCT_NAME, 
            self::PRODUCT_DESCRIPTION
        );

        $this->productRepositoryMock->expects($this->once())
            ->method('save')    
            ->with($expectedProduct)
            ->willReturn($expectedProduct);

        $this->emailSenderServiceMock->expects($this->once())
            ->method('sendEmail')    
            ->with(EmailConfig::EMAIL_FROM, EmailConfig::EMAIL_TO, EmailConfig::EMAIL_SUBJECT, $expectedMessage);

        $this->slackApiServiceMock->expects($this->once())
            ->method('send')    
            ->with($expectedMessage);

        $result = $this->sut->handleAddingProduct($productFormModel);
        $this->assertEquals($expectedProduct, $result);
    }
}

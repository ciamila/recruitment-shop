<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProductAddControllerTest extends WebTestCase
{
    /**
     * @test
     */
    public function addFormView()
    {
        $client = static::createClient();

        $client->request('GET', '/product/add');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertContains(
            'Add product',
            $client->getResponse()->getContent()
        );
    }

    /**
     * @test
     */
    public function addFormValidData()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/product/add');
        $addForm = $crawler->selectButton('product_add_form_add')->form();
        $formValues = [
            ProductType::BLOCK_PREFIX => [
                '_token' => $addForm[ProductType::BLOCK_PREFIX.'[_token]']->getValue(),
                'name' => 'testName',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Amet porttitor eget dolor morbi.',
                'price' => '2.7',
                'currency' => 'PLN',
            ]
        ];
    
        $crawler = $client->request($addForm->getMethod(), $addForm->getUri(), $formValues);
        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertContains(
            'Listing of products',
            $client->getResponse()->getContent()
        );
    }
}

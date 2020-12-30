<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProductListingControllerTest extends WebTestCase
{
    /**
     * @test
     */
    public function listingAction()
    {
        $client = static::createClient();

        $client->request('GET', '/product/listing');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertContains(
            'Listing of products',
            $client->getResponse()->getContent()
        );
    }
}
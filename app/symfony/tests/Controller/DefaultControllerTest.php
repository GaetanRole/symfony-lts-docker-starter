<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;

/**
 * Inherited for setup method()
 *
 * @group functional
 */
final class DefaultControllerTest extends AbstractWebTestCase
{
    /**
     * DefaultController:::index() must returns 200
     */
    public function testIndexMethodReturnsA200StatusCode(): void
    {
        $this->webClient->request('GET', '/');

        $this->assertSame(200, $this->webClient->getResponse()->getStatusCode());
    }
}

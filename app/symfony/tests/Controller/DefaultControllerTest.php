<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;

/**
 * Inherited for setup method()
 *
 * @group   functional
 *
 * @author  Gaëtan Rolé-Dubruille <gaetan.role@gmail.com>
 */
final class DefaultControllerTest extends AbstractWebTestCase
{
    /**
     * DefaultController:::index() must returns 200
     */
    public function testIndexMethodIsSuccessful(): void
    {
        self::$client->request('GET', '/en/');
        self::assertResponseIsSuccessful();
    }
}

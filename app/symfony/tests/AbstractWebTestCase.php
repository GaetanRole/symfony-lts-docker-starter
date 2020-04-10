<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @author  Gaëtan Rolé-Dubruille <gaetan.role@gmail.com>
 */
abstract class AbstractWebTestCase extends WebTestCase
{
    /** @var KernelBrowser A Web client */
    protected static $client;

    /**
     * Setting up $client var
     */
    protected function setUp(): void
    {
        parent::setUp();

        self::$client = self::createClient();
        self::$client->followRedirects(false);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown(): void
    {
        self::$client = null;

        parent::tearDown();
    }
}

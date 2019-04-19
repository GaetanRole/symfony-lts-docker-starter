<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Client;

abstract class AbstractWebTestCase extends WebTestCase
{
    /** @var Client A Client instance */
    protected $client;

    /**
     * Setting up $client var
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
    }
}

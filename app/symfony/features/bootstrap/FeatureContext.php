<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @see     http://behat.org/en/latest/quick_start.html
 *
 * @author  Gaëtan Rolé-Dubruille <gaetan.role@gmail.com>
 */
class FeatureContext implements Context
{
    /** @var KernelInterface */
    private $kernel;

    /** @var Response|null */
    private $response;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @When a user sends a request to :path
     *
     * @throws Exception When an Exception occurs during handle processing
     */
    public function aUserSendsARequestTo(string $path): void
    {
        $this->response = $this->kernel->handle(Request::create($path));
    }

    /**
     * Checks that response has specific status code.
     *
     * @Then the response status code should be :code
     */
    public function theResponseStatusCodeShouldBe(int $code): void
    {
        if ($this->response->getStatusCode() !== $code) {
            throw new RuntimeException('Different status code');
        }
    }

    /**
     * @Then the response should be received
     */
    public function theResponseShouldBeReceived(): void
    {
        if (null === $this->response) {
            throw new RuntimeException('No response received');
        }
    }
}

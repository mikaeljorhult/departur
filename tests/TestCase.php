<?php

namespace Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Mock Guzzle and setup HTTP responses.
     *
     * @param array $responses
     */
    protected function mockHttpResponses(array $responses = [])
    {
        // Default to one empty response of 200 OK.
        if (count($responses) == 0) {
            $responses[] = new Response(200, []);
        }

        // Setup client and attach responses.
        $mock    = new MockHandler($responses);
        $handler = HandlerStack::create($mock);
        $client  = new Client(['handler' => $handler]);

        // Replace Guzzle with mock in service container.
        app()->instance(Client::class, $client);
    }
}

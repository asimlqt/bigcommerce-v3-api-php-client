<?php

namespace BigCommerce\ApiV3;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;

abstract class BaseApiClient
{
    public const DEFAULT_HANDLER      = 'handler';
    public const DEFAULT_BASE_URI     = 'base_uri';
    public const DEFAULT_HEADERS      = 'headers';
    public const DEFAULT_TIMEOUT      = 'timeout';

    private const HEADERS__AUTH_CLIENT  = 'X-Auth-Client';
    private const HEADERS__AUTH_TOKEN   = 'X-Auth-Token';
    private const HEADERS__CONTENT_TYPE = 'Content-Type';
    private const HEADERS__ACCEPT       = 'Accept';
    private const APPLICATION_JSON      = 'application/json';

    private string $storeHash;

    private string $clientId;

    private string $accessToken;

    private string $baseUri;

    private ?ClientInterface $client;

    private ?RequestFactoryInterface $requestFactory;

    private ?StreamFactoryInterface $streamFactory;


    private array $debugContainer = [];

    private array $defaultClientOptions = [
        self::DEFAULT_TIMEOUT => 45,
        self::DEFAULT_HEADERS => [
            self::HEADERS__CONTENT_TYPE => self::APPLICATION_JSON,
            self::HEADERS__ACCEPT       => self::APPLICATION_JSON,
        ]
    ];

    public function __construct(
        string $storeHash,
        string $clientId,
        string $accessToken
    ) {
        $this->storeHash    = $storeHash;
        $this->clientId     = $clientId;
        $this->accessToken  = $accessToken;
        $this->setBaseUri(sprintf($this->defaultBaseUrl(), $this->storeHash));

        $this->requestFactory = null;
        $this->streamFactory = null;
        $this->client = null;
    }

    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    public function setBaseUri(string $baseUri)
    {
        $this->baseUri = $baseUri;
    }

    public function getDefaultHeaders(): array
    {
        return [
            self::HEADERS__AUTH_CLIENT  => $this->clientId,
            self::HEADERS__AUTH_TOKEN   => $this->accessToken,
            self::HEADERS__CONTENT_TYPE => self::APPLICATION_JSON,
            self::HEADERS__ACCEPT       => self::APPLICATION_JSON,
        ];
    }

    public function getRestClient(): ClientInterface
    {
        if (null === $this->client) {
            $this->client = Psr18ClientDiscovery::find();
        }

        return $this->client;
    }

    public function setRestClient(ClientInterface $client): void
    {
        $this->client = $client;
    }

    public function createRequest(string $method, string $uri): RequestInterface
    {
        $request = $this->getRequestFactory()->createRequest($method, $uri);

        foreach ($this->getDefaultHeaders() as $header => $content) {
            $request = $request->withHeader($header, $content);
        }

        return $request;
    }

    public function getRequestFactory(): RequestFactoryInterface
    {
        if (null === $this->requestFactory) {
            $this->requestFactory = Psr17FactoryDiscovery::findRequestFactory();
        }

        return $this->requestFactory;
    }

    public function getStreamFactory(): StreamFactoryInterface
    {
        if (null === $this->streamFactory) {
            $this->streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        }

        return $this->streamFactory;
    }

    public function printDebug()
    {
        foreach ($this->debugContainer as $transaction) {
            print_r(json_decode($transaction['request']->getBody()));
        }
    }

    public function printDebugLastRequest()
    {
        print_r(json_decode(array_pop($this->debugContainer)['request']->getBody()));
    }

    abstract protected function defaultBaseUrl(): string;

    public function getDebugContainer(): array
    {
        return $this->debugContainer;
    }
}

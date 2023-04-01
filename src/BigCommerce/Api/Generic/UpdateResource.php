<?php

namespace BigCommerce\ApiV3\Api\Generic;

use BigCommerce\ApiV3\Client;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

trait UpdateResource
{
    abstract public function singleResourceUrl(): string;
    abstract public function getClient(): Client;

    protected function updateResource(object $resource): ResponseInterface
    {
        $request = $this->getClient()
            ->createRequest('PUT', new Uri($this->getClient()->getBaseUri() . $this->singleResourceUrl()))
            ->withBody($this->getClient()->getStreamFactory()->createStream(json_encode($resource)));

        return $this->getClient()->getRestClient()->sendRequest($request);
    }
}

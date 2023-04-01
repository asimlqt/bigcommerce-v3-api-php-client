<?php

namespace BigCommerce\ApiV3\Api\Generic;

use BigCommerce\ApiV3\BaseApiClient;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

trait GetResource
{
    abstract public function singleResourceUrl(): string;
    abstract public function getClient(): BaseApiClient;

    protected function getResource(array $queryParams = []): ResponseInterface
    {
        $uri = new Uri($this->getClient()->getBaseUri() . $this->singleResourceUrl());
        $uri->withQuery(http_build_query($queryParams));

        $request = $this->getClient()->createRequest('GET', $uri);

        return $this->getClient()->getRestClient()->sendRequest($request);
    }
}

<?php

namespace BigCommerce\ApiV3\Api\Generic;

use BigCommerce\ApiV3\BaseApiClient;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

trait GetAllResources
{
    abstract public function multipleResourceUrl(): string;
    abstract public function getClient(): BaseApiClient;

    protected function getAllResources(array $filters = [], int $page = 1, int $limit = 250): ResponseInterface
    {
        $uri = new Uri($this->getClient()->getBaseUri() . $this->multipleResourceUrl());
        $uri->withQuery(http_build_query(array_merge($filters, [
            'page'  => $page,
            'limit' => $limit,
        ])));

        $request = $this->getClient()->getRequestFactory()->createRequest('GET', $uri);

        foreach ($this->getClient()->getDefaultHeaders() as $header => $content) {
            $request = $request->withHeader($header, $content);
        }

        return $this->getClient()->getRestClient()->sendRequest($request);
    }
}

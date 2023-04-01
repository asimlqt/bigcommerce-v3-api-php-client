<?php

namespace BigCommerce\ApiV3\Api\Generic;

use BigCommerce\ApiV3\Client;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;

trait DeleteResource
{
    abstract public function singleResourceUrl(): string;
    abstract public function getClient(): Client;

    public function delete(): ResponseInterface
    {
        $request = $this->getClient()
            ->createRequest('DELETE', new Uri($this->getClient()->getBaseUri() . $this->singleResourceUrl()));

        return $this->getClient()->getRestClient()->sendRequest($request);
    }
}

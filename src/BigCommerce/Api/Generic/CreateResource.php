<?php

namespace BigCommerce\ApiV3\Api\Generic;

use BigCommerce\ApiV3\BaseApiClient;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

trait CreateResource
{
    abstract public function multipleResourceUrl(): string;
    abstract public function getClient(): BaseApiClient;

    protected function createResource(object $resource, array $query = []): ResponseInterface
    {
        $uri = new Uri($this->getClient()->getBaseUri() . $this->multipleResourceUrl());
        $uri->withQuery(http_build_query($query));

        $request = $this->getClient()->getRequestFactory()->createRequest('POST', $uri);

//        foreach ($this->getClient()->getDefaultHeaders() as $header => $content) {
//            $request = $request->withHeader($header, $content);
//        }

        $request = $request->withBody($this->getClient()->getStreamFactory()->createStream(json_encode($resource)));

        return $this->getClient()->getRestClient()->sendRequest($request);

//        return $this->getClient()->getRestClient()->post(
//            $this->multipleResourceUrl(),
//            [
//                RequestOptions::JSON => $resource,
//                RequestOptions::QUERY => $query,
//            ]
//        );
    }
}

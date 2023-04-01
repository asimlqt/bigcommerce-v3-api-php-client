<?php

namespace BigCommerce\ApiV3\Api\Generic;

use BigCommerce\ApiV3\Client;
use GuzzleHttp\Psr7\Uri;
use Http\Message\MultipartStream\MultipartStreamBuilder;
use Psr\Http\Message\ResponseInterface;

trait CreateImage
{
    abstract public function getClient(): Client;
    abstract protected function multipleResourceUrl(): string;

    /**
     * Add an image to a resource
     *
     * @param string $filename Any path that can be opened using fopen
     * @return ResponseInterface
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function createImage(string $filename): ResponseInterface
    {
        $builder = new MultipartStreamBuilder();
        $builder->addResource('image_file', fopen($filename, 'r'));

        $request = $this->getClient()
            ->createRequest('POST', new Uri($this->getClient()->getBaseUri() . $this->multipleResourceUrl()))
            ->withHeader('Content-Type', sprintf('multipart/form-data; boundary="%s"', $builder->getBoundary()))
            ->withBody($builder->build());

        return $this->getClient()->getRestClient()->sendRequest($request);
    }
}

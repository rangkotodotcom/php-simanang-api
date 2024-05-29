<?php

namespace Rangkotodotcom\Simanang\Services\Schools;

use Illuminate\Support\Collection;
use Rangkotodotcom\Simanang\Networks\HttpClient;


class GetSchool implements School
{
    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected string $response;

    /**
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getSchool(): School
    {
        $endpoint = '/api/school';
        $this->response = $this->httpClient->sendRequest('GET', $endpoint);

        return $this;
    }

    public function getVision(): School
    {
        $endpoint = '/api/school/vision';
        $this->response = $this->httpClient->sendRequest('GET', $endpoint);

        return $this;
    }

    public function getMision(): School
    {
        $endpoint = '/api/school/mision';
        $this->response = $this->httpClient->sendRequest('GET', $endpoint);

        return $this;
    }

    public function getGallery(): School
    {
        $endpoint = '/api/school/gallery';
        $this->response = $this->httpClient->sendRequest('GET', $endpoint);

        return $this;
    }

    public function getHeadMaster(): School
    {
        $endpoint = '/api/school/headmaster';
        $this->response = $this->httpClient->sendRequest('GET', $endpoint);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getResponse(): Collection
    {
        return collect(json_decode($this->response));
    }
}

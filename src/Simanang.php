<?php

namespace Rangkotodotcom\Simanang;

use Illuminate\Support\Collection;
use Rangkotodotcom\Simanang\Networks\HttpClient;

class Simanang
{
    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getSchool(): Collection
    {
        $endpoint = '/api/school';
        $result = $this->httpClient->sendRequest('GET', $endpoint);

        return collect($result);
    }

    public function getHeadMaster(): Collection
    {
        $endpoint = '/api/school/headmaster';
        $result = $this->httpClient->sendRequest('GET', $endpoint);

        return collect($result);
    }

    public function getCurrentSemester(): Collection
    {
        $endpoint = '/api/v1/semester/current';
        $result = $this->httpClient->sendRequest('GET', $endpoint);

        return collect($result);
    }

    public function getStudent(string $param = null, array $data): Collection
    {
        if ($param == null) {
            $endpoint = '/api/v1/student';
        } else {
            $endpoint = '/api/v1/student/' . $param;
        }

        $result = $this->httpClient->sendRequest('GET', $endpoint, $data);

        return collect($result);
    }

    public function getTeacher(string $param = null, array $data): Collection
    {
        if ($param == null) {
            $endpoint = '/api/v1/teacher';
        } else {
            $endpoint = '/api/v1/teacher/' . $param;
        }

        $result = $this->httpClient->sendRequest('GET', $endpoint, $data);

        return collect($result);
    }
}

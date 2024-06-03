<?php

namespace Rangkotodotcom\Simanang;

use Illuminate\Support\Collection;
use Rangkotodotcom\Simanang\Networks\HttpClient;
use Rangkotodotcom\Simanang\Services\QrCodes\QrCode;
use Rangkotodotcom\Simanang\Exceptions\InvalidQrCodeException;
use Rangkotodotcom\Simanang\Services\QrCodes\QrCodePresence;

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

    public function connect(): bool
    {
        return $this->httpClient->checkConnection();
    }

    public function getSchool(): Collection
    {
        $endpoint = '/api/school';
        $result = $this->httpClient->sendRequest('GET', $endpoint);

        return collect($result);
    }

    public function getVision(): Collection
    {
        $endpoint = '/api/school/vision';
        $result = $this->httpClient->sendRequest('GET', $endpoint);

        return collect($result);
    }

    public function getMision(): Collection
    {
        $endpoint = '/api/school/mision';
        $result = $this->httpClient->sendRequest('GET', $endpoint);

        return collect($result);
    }

    public function getGallery(): Collection
    {
        $endpoint = '/api/school/gallery';
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

    public function getStudent(string $param = null, array $data = []): Collection
    {
        if ($param == null) {
            $endpoint = '/api/v1/student';
        } else {
            $endpoint = '/api/v1/student/' . $param;
        }

        $result = $this->httpClient->sendRequest('GET', $endpoint, $data);

        return collect($result);
    }

    public function getTeacher(string $param = null, array $data = []): Collection
    {
        if ($param == null) {
            $endpoint = '/api/v1/teacher';
        } else {
            $endpoint = '/api/v1/teacher/' . $param;
        }

        $result = $this->httpClient->sendRequest('GET', $endpoint, $data);

        return collect($result);
    }

    public function getSubject(string $param = null): Collection
    {
        if ($param == null) {
            $endpoint = '/api/v1/subject';
        } else {
            $endpoint = '/api/v1/subject/' . $param;
        }

        $result = $this->httpClient->sendRequest('GET', $endpoint);

        return collect($result);
    }

    public function validasiQrCode(array $data, string $typeQrCode): QrCode
    {
        if ($typeQrCode == 'presence') {
            $handler = new QrCodePresence($this->httpClient);
            return $handler->validationQrCode($data);
        }

        throw new InvalidQrCodeException("metode yang digunakan tidak didukung.");
    }

    public function storeQrCode(array $data, string $typeQrCode): QrCode
    {
        if ($typeQrCode == 'presence') {
            $handler = new QrCodePresence($this->httpClient);
            return $handler->storeQrCode($data);
        }

        throw new InvalidQrCodeException("metode yang digunakan tidak didukung.");
    }
}

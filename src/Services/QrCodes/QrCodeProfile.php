<?php

namespace Rangkotodotcom\Simanang\Services\QrCodes;

use Illuminate\Support\Collection;
use Rangkotodotcom\Simanang\Networks\HttpClient;
use Rangkotodotcom\Simanang\Services\QrCodes\QrCode;
use Rangkotodotcom\Simanang\Validators\QrCodeProfileFormValidation;

class QrCodeProfile implements QrCode
{

    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var object
     */
    protected object $response;

    /**
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param array $data
     * @return QrCode
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validationQrCode(array $data): QrCode
    {
        $validated = QrCodeProfileFormValidation::validate($data);

        $this->response = $this->httpClient->sendRequest('GET', '/api/v1/profile/validation/' . $validated['qrcode']);

        return $this;
    }


    /**
     * @param array $data
     * @return QrCode
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeQrCode(array $data): QrCode
    {
        $validated = QrCodeProfileFormValidation::validate($data);

        $this->response = $this->httpClient->sendRequest('POST', '/api/v1/profile', $validated);

        return $this;
    }


    /**
     * @return Collection
     */
    public function getResponse(): Collection
    {
        return collect($this->response);
    }
}

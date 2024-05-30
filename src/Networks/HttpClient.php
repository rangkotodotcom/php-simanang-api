<?php

namespace Rangkotodotcom\Simanang\Networks;

use Exception;
use InvalidArgumentException;
use Illuminate\Support\Facades\Http;
use League\Config\Exception\InvalidConfigurationException;

class HttpClient
{
    const HTTP_GET = 'GET';
    const HTTP_POST = 'POST';
    const HTTP_PUT = 'PUT';
    const HTTP_DELETE = 'DELETE';

    protected $productionUrl = 'https://simanang.sman1el.sch.id';
    protected $sandboxUrl = 'https://staging-simanang.sman1el.sch.id';
    protected $baseUrl;
    protected $tokenUrl;

    protected $_accessToken;
    protected $_expiredIn;

    public function __construct(string $mode, string $clientId, string $clientSecret)
    {
        $mode = $mode ?? config('simanang.simanang_mode');
        $clientId = $clientId ?? config('simanang.simanang_client_id');
        $clientSecret = $clientSecret ?? config('simanang.simanang_client_secret');

        if ($mode == '' || $clientId == '' || $clientSecret == '') {
            throw new InvalidConfigurationException("Client ID atau Client Secret belum dikonfigurasi");
        }

        $this->baseUrl = $mode == 'production' ? $this->productionUrl : $this->sandboxUrl;
        $this->tokenUrl = $this->baseUrl . '/oauth/token';

        $path = base_path('json');

        if (!file_exists($path)) {
            mkdir($path, 0755);
        }

        if (file_exists($path . '/simanang_token.json')) {
            $jsonToken = file_get_contents($path . '/simanang_token.json');

            $decJsonToken = json_decode($jsonToken);

            if ($decJsonToken->expired_time > time()) {
                $token = $decJsonToken->token;
                $this->_accessToken = $token;
            }
        } else {
            try {
                $response = Http::asForm()->post($this->tokenUrl, [
                    "client_id"         => $clientId,
                    "client_secret"     => $clientSecret,
                    "grant_type"        => "client_credentials"
                ]);

                if ($response->successful()) {
                    $decResponse = json_decode($response->getBody());
                    $this->_accessToken = $decResponse->access_token;
                    $this->_expiredIn = $decResponse->expires_in;

                    $token = $this->_accessToken;

                    $dataJsonToken = [
                        'expired_time'  => time() + $this->_expiredIn,
                        'token'         => $token
                    ];

                    $encJsonPost = json_encode($dataJsonToken);

                    file_put_contents($path . '/simanang_token.json', $encJsonPost);
                }
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array $data
     * @return string
     * @throws Exception
     */
    public function sendRequest(string $method, string $endpoint, array $data = [])
    {
        if ($method == self::HTTP_GET) {
            return $this->sendGetRequest($this->baseUrl . $endpoint, $data);
        }

        if ($method == self::HTTP_POST) {
            return $this->sendPostRequest($this->baseUrl . $endpoint, $data);
        }

        if ($method == self::HTTP_PUT) {
            return $this->sendPutRequest($this->baseUrl . $endpoint, $data);
        }

        if ($method == self::HTTP_DELETE) {
            return $this->sendDeleteRequest($this->baseUrl . $endpoint, $data);
        }

        throw new InvalidArgumentException(sprintf("http method %s tidak didukung.", $method));
    }

    protected function sendGetRequest(string $fullEndPoint, array $data)
    {
        try {
            $response = Http::withToken($this->_accessToken)->get($fullEndPoint, $data);

            if ($response->successful()) {
                $body = $response->body();

                return json_decode($body);
            }
            $response->toException();

            return json_decode($response);
        } catch (Exception $e) {
            return (object)[
                'status'    => false,
                'message'   => $e->getMessage()
            ];
        }
    }


    protected function sendPostRequest(string $fullEndPoint, array $data = [])
    {
        try {
            $response = Http::withToken($this->_accessToken)->acceptJson()->post($fullEndPoint, $data);

            if ($response->successful()) {
                $body = $response->body();

                return json_decode($body);
            }
            $response->toException();

            return json_decode($response);
        } catch (Exception $e) {
            return (object)[
                'status'    => false,
                'message'   => $e->getMessage()
            ];
        }
    }

    protected function sendPutRequest(string $fullEndPoint, array $data = [])
    {
        try {
            $response = Http::withToken($this->_accessToken)->acceptJson()->put($fullEndPoint, $data);

            if ($response->successful()) {
                $body = $response->body();

                return json_decode($body);
            }
            $response->toException();

            return json_decode($response);
        } catch (Exception $e) {
            return (object)[
                'status'    => false,
                'message'   => $e->getMessage()
            ];
        }
    }

    protected function sendDeleteRequest(string $fullEndPoint, array $data = [])
    {
        try {
            $response = Http::withToken($this->_accessToken)->acceptJson()->delete($fullEndPoint, $data);

            if ($response->successful()) {
                $body = $response->body();

                return json_decode($body);
            }
            $response->toException();

            return json_decode($response);
        } catch (Exception $e) {
            return (object)[
                'status'    => false,
                'message'   => $e->getMessage()
            ];
        }
    }
}

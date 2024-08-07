<?php

namespace Rangkotodotcom\Simanang\Networks;

use Exception;
use Illuminate\Support\Str;
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
    protected $stagingUrl = 'https://staging-simanang.sman1el.sch.id';
    protected $clientId;
    protected $clientSecret;
    protected $baseUrl = 'http://localhost';
    protected $tokenUrl;

    protected $_accessToken;
    protected $_expiredIn;
    protected $_isConnected;

    public function __construct(string $mode, string $clientId, string $clientSecret)
    {
        $mode = $mode ?? config('simanang.simanang_mode');
        $clientId = $clientId ?? config('simanang.simanang_client_id');
        $clientSecret = $clientSecret ?? config('simanang.simanang_client_secret');

        if ($mode == '' || $clientId == '' || $clientSecret == '') {
            throw new InvalidConfigurationException("Client ID atau Client Secret belum dikonfigurasi");
        }

        if ($mode == 'production') {
            $this->baseUrl = $this->productionUrl;
        }

        if ($mode == 'development') {
            $this->baseUrl = $this->stagingUrl;
        }

        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->tokenUrl = $this->baseUrl . '/oauth/token';

        $explodeDomain = explode('/', Str::remove(':', $this->baseUrl));
        if ($explodeDomain[0] != '') {
            $port = $explodeDomain[0] == 'https' ? 443 : 80;
            $domain = $explodeDomain[2];
            $connected = @fsockopen($domain, $port);
            if ($connected) {
                $this->_isConnected = true;
            } else {
                $this->_isConnected = false;
            }
        } else {
            $this->_isConnected = false;
        }

        if ($this->_isConnected) {
            $this->getAccessToken();
        } else {
            throw new Exception('Failed connect to SIMANANG Server : ' . $this->baseUrl);
        }
    }

    protected function getAccessToken(bool $isRefresh = false)
    {
        $path = base_path('json');

        if (!file_exists($path)) {
            mkdir($path, 0755);
        }

        if ($isRefresh) {
            try {
                $response = Http::asForm()->post($this->tokenUrl, [
                    "client_id"         => $this->clientId,
                    "client_secret"     => $this->clientSecret,
                    "grant_type"        => "client_credentials"
                ]);

                if ($response->successful()) {
                    $decResponse = json_decode($response->getBody());
                    $this->_accessToken = $decResponse->access_token;
                    $this->_expiredIn = $decResponse->expires_in;

                    $token = $this->_accessToken;

                    $dataJsonToken = [
                        'expired_time'  => (time() + $this->_expiredIn) - 3600,
                        'token'         => $token
                    ];

                    $encJsonPost = json_encode($dataJsonToken);

                    file_put_contents($path . '/simanang_token.json', $encJsonPost);
                }
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }

            return;
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
                    "client_id"         => $this->clientId,
                    "client_secret"     => $this->clientSecret,
                    "grant_type"        => "client_credentials"
                ]);

                if ($response->successful()) {
                    $decResponse = json_decode($response->getBody());
                    $this->_accessToken = $decResponse->access_token;
                    $this->_expiredIn = $decResponse->expires_in;

                    $token = $this->_accessToken;

                    $dataJsonToken = [
                        'expired_time'  => (time() + $this->_expiredIn) - 3600,
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

        throw new InvalidArgumentException(sprintf("http method %s not supported", $method));
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

            $response = json_decode($response);

            if ($response->code == 401) {
                $this->getAccessToken(true);
                return $this->sendGetRequest($fullEndPoint, $data);
            }

            return $response;
        } catch (Exception $e) {
            return (object)[
                'status'    => false,
                'message'   => $e->getMessage()
            ];
        }
    }

    protected function sendPostRequest(string $fullEndPoint, array $data)
    {
        try {
            $response = Http::withToken($this->_accessToken)->acceptJson()->post($fullEndPoint, $data);

            if ($response->successful()) {
                $body = $response->body();

                return json_decode($body);
            }
            $response->toException();

            $response = json_decode($response);

            if ($response->code == 401) {
                $this->getAccessToken(true);
                return $this->sendPostRequest($fullEndPoint, $data);
            }

            return $response;
        } catch (Exception $e) {
            return (object)[
                'status'    => false,
                'message'   => $e->getMessage()
            ];
        }
    }

    protected function sendPutRequest(string $fullEndPoint, array $data)
    {
        try {
            $response = Http::withToken($this->_accessToken)->acceptJson()->put($fullEndPoint, $data);

            if ($response->successful()) {
                $body = $response->body();

                return json_decode($body);
            }
            $response->toException();

            $response = json_decode($response);

            if ($response->code == 401) {
                $this->getAccessToken(true);
                return $this->sendPutRequest($fullEndPoint, $data);
            }

            return $response;
        } catch (Exception $e) {
            return (object)[
                'status'    => false,
                'message'   => $e->getMessage()
            ];
        }
    }

    protected function sendDeleteRequest(string $fullEndPoint, array $data)
    {
        try {
            $response = Http::withToken($this->_accessToken)->acceptJson()->delete($fullEndPoint, $data);

            if ($response->successful()) {
                $body = $response->body();

                return json_decode($body);
            }
            $response->toException();

            $response = json_decode($response);

            if ($response->code == 401) {
                $this->getAccessToken(true);
                return $this->sendDeleteRequest($fullEndPoint, $data);
            }

            return $response;
        } catch (Exception $e) {
            return (object)[
                'status'    => false,
                'message'   => $e->getMessage()
            ];
        }
    }

    public function checkConnection()
    {
        return $this->_isConnected;
    }
}

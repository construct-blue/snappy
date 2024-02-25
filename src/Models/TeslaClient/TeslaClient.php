<?php

declare(strict_types=1);

namespace Blue\Models\TeslaClient;

use Blue\Core\Database\Storable;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Laminas\Diactoros\Uri;

use function base64_encode;
use function hash;
use function http_build_query;
use function json_decode;
use function json_encode;
use function parse_str;
use function rtrim;
use function strlen;
use function substr;
use function time;
use function uniqid;

final class TeslaClient implements Storable
{
    private ?string $id = null;
    private ?string $access_token;
    private ?string $refresh_token;
    private ?string $id_token;
    private ?int $expires_in;
    private ?int $expires;
    private ?string $token_type;

    private string $state;
    private string $code;

    private ?string $proxy = null;


    public function __construct(?array $data = [])
    {
        $this->id = uniqid();

        if (!isset($this->state)) {
            $this->state = $data['state'] ?? uniqid();
        }
        if (!isset($this->code)) {
            $this->code = $data['code'] ?? $this->generateCode();
        }
    }

    /**
     * @return mixed|string
     */
    public function getState(): mixed
    {
        return $this->state;
    }

    /**
     * @return mixed|string
     */
    public function getCode(): mixed
    {
        return $this->code;
    }

    /**
     * @param string|null $proxy
     */
    public function setProxy(?string $proxy): void
    {
        $this->proxy = $proxy;
    }


    public function toStorage(): array
    {
        return [
            'id' => $this->id,
            'state' => $this->state,
            'code' => $this->code,
            'access_token' => $this->access_token ?? null,
            'refresh_token' => $this->refresh_token ?? null,
            'id_token' => $this->id_token ?? null,
            'expires_in' => $this->expires_in ?? null,
            'expires' => $this->expires ?? null,
            'token_type' => $this->token_type ?? null,
            'proxy' => $this->proxy ?? null,
        ];
    }

    public static function fromStorage(array $data): static
    {
        $client = new TeslaClient();
        $client->id = $data['id'];
        $client->state = $data['state'];
        $client->code = $data['code'];
        $client->access_token = $data['access_token'];
        $client->refresh_token = $data['refresh_token'];
        $client->id_token = $data['id_token'];
        $client->expires_in = $data['expires_in'];
        $client->expires = $data['expires'];
        $client->token_type = $data['token_type'];
        $client->proxy = $data['proxy'] ?? null;

        return $client;
    }

    public function getProxy(): ?string
    {
        return $this->proxy;
    }

    /**
     * @throws TeslaClientException
     * @throws Exception
     */
    public function refreshAccessToken()
    {
        $requestBody = [
            "grant_type" => "refresh_token",
            "client_id" => "ownerapi",
            "refresh_token" => $this->refresh_token,
            "scope" => "openid email offline_access"
        ];

        $client = $this->getClient();
        $response = $client->post(
            'https://auth.tesla.com/oauth2/v3/token',
            [
                RequestOptions::HEADERS => ['content-type' => 'application/json'],
                RequestOptions::BODY => json_encode($requestBody),
                RequestOptions::PROXY => $this->getProxy(),
                'curl' => $this->getCurlOptions()
            ]
        );

        $responseData = json_decode($response->getBody()->getContents(), true);
        if (isset($responseData['access_token'])) {
            $this->access_token = $responseData['access_token'];
            $this->refresh_token = $responseData['refresh_token'];
            $this->id_token = $responseData['id_token'];
            $this->expires_in = $responseData['expires_in'];
            $this->token_type = $responseData['token_type'];
            $this->expires = time() + $this->expires_in;
            TeslaClientRepository::instance()->save($this);
            return $responseData;
        }
        throw new TeslaClientException('Unable to refresh access_token');
    }

    private function getClient()
    {
        return new Client();
    }

    private function getCurlOptions(): array
    {
        return [
            CURLOPT_SSLVERSION => CURL_SSLVERSION_MAX_TLSv1_2
        ];
    }

    public function fetchAccessToken(string $redirectUri)
    {
        $url = new Uri($redirectUri);
        parse_str($url->getQuery(), $params);
        if (isset($params['code'])) {
            $requestBody = [
                "grant_type" => "authorization_code",
                "client_id" => "ownerapi",
                "code" => $params['code'],
                "code_verifier" => $this->code,
                "redirect_uri" => "https://auth.tesla.com/void/callback"
            ];
            $client = $this->getClient();
            $response = $client->post(
                'https://auth.tesla.com/oauth2/v3/token',
                [
                    RequestOptions::HEADERS => ['content-type' => 'application/json'],
                    RequestOptions::BODY => json_encode($requestBody),
                    RequestOptions::PROXY => $this->getProxy(),
                    'curl' => $this->getCurlOptions()
                ]
            );
            $responseData = json_decode($response->getBody()->getContents(), true);
            if (isset($responseData['access_token'])) {
                $this->access_token = $responseData['access_token'];
                $this->refresh_token = $responseData['refresh_token'];
                $this->id_token = $responseData['id_token'];
                $this->expires_in = $responseData['expires_in'];
                $this->token_type = $responseData['token_type'];
                $this->expires = time() + $this->expires_in;
                TeslaClientRepository::instance()->save($this);
                return $responseData;
            }
        }
        throw new TeslaClientException('Unable to fetch access_token');
    }

    public function getLoginUri()
    {
        $codeChallenge = rtrim(strtr(base64_encode(hash('sha256', $this->code, true)), '+/', '-_'), '=');
        $uri = new Uri('https://auth.tesla.com/oauth2/v3/authorize');
        $uri = $uri->withQuery(
            http_build_query([
                'client_id' => 'ownerapi',
                'code_challenge' => $codeChallenge,
                'code_challenge_method' => 'S256',
                'redirect_uri' => 'https://auth.tesla.com/void/callback',
                'response_type' => 'code',
                'scope' => 'openid email offline_access',
                'state' => $this->state,
            ])
        );
        return $uri;
    }


    private function generateCode(): string
    {
        $result = '';
        while (true) {
            $result .= uniqid();
            if (strlen($result) > 86) {
                return substr($result, 0, 86);
            }
        }
    }

    /**
     * @return ?string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->access_token;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refresh_token;
    }

    /**
     * @return string
     */
    public function getIdToken(): ?string
    {
        return $this->id_token;
    }

    /**
     * @return int
     */
    public function getExpiresIn(): int
    {
        return $this->expires_in;
    }

    /**
     * @return int
     */
    public function getExpires(): int
    {
        return $this->expires;
    }

    /**
     * @return string
     */
    public function getTokenType(): string
    {
        return $this->token_type;
    }

    public function getEmail(): ?string
    {
        if (!$this->getIdToken()) {
            return null;
        }
        return json_decode(
            base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $this->getIdToken())[1])))
        )->email;
    }

    public function getVehicles(): array
    {
        if (isset($this->expires)) {
            $this->expires_in = $this->expires - time();
            if ($this->expires_in < 3600) {
                $this->refreshAccessToken();
            }
        }

        $client = $this->getClient();
        $response = $client->get('https://owner-api.teslamotors.com/api/1/products', [
            RequestOptions::HEADERS => ['Authorization' => "Bearer {$this->getAccessToken()}"],
            RequestOptions::PROXY => $this->getProxy(),
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getVehicle(string $id): array
    {
        if (isset($this->expires)) {
            $this->expires_in = $this->expires - time();
            if ($this->expires_in < 3600) {
                $this->refreshAccessToken();
            }
        }

        $client = $this->getClient();
        $response = $client->get("https://owner-api.teslamotors.com/api/1/vehicles/$id", [
            RequestOptions::HEADERS => ['Authorization' => "Bearer {$this->getAccessToken()}"]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getVehicleData(string|int $id): VehicleData
    {
        if (isset($this->expires)) {
            $this->expires_in = $this->expires - time();
            if ($this->expires_in < 3600) {
                $this->refreshAccessToken();
            }
        }

        $client = $this->getClient();

        $response = $client->get("https://owner-api.teslamotors.com/api/1/vehicles/$id/vehicle_data", [
            RequestOptions::HEADERS => ['Authorization' => "Bearer {$this->getAccessToken()}"],
            RequestOptions::PROXY => $this->getProxy(),
        ]);

        return new VehicleData(json_decode($response->getBody()->getContents(), true)['response'] ?? []);
    }
}

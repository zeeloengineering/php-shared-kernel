<?php namespace StraTDeS\SharedKernel\Infrastructure;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GuzzleAPIRestClient implements APIRestClientInterface
{
    /**
     * @param string $url
     * @param array $parameters
     * @param array $headers
     * @return array
     * @throws GuzzleException
     */
    public function get(string $url, array $parameters = [], array $headers = []): array
    {
        $urlWithParameters = $url;
        if (!empty($parameters)) {
            $urlWithParameters .= '?'.http_build_url($parameters);
        }
        return $this->execute($urlWithParameters, 'GET', $parameters, $headers);
    }

    /**
     * @param string $url
     * @param array $parameters
     * @param array $headers
     * @return array
     * @throws GuzzleException
     */
    public function post(string $url, array $parameters = [], array $headers = []): array
    {
        return $this->execute($url, 'POST', $parameters, $headers);
    }

    /**
     * @param string $url
     * @param array $parameters
     * @param array $headers
     * @return array
     * @throws GuzzleException
     */
    public function patch(string $url, array $parameters = [], array $headers = []): array
    {
        return $this->execute($url, 'PATCH', $parameters, $headers);
    }

    /**
     * @param string $url
     * @param array $parameters
     * @param array $headers
     * @return array
     * @throws GuzzleException
     */
    public function delete(string $url, array $parameters = [], array $headers = []): array
    {
        return $this->execute($url, 'DELETE', $parameters, $headers);
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $parameters
     * @param array $headers
     * @return array
     * @throws GuzzleException
     */
    public function execute(string $url, string $method, array $parameters = [], array $headers = []): array
    {
        $client = new Client();

        $res = $client->request($method, $url, [
            'headers' => $headers,
            'form_params' => $parameters
        ]);

        return [
            'status_code' => $res->getStatusCode(),
            'body' => $res->getBody()
        ];
    }
}
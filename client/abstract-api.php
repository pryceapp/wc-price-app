<?php


abstract class AbstractApi
{

    /**
     * Request headers
     * 
     * @var array
     */
    protected $headers;

    /**
     * Api Endpoint
     *
     * @var string
     */
    protected $endpoint = 'https://www.pryce.app/api/v1';

    /**
     * @var Meta
     */
    protected $meta;

    /**
     * Constructor
     *
     * @param  string            $ambiente  (optional) Ambiente da API
     */
    public function __construct($token)
    {
        $this->headers = [
            'headers' => [
                'Authorization' => 'Token ' . $token,
                'Content-Type' => 'application/json'
            ]
        ];
    }

    /**
     * Extract results meta
     *
     * @param   \stdClass  $data  Meta data
     * @return  Meta
     */
    protected function extractMeta(\StdClass $data)
    {
        $this->meta = new Meta($data);

        return $this->meta;
    }

    /**
     * Return results meta
     *
     * @return  Meta
     */
    public function getMeta()
    {
        return $this->meta;
    }

    public function hasResponseError($response)
    {
        if (is_wp_error($response) || !is_array($response)) {
            error_log("[pryce.app] could not get pryce, error: " . json_encode($response['body']));
            return true;
        }

        if ($response["http_response"]->get_status() !== 200) {
            error_log("[pryce.app] could not get pryce, error: " . json_encode($response['body']));
            return true;
        }

        return false;
    }
}

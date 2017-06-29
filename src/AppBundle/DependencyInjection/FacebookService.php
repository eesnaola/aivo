<?php

namespace AppBundle\DependencyInjection;

use GuzzleHttp\Psr7;

class FacebookService
{

    protected $facebookToken;
    protected $client;

    public function __construct($facebookToken, $logger)
    {
        $this->client = new \GuzzleHttp\Client(['base_uri' => "https://graph.facebook.com"]);
        $this->facebookToken = $facebookToken;
        $this->logger = $logger;
    }

    public function getUser($id)
    {
        try {
            $response = $this->client->request('GET', $id, ['query' => ['access_token' => $this->facebookToken, 'fields' => "id,first_name,last_name"]]);
            if ($response->getStatusCode() == 200){
                return json_decode($response->getBody());
            }
        } catch (ClientException $e) {
            $this->logger->error(Psr7\str($e->getRequest()));
            $this->logger->error(Psr7\str($e->getResponse()));
        } catch (\Exception $e) {
        }
        throw new \Exception;
    }

}

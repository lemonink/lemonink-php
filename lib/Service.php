<?php

namespace LemonInk;

class Service extends \GuzzleHttp\Client
{
  protected $endpoint = "https://api.lemonink.co/v1/";

  protected $apiKey;

  public function __construct($apiKey)
  {
    parent::__construct([
      // Used in Guzzle >= 6.x
      "base_uri" => $this->getEndpoint(),
       // Used in Guzzle <= 5.x
      "base_url" => $this->getEndpoint()
    ]);

    $this->setApiKey($apiKey);
  }

  public function getEndpoint()
  {
    return $this->endpoint;
  }

  public function setApiKey($apiKey)
  {
    $this->apiKey = (string) $apiKey;
  }

  public function getApiKey()
  {
    return $this->apiKey;
  }

  public function getDefaultHeaders()
  {
    return [
      "Authorization" => "Token token=" . $this->getApiKey(),
      "Content-Type"  => "application/json",
      "User-Agent"    => "lemonink-php"
    ];
  }

  // Used in Guzzle >= 6.x
  public function requestAsync($method, $uri = '', array $options = [])
  {
    if (!isset($options["headers"])) {
      $options["headers"] = array();
    }
    $options["headers"] = array_merge($this->getDefaultHeaders(), $options["headers"]);
    $options["http_errors"] = false;

    return parent::requestAsync($method, $uri, $options);
  }

  // Used in Guzzle <= 5.x
  public function createRequest($method, $url = '', array $options = [])
  {
    if (!isset($options["headers"])) {
      $options["headers"] = array();
    }
    $options["headers"] = array_merge($this->getDefaultHeaders(), $options["headers"]);

    return parent::createRequest($method, $url, $options);
  }
}

<?php

namespace LemonInk;

class Service extends \GuzzleHttp\Client
{
  // protected $endpoint = "https://api.lemonink.co/v1";
  protected $endpoint = "http://lemonink-api.dev/v1/";
  protected $apiKey;

  public function __construct($apiKey)
  {
    parent::__construct([
      "base_uri" => $this->getEndpoint()
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

  public function requestAsync($method, $uri = '', array $options = [])
  {
    if (!isset($options["headers"])) {
      $options["headers"] = array();
    }
    $options["headers"] = array_merge($this->getDefaultHeaders(), $options);
    $options["http_errors"] = false;

    return parent::requestAsync($method, $uri, $options);
  }
}

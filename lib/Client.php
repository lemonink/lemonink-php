<?php

namespace LemonInk;

function camelcase2underscore($string) {
  return strtolower(preg_replace(
        ["/([A-Z]+)/", "/_([A-Z]+)([A-Z][a-z])/"],
        ["_$1", "_$1_$2"],
        lcfirst($string)
    ));
}

function underscore2camelcase($string) {
  return lcfirst(implode(
    "",
    array_map(
      "ucfirst",
      explode("_", $string)
    )
  ));
}

class Client
{
  protected $apiKey;
  protected $service;

  public function __construct($apiKey)
  {
    $this->apiKey = $apiKey;
  }

  public function save($model)
  {
    $uri = $this->getUriFor($model, $model->getId());
    $action = $model->isPersisted() ? "patch" : "post";

    $json = $this->serialize($model->getModelName(), $model->toArray());

    $response = $this->getService()->$action($uri, ["json" => $json]);

    if ($response->getStatusCode() < 300) {
      // var_dump($this->deserialize($model->getModelName(), $response->getBody()));
      $model->setAttributes($this->deserialize($model->getModelName(), $response->getBody()));
      var_dump($model);
    } else {
      $errors = $this->deserializeErrors($response->getBody());
      var_dump($errors);
      $error = $errors[0];
      throw new Exception($error["title"], intval($error["code"]));
    }
  }

  protected function getService()
  {
    if (!$this->service) {
      $this->service = new Service($this->apiKey);
    }

    return $this->service;
  }

  protected function serialize($modelName, $array)
  {
    $output = [];

    foreach ($array as $key => $value) {
      $output[camelcase2underscore($key)] = $value;
    }

    return [
      camelcase2underscore($modelName) => $output
    ];
  }

  protected function deserialize($modelName, $data)
  {
    $json = json_decode($data, true);
    $json = $json[camelcase2underscore($modelName)];

    $attributes = [];

    foreach ($json as $key => $value) {
      $attributes[underscore2camelcase($key)] = $value;
    }

    return $attributes;
  }

  protected function deserializeErrors($data)
  {
    $json = json_decode($data, true);
    $json = $json["errors"];

    $errors = [];

    foreach ($json as $errorJson) {
      $error = [];
      foreach ($errorJson as $key => $value) {
        $error[underscore2camelcase($key)] = $value;
      }
      $errors[] = $error;
    }

    return $errors;
  }

  protected function getUriFor($model, $id)
  {
    // Crude pluralization. Sufficient for now.
    $parts = [$model->getModelName() . "s"];
    if ($id) {
      $parts[] = $id;
    }
    return implode($parts, "/");
  }
}

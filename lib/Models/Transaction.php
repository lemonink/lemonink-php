<?php

namespace LemonInk\Models;

class Transaction extends Base
{
  const DOWNLOADS_ENDPOINT = "https://dl.lemonink.co/transactions";

  protected $attributeNames = ["masterId", "watermarkValue", "token", "status"];

  protected $masterId;
  protected $watermarkValue;
  protected $token;
  protected $status;

  public function setMasterId($masterId)
  {
    $this->masterId = $masterId;
  }

  public function getMasterId()
  {
    return $this->masterId;
  }

  public function setWatermarkValue($watermarkValue)
  {
    $this->watermarkValue = $watermarkValue;
  }

  public function getWatermarkValue()
  {
    return $this->watermarkValue;
  }

  public function getToken()
  {
    return $this->token;
  }

  protected function setToken($token)
  {
    $this->token = $token;
  }

  public function getStatus()
  {
    return $this->status;
  }

  protected function setStatus($status)
  {
    $this->status = $status;
  }

  public function getUrl()
  {
    return join("/", [
      self::DOWNLOADS_ENDPOINT,
      $this->getToken(),
      $this->getId()
    ]);
  }
};

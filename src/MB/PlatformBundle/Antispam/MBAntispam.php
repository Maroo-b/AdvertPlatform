<?php

namespace MB\PlatformBundle\Antispam;

class MBAntispam
{

  private $minLength;
  public function __construct($minLength)
  {
    $this->minLength = (int) $minLength;
  }
  public function isSpam($text)
  {
    return strlen($text) < $this->minLength;
  }
}

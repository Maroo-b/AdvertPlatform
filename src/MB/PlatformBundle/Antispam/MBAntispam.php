<?php

namespace MB\PlatformBundle\Antispam;

class MBAntispam extends \Twig_Extension
{

  private $minLength;
  protected $msg;
  public function __construct($minLength)
  {
    $this->minLength = (int) $minLength;
  }
  public function isSpam($text)
  {
    if(strlen($text) < $this->minLength){
      return $this->msg;
    }
  }

  public function getFunctions()
  {
    return array(
      'checkIfSpam' => new \Twig_Function_Method($this, 'isSpam')
    );
  }

  public function setMsg($msg)
  {
    $this->msg = $msg;
  }

  public function getName()
  {
    return 'MBAntispam';
  }
}

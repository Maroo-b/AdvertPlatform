<?php

namespace MB\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MBUserBundle extends Bundle
{
  public function getParent()
  {
    return 'FOSUserBundle';
  }
}

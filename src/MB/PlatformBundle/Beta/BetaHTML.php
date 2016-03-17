<?php

namespace MB\PlatformBundle\Beta;

use Symfony\Component\HttpFoundation\Response;

class BetaHTML
{
  public function displayBera(Response $response, $remainingDays)
  {
    $content = $response->getContent();

    $html = '<span style="color: red; font-size: 0.5em;"> - Beta J-'.(int) $remainingDays.' !</span>';

    $content = preg_replace(
      '#<h1>(.*?)</h1>#iU',
      '<h1>$2'.$html.'</h1>',
      $content,
      1
    );
  }
}

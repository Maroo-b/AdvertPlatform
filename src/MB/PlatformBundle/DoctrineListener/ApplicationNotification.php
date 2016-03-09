<?php

namespace MB\PlatformBundle\DoctrineListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use MB\PlatformBundle\Entity\Application;

class ApplicationNotification
{
  private $mailer;

  public function __construct(\Swift_Mailer $mailer)
  {
    $this->mailer = $mailer;
  }

  public function postPersist(LifecycleEventArgs $args)
  {
    $entity = $args->getEntity();

    if(!$entity instanceof Application){
      return;
    }

    $message = new \Swift_Message('Vous avez recevez une nouvelle candidature');

    $message->addTo($entity->getAdvert()->getAuthor())
            ->addFrom('admin@test.com');

    $this->mailer->send($message);
  }
}

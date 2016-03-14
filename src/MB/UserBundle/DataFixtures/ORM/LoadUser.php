<?php

namespace MB\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MB\UserBundle\Entity\User;

class LoadUser implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
  $listNames = array('max','bob', 'alice');
  foreach($listNames as $name){
    $user = new User();
    $user->setUsername($name);
    $user->setPassword($name);
    $user->setSalt('');
    $user->setRoles(array('ROLE_USER'));
    $manager->persist($user);
  }

  $manager->flush();
  } 
}

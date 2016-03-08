<?php

namespace MB\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MB\PlatformBundle\Entity\Skill;

class LoadSkillData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
      $names = array('PHP', 'Symfony2', 'C++', 'Java', 'Photoshop', 'Blender', 'Bloc-note');

      foreach($names as $name){
        $skill = new Skill();
        $skill->setName($name);
        $manager->persist($skill);
      }

        $manager->flush();
    
}


}

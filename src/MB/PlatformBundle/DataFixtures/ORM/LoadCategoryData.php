<?php

namespace MB\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MB\PlatformBundle\Entity\Category;

class LoadCategoryData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
      $names = array(
        'Développement web',
        'Développement mobile',
        'Graphisme',
        'Intégration',
        'Réseau'
      );

      foreach($names as $name){
        $category = new Category();
        $category->setName($name);
        $manager->persist($category);
      }

        $manager->flush();
    
}


}

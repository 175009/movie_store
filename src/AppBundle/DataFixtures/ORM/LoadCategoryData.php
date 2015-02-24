<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use AppBundle\Entity\MovieCategory;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $c1 = new MovieCategory();
        $c1->setName("Sci-Fi");
        $manager->persist($c1);
        
        $c2 = new MovieCategory();
        $c2->setName("Fantasy");
        $manager->persist($c2);
        
        $c3 = new MovieCategory();
        $c3->setName("Przygodowy");
        $manager->persist($c3);
        
        $c4 = new MovieCategory();
        $c4->setName("Familijny");
        $manager->persist($c4);
        
        $c5 = new MovieCategory();
        $c5->setName("Animacja");
        $manager->persist($c5);
        
        $c6 = new MovieCategory();
        $c6->setName("Komedia");
        $manager->persist($c6);
        
        $manager->flush();
        
        $this->addReference('category-sci-fi', $c1);
        $this->addReference('category-fantasy', $c2);
        $this->addReference('category-przygodowy', $c3);
        $this->addReference('category-familijny', $c4);
        $this->addReference('category-animacja', $c5);
        $this->addReference('category-komedia', $c6);
    }
    
    public function getOrder()
    {
        return 2;
    }
}
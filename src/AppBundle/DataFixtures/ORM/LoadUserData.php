<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;
    
    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    public function load(ObjectManager $manager)
    {
        // user
        $username = "student";
        $password = "student";
        $email = "student@movieshop.pl";
        $inactive = false;
        $superadmin = false;
        
        $manipulator = $this->container->get('fos_user.util.user_manipulator');
        $manipulator->create($username, $password, $email, !$inactive, $superadmin);
        
        $manipulator->addRole($username, 'ROLE_USER');
        
        // admin
        $username = "admin";
        $password = "admin";
        $email = "admin@movieshop.pl";
        $inactive = false;
        $superadmin = false;
        
        $manipulator = $this->container->get('fos_user.util.user_manipulator');
        $manipulator->create($username, $password, $email, !$inactive, $superadmin);
        
        $manipulator->addRole($username, 'ROLE_ADMIN');
    }
    
    public function getOrder()
    {
        return 1;
    }
}
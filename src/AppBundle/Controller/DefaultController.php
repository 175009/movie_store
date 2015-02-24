<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template
     */
    public function indexAction()
    {
        // lista wszystkich filmów
        $movies = $this->getDoctrine()
            ->getRepository('AppBundle:Movie')
            ->findAll();
        
        $categories = $this->getDoctrine()
            ->getRepository('AppBundle:MovieCategory')
            ->findAll();
        
        // lista popularnych filmów
        $popular = $this->getDoctrine()
            ->getRepository('AppBundle:Movie')
            ->findBy(array(), array('nbOrders' => 'desc'), 5);
        
        // lista najczęściej recenzowanych filmów
        $frequentlyReviewed = $this->getDoctrine()
            ->getRepository('AppBundle:Movie')
            ->findBy(array(), array('nbReviews' => 'desc'), 5);
        
        return array(
            'movies'                => $movies,
            'categories'            => $categories,
            'popular'               => $popular,
            'frequentlyReviewed'    => $frequentlyReviewed
        );
    }
    
    /**
     * @Route("/popular", name="popular")
     */
    public function poopularAction()
    {
        // lista popularnych filmów
        $movies = $this->getDoctrine()
            ->getRepository('AppBundle:Movie')
            ->findBy(array(), array('nbOrders' => 'desc'));
    
        return $this->render('AppBundle:Default:movies.html.twig', array(
            'title'     => "Lista popularnych filmów",
            'movies'    => $movies
        ));
    }
    
    /**
     * @Route("/mostly-reviewed", name="reviewed")
     */
    public function reviewedAction()
    {
        // lista najczęściej recenzowanych filmów
        $movies = $this->getDoctrine()
            ->getRepository('AppBundle:Movie')
            ->findBy(array(), array('nbReviews' => 'desc'));
    
        return $this->render('AppBundle:Default:movies.html.twig', array(
            'title'     => "Lista najczęściej recenzowanych filmów",
            'movies'    => $movies
        ));
    }
    
    /**
     * @Route("/seach", name="search")
     * @Template()
     */
    public function searchAction()
    {
        if (!$query = $this->getRequest()->query->get('q', false)) {
            return $this->redirectToRoute('homepage');
        }
        
        $movies = $this->getDoctrine()
            ->getRepository('AppBundle:Movie')
            ->createQueryBuilder('m')
            ->innerJoin('m.categories', 'c')
            ->where('m.name LIKE :name OR m.description LIKE :description OR m.actores LIKE :actores')
            ->setParameters(array(
                'name'  => "%".$query."%",
                'description'  => "%".$query."%",
                'actores'  => "%".$query."%",
            ))
            ->getQuery()
            ->getResult();
            
        return array(
            'movies'    => $movies
        );
    }
    
}

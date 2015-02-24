<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * @Route("/order")
 */
class OrderController extends Controller
{
    /**
     * @Secure(roles="ROLE_USER")
     * 
     * @Route("/list", name="order_list")
     * @Template()
     */
    public function listAction()
    {
        $orders = $this->getDoctrine()->getRepository('AppBundle:MovieOrder')
            ->findBy(array('user' => $this->getUser()));
        
        return array(
            'orders'    => $orders
        );
    }
    
    /**
     * @Secure(roles="ROLE_USER")
     *
     * @Route("/movies", name="order_movies")
     * @Template()
     */
    public function moviesAction()
    {
        // zamówione filmy
        $movies = $this->getUser()->getOrderedMovies(); 
    
        return array(
            'movies'    => $movies
        );
    }
    
    /**
     * @Secure(roles="ROLE_USER")
     *
     * @Route("/{id}/show", name="order_show")
     * @Template()
     */
    public function showAction($id)
    {
        $order = $this->getDoctrine()->getRepository('AppBundle:MovieOrder')
            ->find($id);
        
        if (!$order) {
            throw $this->createNotFoundException("Nie znaleziono zamówienia");
        }
    
        return array(
            'order'    => $order
        );
    }
    
    /**
     * @Secure(roles="ROLE_USER")
     *
     * @Route("/{id}/confirmation", name="order_confirm")
     * @Template()
     */
    public function confirmAction($id)
    {
        $order = $this->getDoctrine()->getRepository('AppBundle:MovieOrder')
            ->find($id);
    
        if (!$order) {
            throw $this->createNotFoundException("Nie znaleziono zamówienia");
        }
    
        return array(
            'order'    => $order
        );
    }
}

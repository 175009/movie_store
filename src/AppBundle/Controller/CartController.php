<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/cart")
 */
class CartController extends Controller
{
    /**
     * @Route("/", name="cart")
     * @Template()
     */
    public function listAction()
    {
        return array(
            'cart'  => $this->get('cart')
        );
    }
    
    /**
     * @Route("/{slug}/remove", name="cart_remove")
     * @Template()
     */
    public function cartAction($slug)
    {
        $movie = $this->getDoctrine()->getRepository('AppBundle:Movie')
            ->findOneBy(array('slug' => $slug));
        
        if (!$movie) {
            throw $this->createNotFoundException("Film nie został odnaleziony.");
        }
        
        $this->get('cart')
            ->remove($movie);
        
        $this->addFlash('notice', 'Film został pomyślnie usunięty z koszyka.');
        
        return $this->redirectToRoute('cart');
    }
}

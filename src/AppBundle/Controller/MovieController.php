<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use AppBundle\Form\MovieReviewType;
use AppBundle\Entity\MovieReview;
use AppBundle\Entity\MovieOrder;

/**
 * @Route("/movie")
 */
class MovieController extends Controller
{
    /**
     * @Secure(roles="ROLE_USER")
     *
     * @Route("/order", name="movie_order")
     * @Template()
     */
    public function orderAction()
    {
        $cart = $this->get('cart');
    
        $movies = $cart->getMovies();
        if (count($movies) < 1) {
            // film jest już w koszyku
            $this->addFlash('error', "Nie możesz złożyć zamówienia - koszyk jest pusty.");
        }
    
        $user = $this->getUser();
    
        $order = new MovieOrder();
        $order->setUser($user);
        foreach ($movies as $movie) {
            $order->addMovie($movie);
            
            // aktualizuj liczbę zamówień dla filmu
            $movie->setNbOrders($movie->getNbOrders() + 1);
        }
    
        $em = $this->getDoctrine()->getManager();
    
        $em->persist($order);
        $em->flush();
    
        // wyczyść koszyk
        $cart->clear();
    
        // wyślij miala ze szczegółami płatności
        $mailer = $this->get('mailer');
        
        $message = $mailer->createMessage()
            ->setSubject('MovieShop.pl - potwierdzenie zamówienia')
            ->setFrom('no-reply@movieshop.pl')
            ->setTo($user->getEmail())
            ->setBody($this->renderView(
                'AppBundle:Movie:emailConfirmation.html.twig',
                array('user' => $user)
        ),'text/html');
        
        $mailer->send($message);
        
        $this->addFlash('notice', "Zamówienie zostało zapisane. Na podany adres e-mail zostały wysłane informacje na temat płatności.");
    
        return $this->redirectToRoute('order_confirm', array('id' => $order->getId()));
    }
    
    /**
     * @Route("/{slug}", name="movie_show")
     * @Template()
     */
    public function showAction($slug)
    {
        $movie = $this->getDoctrine()->getRepository('AppBundle:Movie')
            ->findOneBy(array('slug' => $slug));
        
        if (!$movie) {
            throw $this->createNotFoundException("Film nie został odnaleziony.");
        }
        
        return array(
            'movie' => $movie
        );
    }

    /**
     * @Secure(roles="ROLE_USER")
     * 
     * @Route("/{slug}/rent", name="movie_rent")
     * @Template()
     */
    public function rentAction($slug)
    {
        $movie = $this->getDoctrine()->getRepository('AppBundle:Movie')
            ->findOneBy(array('slug' => $slug));
        
        if (!$movie) {
            throw $this->createNotFoundException("Film nie został odnaleziony.");
        }
        
        $cart = $this->get('cart');
        
        if ($cart->has($movie)) {
            // film jest już w koszyku
            $this->addFlash('error', "Ten film znajduje się już w Twoim koszyku.");
        } else {
            $cart->add($movie);
            $this->addFlash('notice', "Film został pomyślnie dodany do koszyka.");
        }
        
        return $this->redirect($this->getRequest()->headers->get('referer'));
    }
    
    /**
     * @Route("/{slug}/watch", name="movie_watch")
     * @Template()
     */
    public function watchAction($slug)
    {
        $movie = $this->getDoctrine()->getRepository('AppBundle:Movie')
            ->findOneBy(array('slug' => $slug));

        if (!$movie) {
            throw $this->createNotFoundException("Film nie został odnaleziony.");
        }
        
        if (!$this->getUser()->isOrderedMovie($movie)) {
            $this->addFlash('error', 'Ten film nie został jeszcze opłacony!');
            return $this->redirectToRoute('homepage');
        }
        
        $this->addFlash('notice', "Odtwarzanie filmu");
    
        return $this->redirect($this->getRequest()->headers->get('referer'));
    }
    
    /**
     * @Secure(roles="ROLE_USER")
     * 
     * @Route("/{slug}/add-review", name="movie_add_review")
     * @Template()
     */
    public function addReviewAction($slug)
    {
        $movie = $this->getDoctrine()->getRepository('AppBundle:Movie')
            ->findOneBy(array('slug' => $slug));
        
        if (!$movie) {
            throw $this->createNotFoundException("Film nie został odnaleziony.");
        }
        
        $review = new MovieReview();
        $review->setMovie($movie);
        $review->setUser($this->getUser());
        
        $form = $this->createForm(new MovieReviewType(), $review);
        
        $form->handleRequest($this->getRequest());
        
        if ($form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            
            // zwiększa liczbę opinie
            $movie->setNbReviews($movie->getNbReviews() + 1);
            
            $em->flush();
            
            $this->addFlash('notice', "Recenzja została pomyślnie dodana i oczekuje na moderację.");
            return $this->redirectToRoute('movie_show', array('slug' => $movie->getSlug()));
        }
        
        return array(
            'movie' => $movie,
            'form'  => $form->createView()
        );
    }
    
}

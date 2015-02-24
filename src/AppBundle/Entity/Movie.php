<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Movie
 *
 * @ORM\Table(name="movie")
 * @ORM\Entity
 */
class Movie
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="cover", type="string", length=255)
     */
    private $cover;

    /**
     * @var string
     *
     * @ORM\Column(name="actores", type="text", nullable=true)
     */
    private $actores;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal")
     */
    private $price;

    /**
     * @var integer
     * 
     * @ORM\Column(name="nb_orders", type="integer")
     */
    private $nbOrders = 0;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="nb_reviews", type="integer")
     */
    private $nbReviews = 0;
    
    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;
    
    /**
     * @var ArrayCollection
     *
     * * @ORM\OneToMany(targetEntity="MovieReview", mappedBy="movie")
     */
    protected $reviews;

    /**
     * @var ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="MovieCategory", inversedBy="movies")
     */
    protected $categories;
    
    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="MovieOrder", mappedBy="movies")
     */
    protected $orders;

    public function __construct()
    {
        $this->reviews = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function __toString()
    {
        return $this->getName();
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Movie
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Movie
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Movie
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set cover
     *
     * @param string $cover
     * @return Movie
     */
    public function setCover($cover)
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * Get cover
     *
     * @return string 
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * Set actores
     *
     * @param string $actores
     * @return Movie
     */
    public function setActores($actores)
    {
        $this->actores = $actores;

        return $this;
    }

    /**
     * Get actores
     *
     * @return string 
     */
    public function getActores()
    {
        return $this->actores;
    }

    /**
     * Set price
     *
     * @param string $price
     * @return Movie
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Movie
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Add reviews
     *
     * @param \AppBundle\Entity\MovieReview $reviews
     * @return Movie
     */
    public function addReview(\AppBundle\Entity\MovieReview $reviews)
    {
        $this->reviews[] = $reviews;

        return $this;
    }

    /**
     * Remove reviews
     *
     * @param \AppBundle\Entity\MovieReview $reviews
     */
    public function removeReview(\AppBundle\Entity\MovieReview $reviews)
    {
        $this->reviews->removeElement($reviews);
    }

    /**
     * Get reviews
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    
    public function getApprovedReviews()
    {
        $reviews = array();
        foreach ($this->getReviews() as $review) {
            if ($review->getIsModerated()) {
                $reviews[] = $review;
            }
        }
        
        return $reviews;
    }
    
    /**
     * Add categories
     *
     * @param \AppBundle\Entity\MovieCategory $categories
     * @return Movie
     */
    public function addCategory(\AppBundle\Entity\MovieCategory $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \AppBundle\Entity\MovieCategory $categories
     */
    public function removeCategory(\AppBundle\Entity\MovieCategory $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add orders
     *
     * @param \AppBundle\Entity\MovieOrder $orders
     * @return Movie
     */
    public function addOrder(\AppBundle\Entity\MovieOrder $orders)
    {
        $this->orders[] = $orders;

        return $this;
    }

    /**
     * Remove orders
     *
     * @param \AppBundle\Entity\MovieOrder $orders
     */
    public function removeOrder(\AppBundle\Entity\MovieOrder $orders)
    {
        $this->orders->removeElement($orders);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Set nbOrders
     *
     * @param integer $nbOrders
     * @return Movie
     */
    public function setNbOrders($nbOrders)
    {
        $this->nbOrders = $nbOrders;

        return $this;
    }

    /**
     * Get nbOrders
     *
     * @return integer 
     */
    public function getNbOrders()
    {
        return $this->nbOrders;
    }

    /**
     * Set nbReviews
     *
     * @param integer $nbReviews
     * @return Movie
     */
    public function setNbReviews($nbReviews)
    {
        $this->nbReviews = $nbReviews;

        return $this;
    }

    /**
     * Get nbReviews
     *
     * @return integer 
     */
    public function getNbReviews()
    {
        return $this->nbReviews;
    }
}

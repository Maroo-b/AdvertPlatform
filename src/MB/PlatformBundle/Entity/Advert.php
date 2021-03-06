<?php

namespace MB\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use MB\PlatformBundle\Validator\Antiflood;

/**
 * Advert
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="MB\PlatformBundle\Entity\AdvertRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields="title", message="Une anonce existe déja avec ce titre")
 */
class Advert
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\Length(min=10)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255)
     * @Assert\Length(min=2)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank()
     * @Antiflood()
     */
    private $content;

    /**
     * @ORM\Column(type="boolean")
     */
    private $published = true;

    /**
     * @ORM\OneToOne(targetEntity="MB\PlatformBundle\Entity\Image", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity="MB\PlatformBundle\Entity\Category", cascade={"persist"})
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="MB\PlatformBundle\Entity\Application", mappedBy="advert")
     */
    private $applications;


    /**
     * @ORM\OneToMany(targetEntity="MB\PlatformBundle\Entity\AdvertSkill", mappedBy="advert")
     */
    private $advertSkills;

    /**
     * @ORM\Column( name="updated_at", type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\Column(name="nbr_applications", type="integer")
     */
    private $nbrApplications = 0;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    public function __construct()
    {
      $this->date = new \Datetime();
      $this->categories   = new ArrayCollection();
      $this->applications = new ArrayCollection();
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
     * Set date
     *
     * @param \DateTime $date
     * @return Advert
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Advert
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return Advert
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Advert
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set published
     *
     * @param boolean $published
     * @return Advert
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean 
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set image
     *
     * @param \MB\PlatformBundle\Entity\Image $image
     * @return Advert
     */
    public function setImage(\MB\PlatformBundle\Entity\Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \MB\PlatformBundle\Entity\Image 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add categories
     *
     * @param \MB\PlatformBundle\Entity\Category $categories
     * @return Advert
     */
    public function addCategory(\MB\PlatformBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \MB\PlatformBundle\Entity\Category $categories
     */
    public function removeCategory(\MB\PlatformBundle\Entity\Category $categories)
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
     * Add applications
     *
     * @param \MB\PlatformBundle\Entity\Application $applications
     * @return Advert
     */
    public function addApplication(\MB\PlatformBundle\Entity\Application $application)
    {
        $this->applications[] = $application;
        $application->setAdvert($this);

        return $this;
    }

    /**
     * Remove applications
     *
     * @param \MB\PlatformBundle\Entity\Application $applications
     */
    public function removeApplication(\MB\PlatformBundle\Entity\Application $application)
    {
        $this->applications->removeElement($application);
    }

    /**
     * Get applications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getApplications()
    {
        return $this->applications;
    }

    /**
     * Set updateAt
     *
     * @param \DateTime $updateAt
     * @return Advert
     */
    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * Get updateAt
     *
     * @return \DateTime 
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
      $this->setUpdateAt(new \Datetime());
    }

    public function increaseApplication()
    {
      $this->nbApplications++;
    }

    public function decreaseApplication()
    {
      $this->nbApplications--;
    }

    /**
     * Set nbrApplications
     *
     * @param integer $nbrApplications
     * @return Advert
     */
    public function setNbrApplications($nbrApplications)
    {
        $this->nbrApplications = $nbrApplications;

        return $this;
    }

    /**
     * Get nbrApplications
     *
     * @return integer 
     */
    public function getNbrApplications()
    {
        return $this->nbrApplications;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Advert
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
     * @Assert\True()
     */
    public function isTitle()
    {
      return false;
    }

    /**
     * @Assert\Callback
     */
    public function isContentValid(ExecutionContextInterface $context)
    {
      $forbiddenWords = array('echec', 'abondon');

      if(preg_match('#'.implode('|', $forbiddenWords).'#', $this->getContent())){
        $context
          ->buildViolation('Contenu invalide, contient un mot interdit.')
          ->atPath('content')
          ->addViolation();
      }
    }
}

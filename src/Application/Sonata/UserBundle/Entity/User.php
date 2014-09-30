<?php

/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Entity;

use Sonata\UserBundle\Entity\BaseUser as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * This file has been generated by the Sonata EasyExtends bundle ( http://sonata-project.org/bundles/easy-extends )
 *
 * References :
 *   working with object : http://www.doctrine-project.org/projects/orm/2.0/docs/reference/working-with-objects/en
 *
 * @author <yourname> <youremail>
 */
class User extends BaseUser
{
    /**
     * @var string
     */
    public $newPassword;
    
    /**
     * @var integer $id
     */
    protected $id;
    
    protected $publicName;

    /**
     *
     * @var string 
     */
    protected $resettingCode;

    /**
     * @var Table\RestaurantBundle\Entity\Restaurant[] $restaurants
     * 
     * @ORM\OneToMany(targetEntity="Table\RestaurantBundle\Entity\Restaurant", mappedBy="user", cascade={"persist", "refresh"})
     */
    protected $restaurants;

    const ROLE_SUPER_ADMIN = "ROLE_SUPER_ADMIN";
    /**
     * Get id
     *
     * @return integer $id
     */
    
    public function __construct()
    {
        parent::__construct();

        $this->restaurants = new ArrayCollection();
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getResettingCode()
    {
        return $this->resettingCode;
    }

    public function setResettingCode($resettingCode)
    {
        $this->resettingCode = $resettingCode;
    }

        /**
     * Get public name
     * 
     * @return string
     */
    public function getPublicName()
    {
        if (is_null($this->publicName)) {
            if (is_null($this->getFirstname())) {
                $publicName = $this->getUsername();
            } else {
                $publicName = $this->getFirstname();
            }
            $this->setPublicName($publicName);
        }
        return $this->publicName;
    }

    /**
     * Set public name
     * 
     * @param string $publicName
     */
    public function setPublicName($publicName)
    {
        $this->publicName = $publicName;
    }

    
}
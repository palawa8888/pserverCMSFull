<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Availablecountrys
 *
 * @ORM\Table(name="availableCountrys", indexes={@ORM\Index(name="fk_availableCountrys_users1_idx", columns={"users_usrId"})})
 * @ORM\Entity
 */
class Availablecountrys {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="acId", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $acid;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cntry", type="string", length=45, nullable=false)
	 */
	private $cntry;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="active", type="string", nullable=false)
	 */
	private $active;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="created", type="datetime", nullable=false)
	 */
	private $created;

	/**
	 * @var \Application\Entity\Users
	 *
	 * @ORM\ManyToOne(targetEntity="Application\Entity\Users")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="users_usrId", referencedColumnName="usrId")
	 * })
	 */
	private $usersUsrid;

	public function __construct( ) {
		$this->created = new \DateTime();
	}

	/**
	 * Get acid
	 *
	 * @return integer
	 */
	public function getAcid() {
		return $this->acid;
	}

	/**
	 * Set cntry
	 *
	 * @param string $cntry
	 *
	 * @return Availablecountrys
	 */
	public function setCntry( $cntry ) {
		$this->cntry = $cntry;

		return $this;
	}

	/**
	 * Get cntry
	 *
	 * @return string
	 */
	public function getCntry() {
		return $this->cntry;
	}

	/**
	 * Set active
	 *
	 * @param string $active
	 *
	 * @return Availablecountrys
	 */
	public function setActive( $active ) {
		$this->active = $active;

		return $this;
	}

	/**
	 * Get active
	 *
	 * @return string
	 */
	public function getActive() {
		return $this->active;
	}

	/**
	 * Set created
	 *
	 * @param \DateTime $created
	 *
	 * @return Availablecountrys
	 */
	public function setCreated( $created ) {
		$this->created = $created;

		return $this;
	}

	/**
	 * Get created
	 *
	 * @return \DateTime
	 */
	public function getCreated() {
		return $this->created;
	}

	/**
	 * Set usersUsrid
	 *
	 * @param \Application\Entity\Users $usersUsrid
	 *
	 * @return Availablecountrys
	 */
	public function setUsersUsrid( \Application\Entity\Users $usersUsrid = null ) {
		$this->usersUsrid = $usersUsrid;

		return $this;
	}

	/**
	 * Get usersUsrid
	 *
	 * @return \Application\Entity\Users
	 */
	public function getUsersUsrid() {
		return $this->usersUsrid;
	}
}

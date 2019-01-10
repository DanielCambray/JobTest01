<?php
/**
 * Created by PhpStorm.
 * User: daniel.zuwala
 * Date: 04/12/2018
 * Time: 11:29
 */

namespace App\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Job;
use App\Entity\Gender;

/**
 * @ORM\Entity()
 * @ORM\Table(name="account", uniqueConstraints={@ORM\UniqueConstraint(name="email", columns={"email"})})
 */
class Account
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=128)
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", length=128)
     */
    protected $lastName;

    /**
     * @ORM\Embedded(class="Gender")
     */
    protected $gender;

    /**
     * @ORM\Column(type="string", length=128)
     */
    protected $email;

    /**
     * @ORM\Column(type="date")
     */
    protected $birthday;

    /**
     * @ORM\ManyToOne(targetEntity="Job")
     */
    protected $job;

    /**
     * @ORM\ManyToOne(targetEntity="Country")
     */
    protected $country;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param mixed $birthday
     */
    public function setBirthday($birthday): void
    {
        $this->birthday = $birthday;
    }

    /**
     * @return mixed
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * @param mixed $job
     */
    public function setJob($job): void
    {
        $this->job = $job;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country): void
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender): void
    {
        $this->gender = $gender;
    }
}

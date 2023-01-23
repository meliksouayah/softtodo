<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="test_User")
 *
 */
#[UniqueEntity(fields: ['username'], message: "Il existe déjà un compte avec ce nom d'utilisateur")]
class User implements UserInterface, TwoFactorInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;




    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $role;
    /**
     * @ORM\Column(name="googleAuthenticatorSecret", type="string", nullable=true)
     */
    private $googleAuthenticatorSecret;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facebookID;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $googleID;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }





    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }





    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
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
     * @return \DateTime
     */
    public function getDateInsci(): \DateTime
    {
        return $this->dateInsci;
    }

    /**
     * @param \DateTime $dateInsci
     */
    public function setDateInsci(\DateTime $dateInsci): void
    {
        $this->dateInsci = $dateInsci;
    }



    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role): void
    {
        $this->role = $role;
    }



    public function getRoles(){

        $roles = [$this->role];
        return array_unique($roles);
    }
    public function getSalt(){}
    public function eraseCredentials(){}



    /**
     * @return mixed
     */
    public function getFacebookID()
    {
        return $this->facebookID;
    }

    /**
     * @param mixed $facebookID
     */
    public function setFacebookID($facebookID): void
    {
        $this->facebookID = $facebookID;
    }

    /**
     * @return mixed
     */
    public function getGoogleID()
    {
        return $this->googleID;
    }

    /**
     * @param mixed $googleID
     */
    public function setGoogleID($googleID): void
    {
        $this->googleID = $googleID;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function isGoogleAuthenticatorEnabled(): bool
    {
        return null !== $this->googleAuthenticatorSecret;
    }

    public function getGoogleAuthenticatorUsername(): string
    {
        return $this->username;
    }

    public function getGoogleAuthenticatorSecret(): ?string
    {
        return $this->googleAuthenticatorSecret;
    }

    public function setGoogleAuthenticatorSecret(?string $googleAuthenticatorSecret): void
    {
        $this->googleAuthenticatorSecret = $googleAuthenticatorSecret;
    }



}
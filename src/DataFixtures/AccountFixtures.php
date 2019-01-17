<?php
/**
 * Created by PhpStorm.
 * User: daniel.zuwala
 * Date: 04/12/2018
 * Time: 11:33
 */

namespace App\DataFixtures;

use App\Entity\Account;
use App\Entity\Gender;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class AccountFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager) : void
    {
        $maleGender = new Gender;
        $maleGender->setType('M');

        $femaleGender = new Gender;
        $femaleGender->setType('F');

        $account = new Account();
        $account->setFirstName('Daniel');
        $account->setLastName('Zuwala');
        $account->setBirthday(new \DateTime('1979-12-10'));
        $account->setEmail('daniel.zuwala@gmail.com');
        $account->setCountry('FR');
        $account->setGender($maleGender);
        $manager->persist($account);

        $account = new Account();
        $account->setFirstName('Naomi');
        $account->setLastName('Zuwala');
        $account->setGender($femaleGender);
        $account->setBirthday(new \DateTime('1980-11-03'));
        $account->setEmail('test@email.org');
        $account->setCountry('FR');

        $manager->persist($account);

        $manager->flush();

        $countryCodes = array('FR', 'ES', 'DE', 'GB', 'IT');
        $arrayGender = array($maleGender, $femaleGender);
        $arrayName = array('Jane', 'John');
        for ($i=0 ; $i<200 ; $i++) {
            $key = array_rand($arrayGender);
            $email = $this->generateRandomString(16) . '@' . $this->generateRandomString(6) . '.' .  $this->generateRandomString(2);

            $account = new Account();
            $account->setFirstName($arrayName[$key]);
            $account->setLastName('Doe');
            $account->setGender($arrayGender[$key]);
            $account->setBirthday(new \DateTime('1956-03-21'));
            $account->setEmail($email);
            $countryCode = $countryCodes[array_rand($countryCodes)];
            $account->setCountry($countryCode);

            $manager->persist($account);
        }

        $manager->flush();
    }

    /**
     * Generate a random string from a given length
     *
     * @param int $length
     *
     * @return string
     */
    protected function generateRandomString($length = 10) : string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            JobFixtures::class,
            CountryFixtures::class
        ];
    }
}

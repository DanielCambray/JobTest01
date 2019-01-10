<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Country;

class CountryFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager) : void
    {
        $frCountries = $this->loadCountries('fr');
        $enCountries = $this->loadCountries('en');

        foreach ($frCountries as $code => $name) {
            $country = new Country();
            $country->setCode($code);
            $country->setTranslatableLocale('fr');
            $country->setName($name);
            $manager->persist($country);
            $this->addReference('country-' . $code, $country);

            $country->setTranslatableLocale('en');
            $country->setName($enCountries[$code]);
            $manager->persist($country);
        }

        $manager->flush();
    }

    /**
     * Return an array of countries in the $locale language
     *
     * @param $locale
     * @return mixed
     */
    protected function loadCountries($locale)
    {
        $file = file_get_contents('data/country.' . $locale . '.json');
        return json_decode($file, 1);
    }
}

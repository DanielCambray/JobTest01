<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Job;

class JobFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager) : void
    {
        // We could load a prefetch list of jobs as we have done for countries
        // As the one available at https://www.bls.gov/soc/2018/home.htm

        $job = new Job();
        $job->setTranslatableLocale('en');
        $job->setCode("11-1011");
        $job->setName("Chief Executives");
        $manager->persist($job);

        $job = new Job();
        $job->setTranslatableLocale('en');
        $job->setCode("11-1021");
        $job->setName("General and Operations Managers");
        $manager->persist($job);

        $job = new Job();
        $job->setTranslatableLocale('en');
        $job->setCode("11-1031");
        $job->setName("Legislators");
        $manager->persist($job);

        $job = new Job();
        $job->setTranslatableLocale('en');
        $job->setCode("11-2011");
        $job->setName("Advertising and Promotions Managers");

        $manager->persist($job);

        $job = new Job();
        $job->setTranslatableLocale('en');
        $job->setCode("11-2021");
        $job->setName("Marketing Managers");

        $manager->persist($job);

        $job = new Job();
        $job->setTranslatableLocale('en');
        $job->setCode("11-2022");
        $job->setName("Sales Managers");
        $manager->persist($job);

        $manager->flush();
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: daniel.zuwala
 * Date: 17/01/2019
 * Time: 10:29
 */


namespace App\Twig;
use Symfony\Component\Intl\Intl;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CountryExtension extends AbstractExtension
{
    public function getFilters()
    {
        return array(
            new TwigFilter("country", array($this, "countryFilter")),
        );
    }
    public function countryFilter($countryCode, $locale = "en")
    {
        \Locale::setDefault($locale);
        $countryName = "";
        if ($countryCode) {
            $countryName = Intl::getRegionBundle()->getCountryName(strtoupper($countryCode));
        }

        return $countryName ?: $countryCode;
    }
    public function getName()
    {
        return "country_extension";
    }
}
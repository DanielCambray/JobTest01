<?php
/**
 * Created by PhpStorm.
 * User: daniel.zuwala
 * Date: 09/01/2019
 * Time: 10:09
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gender
 *
 * @ORM\Embeddable
 */
class Gender
{
    const FEMALE = 'F';
    const MALE = 'M';

    /**
     * @var string
     * @ORM\Column(name="gender", type="string", nullable=true)
     */
    private $gender;

    public function setType(string $gender)
    {
        $class = new \ReflectionClass($this);
        if (!in_array($gender, $class->getConstants())) {
            throw new \InvalidArgumentException();
        }
        $this->gender = $gender;
    }

    public function getType()
    {
        return $this->gender;
    }
}

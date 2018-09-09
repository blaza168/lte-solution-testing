<?php
/**
 * Created by: Jan Blažek
 * Date: 9/09/2018
 * Time: 9:33 AM
 * Email: jan.blazek10@gmail.com
 */

namespace App\Entity;

/**
 * Class ProductEntity
 * @package App\Entity
 */
class ProductEntity
{
    /** @var string */
    private $link;
    /** @var string */
    private $name;
    /** @var string */
    private $code;
    /** @var string */
    private $withDph;
    /** @var string */
    private $withoutDph;

    /**
     * ProductEntity constructor.
     * @param string $link
     * @param string $name
     * @param string $code
     * @param string $withDph
     * @param string $withoutDph
     */
    public function __construct($link, $name, $code, $withDph, $withoutDph)
    {
        $this->link = $link;
        $this->name = $name;
        $this->code = $code;
        $this->withDph = $withDph;
        $this->withoutDph = $withoutDph;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getWithDph()
    {
        return $this->withDph;
    }

    /**
     * @return string
     */
    public function getWithoutDph()
    {
        return $this->withoutDph;
    }
}
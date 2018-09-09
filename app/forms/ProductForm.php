<?php
/**
 * Created by: Jan Blažek
 * Date: 9/09/2018
 * Time: 10:04 AM
 * Email: jan.blazek10@gmail.com
 */

namespace App\Form;

use App\Model\Crawler;
use Nette;
use Nette\Application\UI\Form;

/**
 * Class ProductForm
 * @package App\Form
 */
class ProductForm extends Form
{
    /**
     * ProductForm constructor.
     * @param Nette\ComponentModel\IContainer|null $parent
     * @param null $name
     */
    public function __construct(Nette\ComponentModel\IContainer $parent = null, $name = null)
    {
        $this->addSelect('product', 'Produkty',Crawler::getProductTypes())
            ->setPrompt('Vyberte produkt')
            ->setRequired('Vyberte prosím produkt');

        $this->addSelect('brand', 'Značka',Crawler::getBrands())
            ->setPrompt('Vyberte značku')
            ->setRequired('Vyberte prosím značku');

        $this->addSubmit('submit', 'Odeslat');

        parent::__construct($parent, $name);
    }
}
<?php

namespace App\Presenters;

use App\Form\ProductForm;
use App\Service\ProductService;

/**
 * Class HomepagePresenter
 * @package App\Presenters
 */
class HomepagePresenter extends BasePresenter
{
    /** @var ProductService */
    private $productService;

    /**
     * HomepagePresenter constructor.
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }


    public function renderDefault()
	{
        if ($this['productForm']->isSubmitted() && $this['productForm']->isValid()) {
            $values = $this['productForm']->getValues();

            $this->template->products = $this->productService->getProducts($values->product, $values->brand);
        }
	}

    /**
     * @param string $name
     * @return ProductForm|\Nette\ComponentModel\IComponent|null
     */
	public function createComponent($name)
    {
        if ($name === 'productForm') {
            $form = new ProductForm();
            return $form;
        }
        return parent::createComponent($name);
    }
}

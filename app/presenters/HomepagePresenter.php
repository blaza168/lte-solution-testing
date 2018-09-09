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


    /**
     * TODO: parameter validation
     * @param $product
     * @param $brand
     * @param $page
     */
    public function renderDefault($product, $brand, $page)
	{
	    if ($product) {
            $this->template->products = $this->productService->getProducts($product, $brand, $page);
	        $this->template->page = $page;
            $this->template->brand = $brand;
            $this->template->productName = $product;

            $this['productForm']->setDefaults([
               'product' => $product,
               'brand' => $brand,
            ]);
        }

        if ($this['productForm']->isSubmitted() && $this['productForm']->isValid()) {
            $values = $this['productForm']->getValues();

            $this->template->products = $this->productService->getProducts($values->product, $values->brand);
            $this->template->brand = $values->brand;
            $this->template->page = 1;
            $this->template->productName = $values->product;
        }
	}

    /**
     * Exports data to .csv
     * @param $product
     * @param $brand
     * @throws \Nette\Application\AbortException
     */
	public function handleExport($product, $brand)
    {
            $products = $this->productService->productsToExport($product, $brand);

            $this->getHttpResponse()->setHeader('Content-Type', 'text/csv; charset=utf-8');
            $this->getHttpResponse()->setHeader('Content-Disposition', "attachment; filename=data.csv");


            $out = fopen('php://output', 'w');
            foreach ($products as $product) {
                fputcsv($out, $product);
            }
            fclose($out);

            $this->terminate();
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

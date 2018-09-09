<?php
/**
 * Created by: Jan Blažek
 * Date: 9/09/2018
 * Time: 9:25 AM
 * Email: jan.blazek10@gmail.com
 */

namespace App\Service;

use App\Entity\ProductEntity;
use App\Model\Crawler;

/**
 * Class ProductService
 * @package App\Service
 */
class ProductService
{
    /** @var Crawler */
    private $crawler;

    /**
     * ProductService constructor.
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @param $type string
     * @param $company string
     * @return ProductEntity[]
     */
    public function getProducts($type, $company)
    {
        $data = $this->crawler->getData($type, $company);
        return $this->mapToEntities($data);
    }

    /**
     * @param array $data
     * @return ProductEntity[]
     */
    private function mapToEntities(array $data)
    {
        /** @var ProductEntity[] $result */
        $result = [];
        $size = count($data['links']);

        for ($i = 0; $i < $size; $i++) {
            $product = new ProductEntity(
                $data['links'][$i],
                $data['names'][$i],
                $data['codes'][$i],
                $data['bezdph'][$i],
                $data['sdph'][$i]
            );

            $result[] = $product;
        }

        return $result;
    }
}
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
     * @param int $page
     * @return ProductEntity[]
     */
    public function getProducts($type, $company, $page = 1)
    {
        $data = $this->crawler->getData($type, $company);
        return $this->mapToEntities($data, $page);
    }

    /**
     * @param array $data
     * @param int $page
     * @return ProductEntity[]
     */
    private function mapToEntities(array $data, $page)
    {
        $page--;
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

        if ($size > ($page * 20 + 20)) {
            for ($i = 0; $i < $page * 20; $i++) {
                unset($result[$i]);
            }
            for ($i = $page * 20 + 20; $i < $size; $i++) {
                unset($result[$i]);
            }
        } else if ($size > $page * 20) {
            for ($i = 0; $i < $page * 20; $i++) {
                unset($result[$i]);
            }
        } else {
            return [];
        }

        return $result;
    }
}
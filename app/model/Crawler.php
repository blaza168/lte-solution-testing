<?php
/**
 * Created by: Jan Blažek
 * Date: 9/09/2018
 * Time: 9:10 AM
 * Email: jan.blazek10@gmail.com
 */

namespace App\Model;

/**
 * Class Crawler
 * @package App\Model
 */
class Crawler
{
    // TODO: move this to separate class or use crawler library to get this
    public static $moznostiURL = [
        "vstrikcerp" => [
            "bosch" => "136-bosch",
            "delphi" => "176-delphi",
            "denso" => "182-denso",
            "zexel" => "173-zexel"
        ],
        "vysokotlakcerpadlo" => [
            "bosch" => "150-vysokotlake-cerpadlo-bosch",
            "delphi" => "146-vysokotlake-cerpadlo-delphi",
            "denso" => "142-vysokotlake-cerpadlo-denso",
            "zexel" => ""
        ],
        "vstrikovace" => [
            "bosch" => "159-vstrikovace-bosch",
            "delphi" => "164-vstrikovace-delphi",
            "denso" => "166-vstrikovace-denso",
            "zexel" => "163-vstrikovace-zexel"
        ]
    ];

    /**
     * @return string[]
     */
    public static function getProductTypes()
    {
        $result = [];

        foreach (self::$moznostiURL as $key => $value) {
            $result[$key] = $key;
        }

        return $result;
    }

    /**
     * @return string[]
     */
    public static function getBrands()
    {
        $result = [];

        foreach (self::$moznostiURL as $key => $links) {
            foreach ($links as $partner => $link) {
                if (!isset($result[$partner])) {
                    $result[$partner] = $partner;
                }
            }
        }

        return $result;
    }

    /**
     * @param string $type
     * @param string $company
     * @return array can be mapped on ProductEntity
     */
    public function getData($type = "vstrikcerp", $company = "bosch")
    {

        $url = "https://www.autonorma.cz/" . self::$moznostiURL[$type][$company];

        $data1 = $this->downloadData($url, $company);

        $data2 = $this->downloadData($url . "?p=2", $company);

        return array_merge($data1, $data2);
    }

    /**
     * @param $url
     * @param $com
     * @return mixed
     */
    private function downloadData($url, $com){
        // Zde získáme pomocí c-url data (zdrojový kód stránky).
        // Ta umístíme do proměnné $result
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);


        // Proměnná $result obsahuje naše informace, které "namapujeme"
        // pomocí regulárních výrazů.
        preg_match_all('/<a class="product-name".*?href="(.*?)" title="(.*?)".*?>/', $result, $vytazek);

        $data["links"] = $vytazek[1];
        $deliminer = strtoupper($com);

        foreach($vytazek[2] as $i => $name) {

            $temp_field = explode($deliminer, $name);
            $data["names"][$i] = $temp_field[0];

            if( count($temp_field) == 2 ){
                $data["codes"][$i] = $temp_field[1];
            }else{
                // Vypíše neznámý kód, pokud se nepodaří rozdělit text dle značky.
                $data["codes"][$i] = "neznámý";
            }
        }

        preg_match_all('/<div class="content_price" itemprop="offers" itemscope itemtype="https:\/\/schema.org\/Offer">(.*?)<\/div>/', $result, $ceny);


        foreach($ceny[1] as $i => $radka) {
            if( preg_match('/<span itemprop="price" class="price product-price">(.*?) Kč bez DPH<\/span>/', $radka, $bezdph)) {
                $data["bezdph"][$i] = $bezdph[1];
            } else {
                $data["bezdph"][$i] = "neuvedeno";
            }

            if(preg_match('/<span itemprop="price" class="" style="display:block">(.*?) Kč s DPH<\/span><meta itemprop="priceCurrency" content="CZK" \/>/', $radka, $sdph)) {
                $data["sdph"][$i] = $sdph[1];
            } else {
                $data["sdph"][$i] = "neuvedeno";
            }
        }

        curl_close($curl);

        return $data;
    }
}
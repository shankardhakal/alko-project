<?php

namespace Task;

use Medoo\Medoo;

class ProductImporter
{

    const REMOTE_FILE_LOC = "https://www.alko.fi/INTERSHOP/static/WFS/Alko-OnlineShop-Site/-/Alko-OnlineShop/fi_FI/Alkon%20Hinnasto%20Tekstitiedostona/alkon-hinnasto-tekstitiedostona.xls";

    const LOCAL_FILE_LOC = __DIR__ . "/downloaded_file.xls";

    const TABLE_NAME = 'alko_table';

    /**
     * @var $medoo Medoo
     */
    private $medoo;

    /**
     * ProductImporter constructor.
     * @param Medoo $medoo
     */
    public function __construct(Medoo $medoo)
    {
        $this->medoo = $medoo;
    }


    public function getDataFromAlko()
    {
        if (!file_exists(self::LOCAL_FILE_LOC)) {
            $alkoPriceFile = file_get_contents(self::REMOTE_FILE_LOC);
            file_put_contents(self::LOCAL_FILE_LOC, $alkoPriceFile);
        }

        $reader = \PHPExcel_IOFactory::createReaderForFile(self::LOCAL_FILE_LOC);

        $excelObject = $reader->load(self::LOCAL_FILE_LOC);

        $dataAsAssocArray = $excelObject->getActiveSheet()->toArray();

        array_shift($dataAsAssocArray);
        array_shift($dataAsAssocArray);
        array_shift($dataAsAssocArray);

        $headers = $dataAsAssocArray[0];

        array_shift($dataAsAssocArray);

        $exchangeRate = 1.789798; $this->getExchangeRate();

        $sqlData = [];
        foreach ($dataAsAssocArray as $key => $item) {

            $sqlData [] = [

                'Number' => $item[0],
                'Name' => $item[1],
                'Bottlesize' => $item[3],
                'Price' =>round( (float)($item[4] * $exchangeRate),2),
                'Timestamp' => date(DATE_ATOM)

            ];

        }
print_r(file_put_contents('data.json',json_encode($sqlData)));
        return $sqlData;

    }


    public function persistData($insertData)
    {
echo "Number of item to insert ".count($insertData).PHP_EOL;
        $this->medoo->insert(
            self::TABLE_NAME,
            $insertData
        );
    }

    private function getExchangeRate()
    {

        $access_key = 'e5adcce0a8be7b2cd79a13f7bbf78a1b';
        $apiUrl = "http://apilayer.net/api/live?access_key={$access_key}&currencies=EUR,GBP";

        $curlHandle = curl_init($apiUrl);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        $apiResponse = curl_exec($curlHandle);
        curl_close($curlHandle);

        $responseArray = json_decode($apiResponse, true);

        return $responseArray['quotes']['USDGBP'] / $responseArray['quotes']['USDEUR'];
    }


}
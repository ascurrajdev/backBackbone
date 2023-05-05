<?php
namespace App\Contracts;
use Illuminate\Support\Facades\Cache;

class ZipCodeContract {
    private function getPositionsOfZipCode($zipCode): array {
        $positions = [];
        if(Cache::has($zipCode)){
            return Cache::get($zipCode);
        }
        $pathZipCodesOnly = storage_path("app/zipcodesOnly.txt");
        $currentPosition = binarySearchInFile($pathZipCodesOnly,$zipCode,17);
        if($currentPosition == -1){
            return $positions;
        }
        $positions[] = $currentPosition["value"][1];
        $positionsAround = secuencialSearchInFile($pathZipCodesOnly,$zipCode,$currentPosition["position"]);
        foreach($positionsAround as $value){
            $positions[] = $value[1];
        }
        Cache::put($zipCode,$positions, now()->addMinutes(5));
        return $positions;
    }

    public function getDataOfZipCodeAndDecode($zipCode){
        $fileReader = fopen(storage_path("app/zipcodes.txt"),"r");
        $zipCodes = [];
        $headers = config("zipcodes.headers");
        $positions = $this->getPositionsOfZipCode($zipCode);
        foreach($positions as $numberPointer){
            fseek($fileReader,$numberPointer);
            $values = explode("|",fgets($fileReader));
            $dataset = [];
            foreach($headers as $key => $header){
                $dataset[$header] = strtoupper(iconv("ISO-8859-1","ASCII//TRANSLIT",trim($values[$key])));
            }
            $zipCodes[] = $dataset;
        }
        return $zipCodes;
    }

    public function getDataAndMapPrettyByZipCode($zipCode){
        $datasets = $this->getDataOfZipCodeAndDecode($zipCode);
        if(empty($datasets)){
            return $datasets;
        }
        $response = [
            "zip_code" => $zipCode,
            "locality" => $datasets[0]["d_ciudad"],
            "federal_entity" => [
                "key" => intval($datasets[0]["c_estado"]),
                "name" => $datasets[0]["d_estado"],
                "code" => null,
            ],
            "settlements" => [],
            "municipality" => [
                "key" => intval($datasets[0]["c_mnpio"]),
                "name" => $datasets[0]["D_mnpio"]
            ],
        ];
        foreach($datasets as $dataset){
            $response["settlements"][] = [
                "key" => intval($dataset["id_asenta_cpcons"]),
                "name" => $dataset["d_asenta"],
                "zone_type" => $dataset["d_zona"],
                "settlement_type" => [
                    "name" => $dataset["d_tipo_asenta"]
                ]
            ];
        }
        return $response;
    }
}
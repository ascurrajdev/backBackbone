<?php
namespace App\Contracts;

class ZipCodeContract {
    public function exists($zipCode){
        return !empty(config("zipcodes.{$zipCode}",[]));
    }
    public function getDataOfZipCodeAndDecode($zipCode){
        $fileReader = fopen(storage_path("app/zipcodes.txt"),"r");
        $zipCodes = [];
        $headers = config("zipcodes.headers");
        foreach(config("zipcodes.{$zipCode}",[]) as $numberPointer){
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
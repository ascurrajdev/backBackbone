<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ReadCsvZipCodesListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $path = Storage::path("zipcodes.txt");
        $pathSaveOnlyZipCodes = storage_path("app/zipcodesOnly.txt");
        $existsFileZipCodes = true;
        $fileReader = fopen($path, "r");
        if(file_exists($pathSaveOnlyZipCodes)){
            fgets($fileReader);
            $line = fgets($fileReader);
            $arrayLine = explode("|",$line);
            foreach($arrayLine as $key => $value){
                $arrayLine[$key] = trim($value);
            }
            config([
                "zipcodes.headers" => $arrayLine
            ]);
            fclose($fileReader);
            return ;
        }
        $fileWriterZipCodes = fopen($pathSaveOnlyZipCodes,"w");
        $linesCount = 0;
        while(!feof($fileReader)){
            $position = ftell($fileReader);
            $line = fgets($fileReader);
            $arrayLine = explode("|",$line);
            if($linesCount >= 2){
                $zipCode = $arrayLine[0];
                if(!empty($zipCode)){
                    fwrite($fileWriterZipCodes,$zipCode.",".str_pad($position,10,"0",STR_PAD_LEFT).PHP_EOL);
                }
            }else if($linesCount == 1){
                foreach($arrayLine as $key => $value){
                    $arrayLine[$key] = trim($value);
                }
                config([
                    "zipcodes.headers" => $arrayLine
                ]);
            }
            $linesCount++;
        }
        fclose($fileReader);
        fclose($fileWriterZipCodes);
        Log::info("Ya se ha almacenado la configuracion de los codigos postales");

    }
}

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
        $fileReader = fopen($path, "r");
        $linesCount = 0;
        while(!feof($fileReader)){
            $position = ftell($fileReader);
            $line = fgets($fileReader);
            $arrayLine = explode("|",$line);
            if($linesCount >= 2){
                $zipCode = $arrayLine[0];
                if(empty(config("zipcodes.{$zipCode}"))){
                    config([
                        "zipcodes.{$zipCode}" => [ $position ]
                    ]);
                }else{
                    $values = config("zipcodes.{$zipCode}");
                    $values[] = $position;
                    config([
                        "zipcodes.{$zipCode}" => $values
                    ]);
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
        Log::info("Ya se ha almacenado la configuracion de los codigos postales");

    }
}

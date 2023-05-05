<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ReadCsvZipCodesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zip-codes:readcsv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando lee los codigos postales y indexa en un archivo a parte para su posterior procesamiento';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = Storage::path("zipcodes.txt");
        $pathSaveOnlyZipCodes = storage_path("app/zipcodesOnly.txt");
        $fileReader = fopen($path, "r");
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
                cache([
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

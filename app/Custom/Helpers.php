<?php
if(!function_exists("binarySearchInFile")){
    function binarySearchInFile($file, $searchValue, $lineLength) {
        $fileReader = fopen($file, 'r');
        $left = 0;
        fseek($fileReader, 0, SEEK_END);
        $right = floor(ftell($fileReader) / $lineLength) - 1;
    
        while ($left <= $right) {
            $middle = floor(($left + $right) / 2);
            fseek($fileReader, $middle * $lineLength);
            $line = fgetcsv($fileReader);
    
            if ($line[0] == $searchValue) {
                fclose($fileReader);
                return [
                    "position" => $middle * $lineLength,
                    "value" => $line
                ];
            } elseif ($line[0] < $searchValue) {
                $left = $middle + 1;
            } else {
                $right = $middle - 1;
            }
        }
    
        fclose($fileReader);
        return -1;
    }
    
}
if(!function_exists("secuencialSearchInFile")){
    function secuencialSearchInFile($file, $searchValue, $fromPosition,$mode = 1,$readLines = 7, $lineLength = 17){
        $fileReader = fopen($file,'r');
        $count = $mode == 1 ? $readLines : $readLines * -1;
        $datasets = [];
        $currentPosition = ($fromPosition + ($lineLength * $count));
        if($currentPosition < 0){
            return [];
        }
        fseek($fileReader,$currentPosition);
        while(!feof($fileReader) && $count != 0){
            if(empty($dataLine = fgetcsv($fileReader))){
                break;
            }
            if($dataLine[0] == $searchValue){
                $datasets[] = $dataLine;
            }
            if($mode == 1){
                $count--;
            }
            if($mode == 2){
                $count++;
            }
        }
        fclose($fileReader);
        return $datasets;
    }
}
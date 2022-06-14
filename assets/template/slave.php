<?php

require_once('..'.
    DIRECTORY_SEPARATOR.'vendor'.
    DIRECTORY_SEPARATOR.'autoload.php');

require_once(realpath('../') .
    DIRECTORY_SEPARATOR . 'Micro' .
    DIRECTORY_SEPARATOR . 'Config' .
    DIRECTORY_SEPARATOR . 'php_globals.php');

include  __DIR__.'/../Micro/Jobs/dummyJob.php';
include  __DIR__.'/../Micro/Jobs/postUploadTask.php';



//$output = shell_exec('ps -C php -f');
//if (strpos($output, "watch.php")===false) {
////    shell_exec('php -f slave.php  > /dev/null 2>&1 &');   // Start the script
//    echo "NOT RUNNING";
//}else{
//    echo "RUNNING";
//}


$job_data = json_decode(base64_decode($argv[1]));
$callBack = $job_data->callable;
$data = $job_data->payload;
$callBack($data);


//while(true){
//
//    if(isset($argv[1])){
//
//        $job_data = json_decode(base64_decode($argv[1]));
//        $callBack = $job_data->callable;
//        $data = $job_data->payload;
//        $callBack($data);
//
//    }
//}

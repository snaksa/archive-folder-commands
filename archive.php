<?php
require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Finder\Finder;

function usage() {
    echo "usage: php archive.php [-a archive.zip] [-f /home/myuser/Documents] [-d 30] [-h] \n";
    echo "  -a      archive file name \n";
    echo "  -f      set folder to scan \n";
    echo "  -d      set number of days \n";
    echo "  -h      show this help message \n";
    exit;
}

#default values
$archiveName = 'docs_' . (new \DateTime())->format('Y-m-d-H-i-s');
$folder = "";
$days = 30; 

$options = getopt("a:f:d:h");
foreach($options as $key => $option) {
    if($key === 'a') $archiveName = $option;
    else if($key === 'f') $folder = $option;
    else if($key === 'd') $days = $option;
    else if($key === 'h') usage();
}

#check if folder to be archived is provided
if($folder === "") {
    echo "Please provide folder to be archived!\n";
    exit;
}

#check if filename has .zip extension
if (strpos($archiveName, '.zip') !== strlen($archiveName) - 4) {
    $archiveName .= '.zip';
}

#check if folder to be archived has slash at the end
if ($folder[strlen($folder) - 1] !== '/') {
    $folder .= '/';
}

#initialize Symfony Finder instance
$finder = new Finder();
#find all files older than X days
$finder->date("< $days days ago")->files();

$fileNames = []; #store filenames so we can delete them later
$zip = new ZipArchive; #initialize zip archive
if ($zip->open($archiveName, ZipArchive::CREATE) === TRUE) { #create file
    try {
        foreach ($finder->in($folder) as $file) {
            $path = $folder . $file->getRelativePathname();
            $zip->addFile($path); #add file to archive
            $fileNames[] = $path; #and save path
        }
        $zip->close(); #close and compress the files
    }
    catch(\Exception $e) {
        echo "Folder to be archived not found!\n";
        exit;
    }

    #delete files if we have permission after successfully compressing them
    foreach($fileNames as $file){
        if(is_writable($file)){
            unlink($file);
        }
    }

    echo 'Success' . "\n";
} else {
    echo 'Could not create the zip file' . "\n";
}
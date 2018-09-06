#!/bin/bash

#usage menu
function usage {
  echo "usage: ./archive [-a archive.tar.gz] [-f /home/myuser/Documents] [-d 30]"
  echo "  -a      archive file name"
  echo "  -f      set folder to scan"
  echo "  -d      set number of days"
  exit 1
}

#default values
archiveName="archive-$(date +'%Y-%m-%d-%H-%M-%S').tar.gz"
folder=""
days=30

while getopts ":a:f:d:" op;
do
  case "$op" in
    a) archiveName="$OPTARG";;
    f) folder="$OPTARG";;
    d) days="$OPTARG";;
    *) usage;;
  esac
done

if [[ $folder == "" ]]
then
  echo "Please provide folder to be archived!";
  exit 1
fi

#check if archive name has .tar.gz extension
if [[ $archiveName != *.tar.gz ]]
then
	archiveName="${archiveName}.tar.gz" #append if doesn't
fi

#calculate the exact minutes in N days
minutes=$((60*24*$days))

#create empty archive
tar -cf "$archiveName" --files-from /dev/null

#find files older than X minutes
find "$folder" -name '*.*' -mmin "+${minutes}" |
while read fname; #read filename from the standard output
do
  tar -rf "$archiveName" "$fname" #append the file to the archive
  rm $fname #remove file
done
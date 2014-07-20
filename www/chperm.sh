#!/bin/bash

if [ $# -ne 1 ]; then
    echo $0: usage: myscript name
    exit 1
fi

# save the files and respective owners in permissions.txt
# sh -c "ls -alF --group-directories-first | grep -v / > permissions.txt"

# change owner:group of files in directory to debian:debian
# lista=$(ls -aF | grep -v /)
# chown debian:debian $lista
# unset lista #free variable from bash
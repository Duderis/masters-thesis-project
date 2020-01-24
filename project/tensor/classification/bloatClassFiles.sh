#!/bin/bash

COUNT=$1
FILENAME=$2
FOLDER=$3

i=0
while (( i++ < $1 )); do
    cp -f "${FILENAME}" "${FOLDER}/bloat_${i}.png"
done

#!/bin/sh

if [ $# -eq 0 ]; then
        echo "meld fuel-docs fuel-docs-nekoget"
        echo " usage: $0 file"
        echo "    eg: $0 index.html"
        exit;
fi

file="$1"
meld "./fuel-docs/$file" "./fuel-docs-nekoget/$file" &
firefox "./fuel-docs-nekoget/$file" &

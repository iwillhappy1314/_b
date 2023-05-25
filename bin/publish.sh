#!/usr/bin/env bash
dir_path=$(pwd)
dir_name=$(basename $dir_path)
zip_name=$dir_name.zip

if [ -f $zip_name ]; then
    rm $zip_name
fi

if [ $1 = "production" ]; then
    zip -r $zip_name . -x "*.yaml" -x "*.lock" -x "*.json" -x "*.js" -x "bin/*" -x ".git/*" -x "node_modules/*"
else
    zip -r $zip_name . -x ".git/*" -x "node_modules/*"
fi
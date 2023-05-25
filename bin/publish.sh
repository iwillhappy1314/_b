#!/usr/bin/env bash
dir_path=$(pwd)
dir_name=$(basename $dir_path)
zip_name=$dir_name.zip

if [ -f $zip_name ]; then
    rm $zip_name
fi

if [ $1 = "production" ]; then
    zip -r $zip_name . -x "frontent/*.yaml" -x "frontent/*.lock" -x "frontent/*.json" -x "frontent/*.js" -x "bin/*" -x ".git/*" -x "frontent/node_modules/*"
else
    zip -r $zip_name . -x ".git/*" -x "frontent/node_modules/*"
fi
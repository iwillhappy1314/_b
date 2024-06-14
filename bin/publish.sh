#!/usr/bin/env bash
dir_path=$(pwd)
dir_name=$(basename "$dir_path")
zip_name=$dir_name.zip

if [ -f "$zip_name" ]; then
    rm "$zip_name"
fi

if [ "$1" = "production" ]; then
    zip -r "../$zip_name" . -x "frontend/*.yaml" -x "frontend/*.lock" -x "frontend/*.json" -x "frontend/*.js" -x "bin/*" -x ".git/*" -x "frontend/node_modules/*"
else
    zip -r "../$zip_name" . -x ".git/*" -x "frontend/node_modules/*"
fi
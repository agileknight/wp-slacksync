#!/bin/bash

(cd react-app && npm run build)
rm -rf wpslacksync/assets/js-gen
mkdir -p wpslacksync/assets/js-gen
cp react-app/build/wpslacksync-app.* wpslacksync/assets/js-gen/

tag=$(git describe --tags)

rm -f wpslacksync-*.zip

# files with "." are not allowed and break the Wordpress plugin deployment process
zip -r wpslacksync-${tag}.zip wpslacksync -x "*.DS_Store" -x ".gitignore"

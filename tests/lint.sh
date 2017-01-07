#!/bin/bash
echo Running PHP lint scans...
shopt -s globstar

function scanFile {
    OUTPUT=`php -l "$1"`
    [ $? -ne 0 ] && echo -n "$OUTPUT" && exit 1
}

for file in src/pocketmine/*.php src/pocketmine/**/*.php; do scanFile "$file"; done
for file in als/**/*.php; do scanFile "$file"; done

echo Lint scan completed successfully.

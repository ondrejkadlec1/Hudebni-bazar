#!/bin/bash

echo "START"

cd /usr/local/bin && ln -f -s php php8.1

apachectl -D FOREGROUND

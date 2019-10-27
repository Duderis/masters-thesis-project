#!/bin/sh

python3 /magic/server/server.py $(awk 'END{print $1}' /etc/hosts)
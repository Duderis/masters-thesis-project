#!/bin/bash

WORK_DIR="/magic/classification"

"${WORK_DIR}"/bloatClassFiles.sh \
    500 \
    /magic/classification/dataset/real/tower/641b4e7fe4496e0eb17f514daeab08a1.png \
    /magic/classification/dataset/real/tower

"${WORK_DIR}"/bloatClassFiles.sh 
    500 \
    /magic/classification/dataset/real/sidebar/f0a89bacd662a915d0f0a097d6b2aa79.png \
    /magic/classification/dataset/real/sidebar 
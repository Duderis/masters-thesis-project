#!/bin/bash
set -e
# Set up the working environment.
WORK_DIR="/magic/segmentation/deeplab"
DATASET_DIR="datasets"

# Set up the working directories.
# SYS_FOLDER="SYS"

# MODEL_DIR="${WORK_DIR}/${DATASET_DIR}/${SYS_FOLDER}/models"
# ANALYZE_DIR="${WORK_DIR}/${DATASET_DIR}/${SYS_FOLDER}/analyses"

MODEL=$1
TARGET=$2
SAVE1=$3 #This is the one we will send to class network
SAVE2=$4 #This one shows a nice "graph" of segmentation results

python "${WORK_DIR}"/eval_one.py \
  --modelpath="${MODEL}" \
  --target="${TARGET}" \
  --save="${SAVE1}" \
  --savedetailed="${SAVE2}"

python "${WORK_DIR}"/prepare_image.py "${SAVE1}"
#!/bin/bash
set -e
# Set up the working environment.
WORK_DIR="/magic/classification"
DATASET_DIR="dataset"

TRAIN_DIR="${WORK_DIR}/${DATASET_DIR}/real"
VAL_DIR="${TRAIN_DIR}"
MODEL_SAVE_DIR="${WORK_DIR}/${DATASET_DIR}/models"

NAME=$1

python "${WORK_DIR}"/train_class.py \
    --trainpath="${TRAIN_DIR}" \
    --valpath="${VAL_DIR}" \
    --batchsize=128 \
    --epochs=5 \
    --modelsavepath="${MODEL_SAVE_DIR}/${NAME}.model"

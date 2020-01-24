#!/bin/bash
set -e
# Set up the working environment.
WORK_DIR="/magic/segmentation/deeplab"
DATASET_DIR="datasets"

# Set up the working directories.
SYS_FOLDER="SYS"

EXP_FOLDER="${WORK_DIR}/${DATASET_DIR}/${SYS_FOLDER}/exp"

INIT_FOLDER="${EXP_FOLDER}/init_models"
TRAIN_LOGDIR="${EXP_FOLDER}/train"
DATASET="${WORK_DIR}/${DATASET_DIR}/${SYS_FOLDER}/tfrecord"
EXPORT_DIR="${EXP_FOLDER}/export"

NUM_ITERATIONS=$1
NAME=$2

CKPT_PATH="${TRAIN_LOGDIR}/model.ckpt-${NUM_ITERATIONS}"
EXPORT_PATH="${EXPORT_DIR}/frozen_inference_graph.pb"
MODEL_PATH="${WORK_DIR}/${DATASET_DIR}/${SYS_FOLDER}/models"

MODEL_NAME="${NAME}"

mkdir -p "${MODEL_PATH}/${MODEL_NAME}"

mv "${EXPORT_PATH}" "${MODEL_PATH}/${MODEL_NAME}/"
# cp "${EXPORT_PATH}" "${MODEL_PATH}/${MODEL_NAME}/"
mv "${CKPT_PATH}"* "${MODEL_PATH}/${MODEL_NAME}/"
# cp "${CKPT_PATH}"* "${MODEL_PATH}/${MODEL_NAME}/"

tar -czf "${MODEL_PATH}/${MODEL_NAME}.model" "${MODEL_PATH}/${MODEL_NAME}"

rm -rf "${EXP_FOLDER}"
rm -rf "${MODEL_PATH}/${MODEL_NAME}"

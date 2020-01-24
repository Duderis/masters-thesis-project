#!/bin/bash
set -e
# Set up the working environment.
WORK_DIR="/magic/classification"
DATASET_DIR="dataset"

ANALYSES_DIR="${WORK_DIR}/${DATASET_DIR}/analyses_further"

MODEL_DIR="${WORK_DIR}/${DATASET_DIR}/models"

RESULT_SAVE_DIR="${WORK_DIR}/${DATASET_DIR}/results"

MODEL_NAME=$1
ANALYSIS_NAME=$2
RESULT_NAME=$3

SELECTED_MODEL="${MODEL_DIR}/${MODEL_NAME}"
TARGET_IMG="${ANALYSES_DIR}/${ANALYSIS_NAME}"
# TARGET_IMG="${ANALYSIS_NAME}"

python "${WORK_DIR}"/eval_one.py \
    --modelpath="${SELECTED_MODEL}" \
    --target="${TARGET_IMG}" \
    --resultsave="${RESULT_SAVE_DIR}/${RESULT_NAME}"

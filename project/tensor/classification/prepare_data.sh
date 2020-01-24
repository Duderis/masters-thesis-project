#!/bin/bash
set -e
# Set up the working environment.
WORK_DIR="/magic/classification"
DATASET_DIR="dataset"

ORIGINAL_DIR="${WORK_DIR}/${DATASET_DIR}/original"
REAL_DIR="${WORK_DIR}/${DATASET_DIR}/real"

python "${WORK_DIR}"/prepare_data.py "${ORIGINAL_DIR}" "${REAL_DIR}"


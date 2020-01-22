#!/bin/bash
set -e
# Set up the working environment.
WORK_DIR="/magic/segmentation/deeplab"
DATASET_DIR="datasets"

# Set up the working directories.
SYS_FOLDER="SYS"
EXP_FOLDER="exp"
INIT_FOLDER="${WORK_DIR}/${DATASET_DIR}/${SYS_FOLDER}/${EXP_FOLDER}/init_models"
TRAIN_LOGDIR="${WORK_DIR}/${DATASET_DIR}/${SYS_FOLDER}/${EXP_FOLDER}/train"
DATASET="${WORK_DIR}/${DATASET_DIR}/${SYS_FOLDER}/tfrecord"

mkdir -p "${WORK_DIR}/${DATASET_DIR}/${SYS_FOLDER}/exp"
mkdir -p "${TRAIN_LOGDIR}"

NUM_ITERATIONS=$1
echo ${NUM_ITERATIONS}
python -W ignore "${WORK_DIR}"/train.py \
  --logtostderr \
  --train_split="train" \
  --model_variant="xception_65" \
  --atrous_rates=6 \
  --atrous_rates=12 \
  --atrous_rates=18 \
  --output_stride=16 \
  --decoder_output_stride=4 \
  --train_crop_size="513,513" \
  --train_batch_size=2 \
  --training_number_of_steps="${NUM_ITERATIONS}" \
  --fine_tune_batch_norm=true \
  --train_logdir="${TRAIN_LOGDIR}" \
  --dataset_dir="${DATASET}"
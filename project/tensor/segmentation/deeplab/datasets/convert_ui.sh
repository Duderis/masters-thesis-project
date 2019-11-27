WORK_DIR="./UI"
ROOT="${WORK_DIR}/dataset/"
SEG_FOLDER="${ROOT}/SegmentationClass"
SEMANTIC_SEG_FOLDER="${ROOT}/SegmentationClassRaw"
# Build TFRecords of the dataset.
OUTPUT_DIR="${WORK_DIR}/tfrecord"
mkdir -p "${OUTPUT_DIR}"
IMAGE_FOLDER="${ROOT}/JPEGImages"
LIST_FOLDER="${ROOT}/ImageSets"
echo "Converting UI dataset..."
python ./build_ui_data.py \
  --image_folder="${IMAGE_FOLDER}" \
  --semantic_segmentation_folder="${SEMANTIC_SEG_FOLDER}" \
  --list_folder="${LIST_FOLDER}" \
  --image_format="jpg" \
  --output_dir="${OUTPUT_DIR}"
import os
from io import BytesIO
import tarfile
from six.moves import urllib
import matplotlib
matplotlib.use('Agg')
from matplotlib import gridspec
from matplotlib import pyplot as plt
import numpy as np
from PIL import Image
import tensorflow as tf

flags = tf.app.flags
FLAGS = flags.FLAGS

# flags.DEFINE_string('modeldir', '/magic/segmentation/deeplab/datasets/SYS/models', 'The directory for models')

# flags.DEFINE_string('analyzedir', '/magic/segmentation/deeplab/datasets/SYS/models', 'The directory for analyze targets')

flags.DEFINE_string('target', '', 'Infer target filepath')


flags.DEFINE_string('modelpath', '', 'Tared model to use for infer')

flags.DEFINE_string('savedetailed', '', 'Detailed result save')

flags.DEFINE_string('save', '', 'Undetailed result save for next neural network')


class DeepLabModel(object):
    """Class to load deeplab model and run inference."""

    INPUT_TENSOR_NAME = 'ImageTensor:0'
    OUTPUT_TENSOR_NAME = 'SemanticPredictions:0'
    INPUT_SIZE = 513
    FROZEN_GRAPH_NAME = 'frozen_inference_graph'

    def __init__(self, tarball_path):
        """Creates and loads pretrained deeplab model."""
        self.graph = tf.Graph()

        graph_def = None
        # Extract frozen graph from tar archive.
        tar_file = tarfile.open(tarball_path)
        for tar_info in tar_file.getmembers():
            if self.FROZEN_GRAPH_NAME in os.path.basename(tar_info.name):
                file_handle = tar_file.extractfile(tar_info)
                graph_def = tf.GraphDef.FromString(file_handle.read())
                break

        tar_file.close()

        if graph_def is None:
            raise RuntimeError('Cannot find inference graph in tar archive.')

        with self.graph.as_default():
            tf.import_graph_def(graph_def, name='')

        self.sess = tf.Session(graph=self.graph)

    def run(self, image):
        """Runs inference on a single image.

        Args:
          image: A PIL.Image object, raw input image.

        Returns:
          resized_image: RGB image resized from original input image.
          seg_map: Segmentation map of `resized_image`.
        """
        width, height = image.size
        resize_ratio = 1.0 * self.INPUT_SIZE / max(width, height)
        target_size = (int(resize_ratio * width), int(resize_ratio * height))
        resized_image = image.convert('RGB').resize(target_size, Image.ANTIALIAS)
        batch_seg_map = self.sess.run(
            self.OUTPUT_TENSOR_NAME,
            feed_dict={self.INPUT_TENSOR_NAME: [np.asarray(resized_image)]})
        seg_map = batch_seg_map[0]
        return resized_image, seg_map


# def create_pascal_label_colormap():
#     """Creates a label colormap used in PASCAL VOC segmentation benchmark.

#     Returns:
#         A Colormap for visualizing segmentation results.
#     """
#     colormap = np.zeros((256, 3), dtype=int)
#     ind = np.arange(256, dtype=int)

#     for shift in reversed(range(8)):
#         for channel in range(3):
#             colormap[:, channel] |= ((ind >> channel) & 1) << shift
#         ind >>= 3

#     return colormap


def labelToColorImage(label):
    """Adds color defined by the dataset colormap to the label.

    Args:
      label: A 2D array with integer type, storing the segmentation label.

    Returns:
      result: A 2D array with floating type. The element of the array
        is the color indexed by the corresponding element in the input label
        to the PASCAL color map.

    Raises:
      ValueError: If label is not of rank 2 or its value is larger than color
        map maximum entry.
    """
    if label.ndim != 2:
        raise ValueError('Expect 2-D input label')

    colormap = np.asarray([
        [0,0,0],
        [255,0,0],
        [255,0,218],
        [114,0,255],
        [0,5,255]
    ])

    if np.max(label) >= len(colormap):
        raise ValueError('label value too large.')

    return colormap[label]


def visSegmentationDetailed(image, seg_map):
    """Visualizes input image, segmentation map and overlay view."""
    plt.figure(figsize=(15, 5))
    grid_spec = gridspec.GridSpec(1, 4, width_ratios=[6, 6, 6, 1])

    plt.subplot(grid_spec[0])
    plt.imshow(image)
    plt.axis('off')
    plt.title('input image')

    plt.subplot(grid_spec[1])
    seg_image = labelToColorImage(seg_map).astype(np.uint8)
    plt.imshow(seg_image)
    plt.axis('off')
    plt.title('segmentation map')

    plt.subplot(grid_spec[2])
    plt.imshow(image)
    plt.imshow(seg_image, alpha=0.7)
    plt.axis('off')
    plt.title('segmentation overlay')

    unique_labels = np.unique(seg_map)
    ax = plt.subplot(grid_spec[3])
    plt.imshow(
        FULL_COLOR_MAP[unique_labels].astype(np.uint8), interpolation='nearest')
    ax.yaxis.tick_right()
    plt.yticks(range(len(unique_labels)), LABEL_NAMES[unique_labels])
    plt.xticks([], [])
    ax.tick_params(width=0.0)
    plt.grid('off')
    plt.savefig(FLAGS.savedetailed, bbox_inches='tight')

def visSegmentation(seg_map):
    fig = plt.figure()
    # fig.patch.set_visible(False)
    ax = fig.add_axes([0, 0, 1, 1])
    ax.axis('off')
    seg_image = labelToColorImage(seg_map).astype(np.uint8)
    ax.imshow(seg_image)
    with open(FLAGS.save, 'w') as outfile:
        fig.canvas.print_png(outfile)


LABEL_NAMES = np.asarray([
    'background', 'menu', 'sidebar', 'content', 'footer'
])

FULL_LABEL_MAP = np.arange(len(LABEL_NAMES)).reshape(len(LABEL_NAMES), 1)
FULL_COLOR_MAP = labelToColorImage(FULL_LABEL_MAP)

# model_path = os.path.join(FLAGS.modeldir, FLAGS.modelpath)
model_path = FLAGS.modelpath

MODEL = DeepLabModel(model_path)
print('model loaded successfully!')

def runVisualization(target_path):
    """Inferences DeepLab model and visualizes result."""
    try:
        original_im = Image.open(target_path)
    except Exception:
        print('image not found')
        return

    print('running deeplab on image %s...' % target_path)
    resized_im, seg_map = MODEL.run(original_im)
    # print(resized_im)
    # print(seg_map

    visSegmentation(seg_map)
    visSegmentationDetailed(resized_im, seg_map)


# image_path = os.path.join(FLAGS.analyzedir, FLAGS.target)
image_path = FLAGS.target
print(image_path)
runVisualization(image_path)

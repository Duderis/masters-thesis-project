from __future__ import absolute_import, division, print_function, unicode_literals

# TensorFlow and tf.keras
import tensorflow as tf
from tensorflow import keras

# Helper libraries
import numpy as np
import matplotlib.pyplot as plt

IMG_SIZE = 326
IMG_HEIGHT = IMG_SIZE
IMG_WIDTH = IMG_SIZE

model = keras.Sequential([
    keras.layers.Flatten(input_shape=(IMG_HEIGHT, IMG_WIDTH)),
    keras.layers.Dense(128, activation='relu'),
    keras.layers.Dense(1, activation='softmax')
])
from __future__ import absolute_import, division, print_function, unicode_literals
import tensorflow as tf
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Dense, Conv2D, Flatten, Dropout, MaxPooling2D
from tensorflow.keras.preprocessing.image import ImageDataGenerator
from PIL import Image

import os
import numpy as np
import matplotlib.pyplot as plt
import json

flags = tf.app.flags
FLAGS = flags.FLAGS

flags.DEFINE_string('modelpath', '', 'Path to the model')

flags.DEFINE_string('target', '', 'Target to evaluate')

flags.DEFINE_string('resultsave', '', 'Path to save results')

modelPath = FLAGS.modelpath

if not os.path.exists(modelPath):
    raise Exception('Model does not exist')

def load(filename):
    npImage = Image.open(filename)
    npImage = np.array(npImage).astype('float32')/255
    # print(npImage.shape)

    npImage = npImage[...,:3]
    # print(npImage.shape)

    npImage = np.expand_dims(npImage, axis=0)
    # print(npImage.shape)
    
    return npImage

# Load model
model = tf.keras.models.load_model(modelPath)

# Load image
image = load(FLAGS.target)

# Predict
prediction = model.predict(image)

singlePred = prediction[0][0]
# print(singlePred)
# print(type(singlePred))

result = { 'result': singlePred.item() }
# print(result)

# print(json.dumps(result))
# Write prediction to file
file = open(FLAGS.resultsave, 'w')

json.dump(result, file)
file.close()

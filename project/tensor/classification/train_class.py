from __future__ import absolute_import, division, print_function, unicode_literals
import tensorflow as tf
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Dense, Conv2D, Flatten, Dropout, MaxPooling2D
from tensorflow.keras.preprocessing.image import ImageDataGenerator

import os
import numpy as np
import matplotlib.pyplot as plt

flags = tf.app.flags
FLAGS = flags.FLAGS

flags.DEFINE_string('trainpath', '', 'Path to the training data folder')

flags.DEFINE_string('valpath', '', 'Path to the validate data folder')

flags.DEFINE_integer('batchsize', 128, 'Batch size')

flags.DEFINE_integer('epochs', 15, 'Epoch amount')

flags.DEFINE_string('modelsavepath', '', 'Full model save path')

IMG_SIZE = 326
IMG_HEIGHT = IMG_SIZE
IMG_WIDTH = IMG_SIZE

batchSize = FLAGS.batchsize
epochs = FLAGS.epochs

trainDir = FLAGS.trainpath
valDir = FLAGS.valpath

train_image_generator = ImageDataGenerator(rescale=1./255) # Generator for our training data
validation_image_generator = ImageDataGenerator(rescale=1./255) # Generator for our validation data

def getDirTotal(dir):
    total = 0
    for folder in os.listdir(dir):
        total += len(os.listdir(os.path.join(dir, folder)))
    return total

totalTrain = getDirTotal(trainDir)
totalVal = getDirTotal(valDir)

train_data_gen = train_image_generator.flow_from_directory(
    batch_size=batchSize,
    directory=trainDir,
    shuffle=True,
    target_size=(IMG_HEIGHT, IMG_WIDTH),
    class_mode='binary'
)

val_data_gen = validation_image_generator.flow_from_directory(
    batch_size=batchSize,
    directory=valDir,
    target_size=(IMG_HEIGHT, IMG_WIDTH),
    class_mode='binary'
)

model = Sequential([
    Conv2D(16, 3, padding='same', activation='relu', input_shape=(IMG_HEIGHT, IMG_WIDTH, 3)),
    MaxPooling2D(),
    Conv2D(32, 3, padding='same', activation='relu'),
    MaxPooling2D(),
    Conv2D(64, 3, padding='same', activation='relu'),
    MaxPooling2D(),
    Flatten(),
    Dense(512, activation='relu'),
    Dense(1, activation='sigmoid')
])

model.compile(
    optimizer='adam',
    loss='binary_crossentropy',
    metrics=['accuracy']
)

model.fit_generator(
    train_data_gen,
    steps_per_epoch=totalTrain // batchSize,
    epochs=epochs,
    validation_data=val_data_gen,
    validation_steps=totalVal // batchSize
)

model.save(FLAGS.modelsavepath)

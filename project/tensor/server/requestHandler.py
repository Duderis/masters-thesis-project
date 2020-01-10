import subprocess
import os
import json
from fileManager import FileManager

OPERATION_OVERRIDE = 'override'
OPERATION_PREPARE_SEGMENTATION_TRAINING_DATA = 'prepare_seg_train'
OPERATION_TRAIN_SEGMENTATION = 'train_seg'
OPERATION_TRAIN_CLASSIFICATION = 'train_class'
OPERATION_INFER_SEGMENTATION = 'infer_seg'
OPERATION_INFER_CLASSIFICATION = 'infer_class'

DEFAULT_ITERATION_NUM = 10

class RequestHandler(object):
    def __init__(self):
        self.__fm = FileManager()

    def handle_request(self, message):#---------------------------------
        decodedMessage = json.loads(message)
        op = decodedMessage['operation']
        body = decodedMessage['body']

        if OPERATION_OVERRIDE == op:
            self.handle_override(body)
        elif OPERATION_PREPARE_SEGMENTATION_TRAINING_DATA == op:
            self.handle_seg_prepare_train(body)
        elif OPERATION_TRAIN_SEGMENTATION == op:
            self.handle_seg_train(body)
        elif OPERATION_TRAIN_CLASSIFICATION == op:
            self.handle_class_train(body)            
        elif OPERATION_INFER_SEGMENTATION == op:
            self.handle_seg_infer(body)
        elif OPERATION_INFER_CLASSIFICATION == op:
            self.handle_class_infer(body)

        else:
            print('Unsupported message')



    def handle_override(self, body):#-----------------------------------
        subprocess.run(body['command'])
        print('Finished override command')

    def handle_seg_prepare_train(self, body):#--------------------------
        fm = self.__fm
        # create train;val;trainval files
        fm.writeToDumbFiles(body['data'])

        # modify 255 to 1.0
        subprocess.run([
            'python', 
            '/magic/segmentation/deeplab/datasets/label.py', 
            '/magic/segmentation/deeplab/datasets/SYS/'
            ])
        
        # generate actual tf records
        print('converting dataset to tf records')
        subprocess.run([
            '/magic/segmentation/deeplab/datasets/convert_sys.sh'
        ])

        # define dataset description
        print('creating/updating info file')
        fm.writeInfoFile(body['info'])
        print('Segmentation training data preparation complete!')

    def handle_seg_train(self, body):#---------------------------------
        print('training segmentation net')
        if 'iterationNum' in body:
            iterationNum = body['iterationNum']
        else:
            iterationNum = DEFAULT_ITERATION_NUM
        
        subprocess.run([
            '/magic/segmentation/deeplab/train-sys.sh',
            iterationNum
        ])
        print('Segmentation training complete!')
        
    
    def handle_class_train(self, body):#-------------------------------
        print('Unsupported message')

    def handle_seg_infer(self, body):#---------------------------------
        print('Unsupported message')

    def handle_class_infer(self,body):#--------------------------------
        print('Unsupported message')
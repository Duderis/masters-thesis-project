import os
import json
from fileManager import FileManager
from commandHandler import CommandHandler
from httpHandler import HttpHandler
import calendar
import time

OPERATION_OVERRIDE = 'override'
OPERATION_PREPARE_SEGMENTATION_TRAINING_DATA = 'prepare_seg_train'
OPERATION_PREPARE_CLASSIFICATION_TRAINING_DATA = 'prepare_class_train'
OPERATION_TRAIN_SEGMENTATION = 'train_seg'
OPERATION_TRAIN_CLASSIFICATION = 'train_class'
OPERATION_ANALYZE = 'analyze'

DEFAULT_ITERATION_NUM = 10

PRNT_BLUE = '\033[94m'
PRNT_ENDC = '\033[0m'

class RequestHandler(object):
    def __init__(self):
        self.__fm = FileManager()
        self.__cm = CommandHandler()
        self.__hh = HttpHandler()

    def handle_request(self, message):#---------------------------------
        decodedMessage = json.loads(message)
        op = decodedMessage['operation']
        body = decodedMessage['body']

        if OPERATION_OVERRIDE == op:
            self.handle_override(body)
        elif OPERATION_PREPARE_SEGMENTATION_TRAINING_DATA == op:
            self.handle_seg_prepare_train(body)
        elif OPERATION_PREPARE_CLASSIFICATION_TRAINING_DATA == op:
            self.handle_class_prepare_train(body)
        elif OPERATION_TRAIN_SEGMENTATION == op:
            self.handle_seg_train(body)
        elif OPERATION_TRAIN_CLASSIFICATION == op:
            self.handle_class_train(body)            
        elif OPERATION_ANALYZE == op:
            self.handle_analyze(body)

        else:
            print(PRNT_BLUE+'Unsupported message'+PRNT_ENDC)


    def handle_override(self, body):#-----------------------------------
        cm = self.__cm
        cm.handle(body['command'])
        print(PRNT_BLUE+'Finished override command'+PRNT_ENDC)

    def handle_seg_prepare_train(self, body):#--------------------------
        fm = self.__fm
        cm = self.__cm
        # create train;val;trainval files
        fm.writeToDumbFiles(body['data'])

        # modify 255 to 1.0
        cm.handle([
            'python', 
            '/magic/segmentation/deeplab/datasets/label.py', 
            '/magic/segmentation/deeplab/datasets/SYS/'
            ])
        
        # generate actual tf records
        print(PRNT_BLUE+'converting dataset to tf records'+PRNT_ENDC)
        cm.handle([
            '/magic/segmentation/deeplab/datasets/convert_sys.sh'
        ])

        # define dataset description
        print(PRNT_BLUE+'creating/updating info file'+PRNT_ENDC)
        fm.writeInfoFile(body['info'])
        print(PRNT_BLUE+'Segmentation training data preparation complete!'+PRNT_ENDC)

    def handle_seg_train(self, body):#---------------------------------
        cm = self.__cm
        hh = self.__hh
        if 'iterationNum' in body:
            iterationNum = body['iterationNum']
        else:
            iterationNum = DEFAULT_ITERATION_NUM

        # define name as timestamp
        name = 'segmentation_' + str(calendar.timegm(time.gmtime()))
        
        cm.handle([
            '/magic/segmentation/deeplab/train-sys.sh',
            str(iterationNum)
        ])
            
        print(PRNT_BLUE+'Segmentation training complete!'+PRNT_ENDC)

        cm.handle([
            '/magic/segmentation/deeplab/export-sys.sh',
            str(iterationNum)
        ])

        cm.handle([
            '/magic/segmentation/deeplab/archive.sh',
            str(iterationNum),
            str(name)
        ])
        hh.saveModel(name+'.model')
        print(PRNT_BLUE+'Segmentation model exported!'+PRNT_ENDC)
        
    def handle_class_prepare_train(self, body):#-----------------------------------
        cm = self.__cm
        cm.handle([
            '/magic/classification/prepare_data.sh'
        ])
        print(PRNT_BLUE+'Prepared classification data!'+PRNT_ENDC)

    def handle_class_train(self, body):#-------------------------------
        cm = self.__cm
        hh = self.__hh 
        name = 'classification_' + str(calendar.timegm(time.gmtime()))
        cm.handle([
            '/magic/classification/train_class.sh',
            name
        ]) 
        hh.saveModel(name+'.model')
        print(PRNT_BLUE+'Model trained'+PRNT_ENDC)

    # MODEL=$1 --- segModel
    # TARGET=$2 --- target
    # SAVE1=$3 further --- target+'str'
    # SAVE2=$4 detailed --- target+'str'
    def handle_seg_infer(self, segModel, target):#---------------------------------
        cm = self.__cm
        targetParts = target.split('.')
        cm.handle([
            '/magic/segmentation/deeplab/eval_one.sh',
            segModel,
            target,
            targetParts[0]+'_result_1'+'.'+targetParts[1],
            targetParts[0]+'_result_2'+'.'+targetParts[1]
        ])
        print(PRNT_BLUE+'Infering with the segmentation network'+PRNT_ENDC)

    # MODEL_NAME=$1 --- classModel
    # ANALYSIS_NAME=$2 --- target+'str'
    # RESULT_NAME=$3 --- target+'str'
    def handle_class_infer(self, classModel, target):#--------------------------------
        cm = self.__cm
        targetParts = target.split('.')
        cm.handle([
            '/magic/classification/eval_one.sh',
            classModel,
            targetParts[0]+'_result_1'+'.'+targetParts[1],
            targetParts[0]+'_result_3'
        ])
        print(PRNT_BLUE+'Infering with the classification network'+PRNT_ENDC)

    def handle_analyze(self, body):#----------------------------------
        hh = self.__hh
        if 'data' in body:
            data = body['data']
        else:
            raise Exception('No target provided')

        if 'classmodel' in body:
            classModel = body['classmodel']
        else:
            raise Exception('No classmodel provided')

        if 'segmodel' in body:
            segModel = body['segmodel']
        else:
            raise Exception('No segmodel provided')

        for target in data:
            self.handle_seg_infer(segModel, target)
            self.handle_class_infer(classModel, target)
            hh.saveAnalysis(target)
    
import json

BASE_PATH = "/magic/data/learning/segmentation"

class FileManager(object):
    def writeToDumbFiles(self, items):
        trainFile = open(BASE_PATH + "/sets/train.txt", 'w')
        valFile = open(BASE_PATH + "/sets/val.txt", 'w')
        trainValFile = open(BASE_PATH + "/sets/trainval.txt", 'w')

        for item in items:
            str = item +"\n"
            trainFile.write(str)
            valFile.write(str)
            trainValFile.write(str)

        trainFile.close()
        valFile.close()
        trainValFile.close()

    def writeInfoFile(self, info):
        dataFile = open("/magic/segmentation/deeplab/datasets/trainInfo.txt")
        json.dump(info, dataFile)
        dataFile.close()
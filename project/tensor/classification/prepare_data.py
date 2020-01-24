from os import listdir, makedirs
from os.path import isfile, join, exists
import sys
from PIL import Image
import shutil


if len(sys.argv) < 3:
    # Not enough arguments -- show usage information and exit
    print "Usage: " + sys.argv[0] + " indirectory outdirectory [border]"
    exit(1)

inDirectory = sys.argv[1]
outDirectory = sys.argv[2]

fullStructure = {}

IMG_SIZE = 326, 326

# pre-cleanup
for folder in listdir(outDirectory):
    innerFolder = join(outDirectory, folder)
    if exists(innerFolder):
        shutil.rmtree(innerFolder)

# copy and resize
for folder in listdir(inDirectory):
    innerFolder = join(inDirectory, folder)
    allFolderFiles = []
    for folderFile in listdir(innerFolder):
        fullFilePath = join(innerFolder, folderFile)
        if isfile(fullFilePath):
            imageFile = Image.open(fullFilePath)
            imageFile = imageFile.resize(IMG_SIZE, Image.ANTIALIAS)
            newFolder = join(outDirectory, folder)
            if not exists(newFolder):
                makedirs(newFolder)
            newPath = join(newFolder, folderFile)
            imageFile.save(newPath)


import urllib.request

PRNT_BLUE = '\033[94m'
PRNT_ENDC = '\033[0m'

URL_BASE = "http://uicore/internal/"
URL_SEGMENTATION_SAVE = "segmentation/save"

class HttpHandler(object):
    def saveSegmentationModel(self, fileName):
        fullPath = URL_BASE+URL_SEGMENTATION_SAVE+'/'+fileName
        response = urllib.request.urlopen(url = fullPath).read()
        print(response)
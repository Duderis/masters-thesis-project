import urllib.request

PRNT_BLUE = '\033[94m'
PRNT_ENDC = '\033[0m'

URL_BASE = "http://uicore/internal/"
URL_MODEL_SAVE = "model/save"
URL_ANALYSIS_SAVE = "analysis/save"

class HttpHandler(object):
    def saveModel(self, fileName):
        fullPath = URL_BASE+URL_MODEL_SAVE+'/'+fileName
        response = urllib.request.urlopen(url = fullPath).read()
        print(response)

    def saveAnalysis(self, target):
        fullPath = URL_BASE+URL_ANALYSIS_SAVE+'/'+target
        response = urllib.request.urlopen(url = fullPath).read()
        print(response)
import socket
import sys
import subprocess
import os
import json
from fileManager import FileManager
PORT = 11111

arg1 = sys.argv[1]

HOST = arg1 if arg1 else '127.0.0.1'

print('Starting server for neural nets')

prepareSegTrainCmd = 'echo'

fm = FileManager()

def handle_override(body):
    subprocess.run(body['command'])

def handle_seg_prepare_train(body):
    # create train;val;trainval files
    fm.writeToDumbFiles(body['data'])

    # modify 255 to 1.0
    subprocess.run([
        'python', 
        '/magic/segmentation/deeplab/datasets/label.py', 
        '/magic/segmentation/deeplab/datasets/SYS/'
        ])
    
    # generate actual tf records
    subprocess.run([
        '/magic/segmentation/deeplab/datasets/convert_sys.sh'
    ])
    
    # define dataset description
    fm.writeInfoFile(body['info'])

def handle_seg_infer(body):
    print('Unsupported message')
    # subprocess.run(messageSplit[1:])

def handle_messages(message):
    decodedMessage = json.loads(message)
    
    if decodedMessage['operation'] == 'prepare_seg_train':
        handle_seg_prepare_train(decodedMessage['body'])
    elif decodedMessage['operation'] == 'override':
        handle_override(decodedMessage['body'])
    elif decodedMessage['operation'] == 'infer':
        handle_seg_infer(decodedMessage['body'])
    else:
        print('Unsupported message')

        
with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as server:
    server.bind((HOST, PORT))
    print('Server listening on: ' + str(PORT))
    server.listen()
    while True:
        conn, addr = server.accept()
        data = conn.recv(10240)
        message = data.decode("utf-8")
        print('Connected by', addr)
        print('Received', message)

        handle_messages(message)

        conn.send(bytes("ok", encoding = 'utf-8'))
        conn.close()
            
                
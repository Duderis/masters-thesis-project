import socket
import sys
import os
import json
from requestHandler import RequestHandler
import traceback

PORT = 11111

arg1 = sys.argv[1]

HOST = arg1 if arg1 else '127.0.0.1'

PRNT_BLUE = '\033[94m'
PRNT_ENDC = '\033[0m'

print('Starting server for neural nets')

prepareSegTrainCmd = 'echo'
        
with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as server:
    server.bind((HOST, PORT))
    print(PRNT_BLUE+'Server listening on: ' + str(PORT) +PRNT_ENDC)
    server.listen()

    requestHandler = RequestHandler()

    while True:
        try:
            conn, addr = server.accept()
            data = conn.recv(10240)
            message = data.decode("utf-8")
            print(PRNT_BLUE+'Connected by'+PRNT_ENDC, addr)
            print(PRNT_BLUE+'Received'+PRNT_ENDC, message)

            requestHandler.handle_request(message)

            conn.send(bytes("ok", encoding = 'utf-8'))
            conn.close()
        except Exception as e:
            tb = traceback.format_exc()
            print(e)
            print(tb)
        
            
                
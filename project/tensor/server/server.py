import socket
import sys
import os
import json
from requestHandler import RequestHandler
PORT = 11111

arg1 = sys.argv[1]

HOST = arg1 if arg1 else '127.0.0.1'

print('Starting server for neural nets')

prepareSegTrainCmd = 'echo'
        
with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as server:
    server.bind((HOST, PORT))
    print('Server listening on: ' + str(PORT))
    server.listen()

    requestHandler = RequestHandler()

    while True:
        try:
            conn, addr = server.accept()
            data = conn.recv(10240)
            message = data.decode("utf-8")
            print('Connected by', addr)
            print('Received', message)

            requestHandler.handle_request(message)

            conn.send(bytes("ok", encoding = 'utf-8'))
            conn.close()
        except Exception as e:
            print(e)
        
            
                
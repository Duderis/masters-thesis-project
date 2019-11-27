import socket
import sys
import subprocess
import os

PORT = 11111

arg1 = sys.argv[1]

HOST = arg1 if arg1 else '127.0.0.1'

print('Starting server for neural nets')

command = os.environ['TENSOR_CMD_1'];

with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as server:
    server.bind((HOST, PORT))
    server.listen()
    while True:
        conn, addr = server.accept()
        data = conn.recv(1024);
        fileName = data.decode("utf-8")
        print('Connected by', addr)
        print('Received', fileName)
        subprocess.run([command, fileName])
        conn.send(bytes("ok", encoding = 'utf-8'))
        conn.close()
            
                
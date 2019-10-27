import socket
import sys

PORT = 11111

arg1 = sys.argv[1]

HOST = arg1 if arg1 else '127.0.0.1'

with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as server:
    server.bind((HOST, PORT))
    server.listen()
    while True:
        conn, addr = server.accept()
        print('Connected by', addr)
        conn.send(bytes("ok", encoding = 'utf-8'))
        conn.close()
            
                
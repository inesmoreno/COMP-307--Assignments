'''
Source: https://ruslanspivak.com/lsbaws-part1/
BASED ON CODE PROVIDED IN CLASS 

'''


import socket
import sys

def receive(client_connection):
    request_data = b''
    while True:
      new_data = client_connection.recv(4098)
      if (len(new_data) == 0):
        # client disconnected
        return None, None
      request_data += new_data
      if b'\r\n\r\n' in request_data:
        break

    parts = request_data.split(b'\r\n\r\n', 1)
    header = parts[0]
    body = parts[1]

    if b'Content-Length' in header:
      headers = header.split(b'\r\n')
      for h in headers:
        if h.startswith(b'Content-Length'):
          blen = int(h.split(b' ')[1]);
          break
    else:
        blen = 0

    while len(body) < blen:
      body += client_connection.recv(4098)

    print(header.decode('utf-8', 'replace'), flush=True)
    print('')
    print(body.decode('utf-8', 'replace'), flush=True)

    return header, body


if (len(sys.argv) != 4):
  print("Error. Wrong number of arguments.")

HOST = sys.argv[1]
PORT = int(sys.argv[2])
PATH = sys.argv[3].encode(encoding='UTF-8')

listen_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
listen_socket.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
listen_socket.bind((HOST, PORT))
listen_socket.listen(1)
print("Serving HTTP on port ",PORT,"...")

while True:
    client_connection, client_address = listen_socket.accept()
    header, body = receive(client_connection)

    if header is None or body is None:
        client_connection.close()
        continue

    # If using Firefox ask to switch browser
    if "Firefox" in header.decode(encoding='UTF-8'):
        http_response = """\
HTTP/1.1 200 OK
Content-Type: text/html; charset=UTF-8
Connection: keep-alive

<html>
<body>
<b>SECURITY ALERT!</b> Please switch to another browser. 

</body>
</html>

""".replace('\n','\r\n').encode('UTF-8')
        client_connection.sendall(http_response)
        client_connection.close()
        continue

    document = header.split(b' ')[1]

    try:
      if (document.split(b'.')[1] == b'jpg'):
        http_response = """\
HTTP/1.1 200 OK
Content-Type: image/jpeg
Connection: keep-alive

""".replace('\n','\r\n').encode('UTF-8')
        with open((PATH+document).decode(encoding='UTF-8'),'rb') as fh:
            http_response += fh.read()
        client_connection.sendall(http_response)
        client_connection.close()
        continue

      if (document.split(b'.')[1] == b'png'):
        http_response = """\
HTTP/1.1 200 OK
Content-Type: image/png
Connection: keep-alive

""".replace('\n','\r\n').encode('UTF-8')
        with open((PATH+document).decode(encoding='UTF-8'),'rb') as fh:
            http_response += fh.read()
        client_connection.sendall(http_response)
        client_connection.close()
        continue

      if (document.split(b'.')[1] == b'html'):
        http_response = """\
HTTP/1.1 200 OK
Content-Type: text/html; charset=UTF-8
Connection: keep-alive

""".replace('\n','\r\n').encode('UTF-8')
        with open((PATH+document).decode(encoding='UTF-8'),'rb') as fh:
            http_response += fh.read()
        client_connection.sendall(http_response)
        client_connection.close()
        continue

    #If any error occurs: file not found, display 404 page
    except: 
      http_response = """\
HTTP/1.1 404 Not Found
Content-Type: text/html; charset=UTF-8
Connection: keep-alive

<html>
<body>
<b>404</b> File Not Found.
</body>
</html>
""".replace('\n','\r\n').encode('UTF-8')
      client_connection.sendall(http_response)
      client_connection.close()
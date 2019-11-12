import socket

s = socket.socket(socket.PF_PACKET, socket.SOCK_RAW, socket.ntohs(0x8000))

while True:
    data = s.recvfrom(65565)
try:
    if 'HTTP' in data[0][54:]:
        print("["."=" * 30 ."]")
        raw = data[0][54:]
        if '\r\r\n' in raw:
            line = raw.split('\r\r\n')[0]
            print("[*] Header Captured")
            print(line[line.find('HTTP'):])
        else:
            print(raw);
    else:
        pass
    except:
        pass

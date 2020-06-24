import time
from datetime import datetime
from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import pause
import mimac_config as mc
import time_config as tc
import re

driver = webdriver.Chrome('chromedriver.exe')
driver_time = webdriver.Chrome('chromedriver.exe')

wait = WebDriverWait(driver, 10)
WebDriverWait(driver_time, 10)

driver.get(mc.url)
driver_time.get(tc.target_url)

login_form = driver.find_element_by_class_name("view_login").click()

id_elem = wait.until(EC.element_to_be_clickable((By.ID, "upperloginid")))
pass_elem = driver.find_element_by_id("upperloginpw")
id_elem.send_keys(mc.my_id)
pass_elem.send_keys(mc.my_pass)
login_elem = driver.find_element_by_id("upperloginBtn").click()

driver_time.find_element_by_id('msec_check').click()

while True:
    a = driver_time.find_element_by_id('time_area').text
    b = driver_time.find_element_by_id('msec_area').text
    
    time = re.findall("[0-9]+", a)

    if time[0]==mc.start_year and time[1]==mc.start_month and time[2]==mc.start_date and time[3]==mc.start_hour and time[4]==mc.start_min and time[5]=='00':
        msec = re.findall("[0-9]+", b)
        if int(msec[0])>=0:
            driver.get(mc.target_url)
            driver.find_element_by_xpath('//*[@id="daesungwrap"]/div[6]/div/div[4]/ul/li[3]/div/button').click()
            # 배성민 : //*[@id="daesungwrap"]/div[6]/div/div[4]/ul/li[3]/div/button
            # 박광일 : //*[@id="daesungwrap"]/div[6]/div/div[7]/ul/li[3]/div/div[2]/button
            break

driver.quit()
driver_time.quit()
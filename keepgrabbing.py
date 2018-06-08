import time
import os
import sys
from selenium import webdriver

url=sys.argv[1]
driver = webdriver.Chrome(os.getcwd() + '/chromedriver')  # Optional argument, if not specified will search path.
# driver.get('http://qpublic9.qpublic.net/qpmap4/map.php?county=hi_hawaii&parcel=130280050000&extent=-17242825+2210252+-17242297+2210708&layers=parcels+roads+parcel_sales+streetnum');
driver.get(url)
# let the JS load and stuff. slow down, this ain't da mainland
time.sleep(3) 
# dl_link = driver.find_element_by_css_selector('#reports')
dl_link = driver.find_element_by_css_selector('#reports .x-panel-body #parcel .x-panel-bwrap .x-panel-body-noborder p a')

output = dl_link.get_attribute('href')
# quit then print, otherwise PHP call keeps Chrome windows open :,(
driver.quit()
print(output)

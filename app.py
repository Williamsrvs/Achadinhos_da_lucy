<<<<<<< HEAD
import time
import hmac
import hashlib
import requests

partner_id = "18308720825"
partner_key = "NHCYEOE7UKE3R65R7OHNQNUONMOOUB5A"
shop_id = "18308720825"
access_token = "NHCYEOE7UKE3R65R7OHNQNUONMOOUB5A"

timestamp = int(time.time())
path = "/api/v2/order/get_order_list"
base_url = "https://partner.shopeemobile.com"

# Gerar assinatura
base_string = f"{partner_id}{path}{timestamp}{access_token}{shop_id}"
sign = hmac.new(
    partner_key.encode(),
    base_string.encode(),
    hashlib.sha256
).hexdigest()

url = f"{base_url}{path}"
params = {
    "partner_id": partner_id,
    "timestamp": timestamp,
    "access_token": access_token,
    "shop_id": shop_id,
    "sign": sign,
    "time_range_field": "create_time",
    "time_from": timestamp - 86400,
    "time_to": timestamp,
    "page_size": 20
}

response = requests.get(url, params=params)
=======
import time
import hmac
import hashlib
import requests

partner_id = "18308720825"
partner_key = "NHCYEOE7UKE3R65R7OHNQNUONMOOUB5A"
shop_id = "18308720825"
access_token = "NHCYEOE7UKE3R65R7OHNQNUONMOOUB5A"

timestamp = int(time.time())
path = "/api/v2/order/get_order_list"
base_url = "https://partner.shopeemobile.com"

# Gerar assinatura
base_string = f"{partner_id}{path}{timestamp}{access_token}{shop_id}"
sign = hmac.new(
    partner_key.encode(),
    base_string.encode(),
    hashlib.sha256
).hexdigest()

url = f"{base_url}{path}"
params = {
    "partner_id": partner_id,
    "timestamp": timestamp,
    "access_token": access_token,
    "shop_id": shop_id,
    "sign": sign,
    "time_range_field": "create_time",
    "time_from": timestamp - 86400,
    "time_to": timestamp,
    "page_size": 20
}

response = requests.get(url, params=params)
>>>>>>> 05c17e974ed82686ed1612707809daf60d2c183e
print(response.json())
Below is a **clean, developer-friendly GitHub README.md** version of the API documentation.

You can paste this directly into a GitHub repo.

---

# 🏕️ Get Camp Details API

This API retrieves detailed information about a specific camp inside the portal.
It is used for brochure creation, camp listing, and internal operations.

---

## 📌 Endpoint

**GET**

```
http://31.220.55.121/portal/ajax_get_camp_details.php
```

### Query Parameters

| Name      | Type | Required | Description                               |
| --------- | ---- | -------- | ----------------------------------------- |
| `camp_id` | int  | Yes      | ID of the camp whose details are required |

**Example**

```
?camp_id=5448
```

---

## 🔐 Authentication

The API requires an active PHP session.

### Required Cookie

| Cookie      | Description                          |
| ----------- | ------------------------------------ |
| `PHPSESSID` | Valid session ID of a logged-in user |

If this cookie is missing or expired → API may return empty data.

---

## 📥 Required Headers

These are typically auto-applied by browser AJAX calls.

| Header             | Value / Purpose                                      |
| ------------------ | ---------------------------------------------------- |
| `X-Requested-With` | `XMLHttpRequest` to identify AJAX request            |
| `Referer`          | Must come from portal page (ex: create_brochure.php) |
| `User-Agent`       | Any browser UA                                       |
| `Accept`           | `*/*`                                                |

---

## 📤 Sample cURL Request

```bash
curl 'http://31.220.55.121/portal/ajax_get_camp_details.php?camp_id=5448' \
  -H 'X-Requested-With: XMLHttpRequest' \
  -b 'PHPSESSID=0465fd7958c1180294887604ea13b39c' \
  --insecure
```

---

## 📦 Response Format

### Top-level Structure

```json
{
  "status": 1,
  "msg": "",
  "data": { ... }
}
```

### Response Field Description

| Field    | Type   | Description                        |
| -------- | ------ | ---------------------------------- |
| `status` | number | `1` = success, `0` = error         |
| `msg`    | string | Optional message, empty on success |
| `data`   | object | Detailed camp info                 |

---

## 🧩 `data` Object Structure

| Field           | Type             | Description                |
| --------------- | ---------------- | -------------------------- |
| `state`         | string           | State (e.g., QLD, NSW)     |
| `sports`        | string           | Sport category             |
| `is_partner`    | string (`0`/`1`) | Indicates partner venue    |
| `suburb`        | string           | Suburb                     |
| `dates`         | string           | Human-readable camp dates  |
| `venue_name`    | string           | Full name of venue         |
| `venue_address` | string           | Address (may contain `\n`) |

---

## 📘 Example Response

```json
{
  "status": 1,
  "msg": "",
  "data": {
    "state": "QLD",
    "sports": "Cricket",
    "is_partner": "0",
    "suburb": "Macgregor",
    "dates": "January 19th, 20th & 21st",
    "venue_name": "Macgregor Cricket Club, Macgregor, QLD - Sum 2025 #Week 3 January",
    "venue_address": "162 Granadilla Street,\nMacgregor, QLD."
  }
}
```

---

## ❗ Error Handling

| Condition                 | Output                   | Meaning            |
| ------------------------- | ------------------------ | ------------------ |
| Missing/invalid PHPSESSID | `status:0` or empty data | Session expired    |
| Missing `camp_id`         | 400 or `status:0`        | Invalid request    |
| Server error              | 500                      | Internal PHP error |

---

## 📝 Notes

* The API is **HTTP** and requires `--insecure` for cURL because the SSL certificate is not valid.
* `is_partner` is returned as a **string**, not boolean.
* `venue_address` may include newlines; handle formatting appropriately.
* Ideal for brochure generation, internal dashboards, and content automation.

---

If you want, I can also create:

✨ **OpenAPI/Swagger YAML**
✨ **Postman Collection JSON**
✨ **API folder structure for GitHub**

Just tell me!

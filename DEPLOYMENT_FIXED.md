# 🚀 Awardspace Deployment Guide - FIXED VERSION

## 📁 **Upload These Files to Your Awardspace Domain Root**

Upload these files directly to your domain root (not in a backend folder):

```
yourdomain.com/
├── autoload.php                     # ✅ FIXED - Enhanced autoloader
├── debug-error.php                  # ✅ NEW - Debug tool
├── simple-test.php                  # ✅ NEW - Basic test
├── db-test-simple.php              # ✅ NEW - Database test
├── .htaccess                       # Apache config
├── api/
│   ├── graphql-real.php            # Main GraphQL endpoint
│   ├── graphql-simple-test.php     # ✅ NEW - Simple test endpoint
│   └── test-db.php                 # ✅ FIXED - Database test
├── src/
│   ├── Config/
│   │   └── DatabaseSimple.php      # Database connection
│   ├── GraphQL/
│   │   └── SimpleGraphQL.php       # GraphQL engine
│   └── Repositories/
│       ├── ProductRepository.php
│       ├── CategoryRepository.php
│       └── OrderRepository.php
```

## 🧪 **Testing Steps (Do In This Exact Order!)**

### **Step 1: Test Basic PHP** ⭐ START HERE

**URL:** `https://yourdomain.com/simple-test.php`

**What You Should See:**

```
Simple Test Results
Hello from Awardspace!
PHP Version: 8.x
Current directory: /home/...
Files in root directory:
- autoload.php
- api
- src
- ...
```

**If This Fails:** Your files aren't uploaded correctly.

---

### **Step 2: Test Database Connection**

**URL:** `https://yourdomain.com/db-test-simple.php`

**What You Should See:**

```
✅ Database Connection Successful!
Tables in database:
• products
• categories
• orders
Sample products:
• product-1: Sample Product
```

**If This Fails:** Database credentials are wrong or Awardspace DB is down.

---

### **Step 3: Test Debug Info**

**URL:** `https://yourdomain.com/debug-error.php`

**What You Should See:**

```
✅ PHP is working
✅ File exists: autoload.php
✅ File exists: src/Config/DatabaseSimple.php
✅ Autoloader loaded successfully
✅ Database connection successful
```

**If This Fails:** Files are missing or autoloader is broken.

---

### **Step 4: Test Simple GraphQL**

**URL:** `https://yourdomain.com/api/graphql-simple-test.php`

**What You Should See:**

```json
{
  "errors": [
    {
      "message": "No input data received"
    }
  ]
}
```

**This Error is GOOD!** It means the endpoint is working but needs POST data.

---

### **Step 5: Test GraphQL with Real Data**

Use this command (replace `yourdomain.com` with your actual domain):

```bash
curl -X POST https://yourdomain.com/api/graphql-simple-test.php \
  -H "Content-Type: application/json" \
  -d '{"query":"query { categories { name } }"}'
```

**What You Should See:**

```json
{
  "data": {
    "categories": [{ "name": "clothes" }, { "name": "tech" }, { "name": "all" }]
  }
}
```

---

## 🔥 **Common Issues & Fixes**

### **500 Internal Server Error**

1. Check file permissions: 755 for folders, 644 for files
2. Check `debug-error.php` for missing files
3. Look at Awardspace error logs

### **Database Connection Failed**

1. Verify hostname: `fdb1033.awardspace.net`
2. Check if database is active in Awardspace control panel
3. Verify credentials in `DatabaseSimple.php`

### **Class Not Found**

1. Make sure `autoload.php` is in root directory
2. Check all `src/` files are uploaded
3. Verify namespace matches folder structure

### **CORS Errors (Frontend)**

1. Make sure `.htaccess` is uploaded
2. Check CORS headers in GraphQL endpoint

---

## 🎯 **Final Step: Update Your React App**

Once all tests pass, update your React app to use your live domain:

```javascript
// In src/utils/graphql.js
const response = await fetch("https://YOURDOMAIN.com/api/graphql-real.php", {
  method: "POST",
  headers: {
    "Content-Type": "application/json",
  },
  body: JSON.stringify({ query, variables }),
});
```

Replace `YOURDOMAIN.com` with your actual Awardspace domain.

---

## ✅ **Success Checklist**

- [ ] `simple-test.php` shows PHP version and files
- [ ] `db-test-simple.php` shows database tables
- [ ] `debug-error.php` shows all green checkmarks
- [ ] `graphql-simple-test.php` returns valid JSON
- [ ] GraphQL curl command returns category data
- [ ] React app updated with live domain
- [ ] Frontend can fetch data from live GraphQL endpoint

**When all boxes are checked, your deployment is complete!** 🎉

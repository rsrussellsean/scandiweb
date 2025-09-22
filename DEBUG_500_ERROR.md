# 🚨 500 Error Debugging Guide

## 🔍 **Step-by-Step Testing (Do in This Exact Order!)**

Upload all these files to your Awardspace domain and test each one:

### **Step 1: Ultra Basic Test**

**URL:** `https://yourdomain.com/ultra-simple-test.php`

**If this fails:** Your PHP is broken or files aren't uploaded correctly.

---

### **Step 2: Comprehensive Debug Test**

**URL:** `https://yourdomain.com/error-debug-500.php`

**This will show you exactly what's wrong!**

Look for:

- ❌ Missing files
- ❌ Permission errors
- ❌ Parse errors in PHP code
- ❌ Class loading issues

---

### **Step 3: Minimal GraphQL Test**

**URL:** `https://yourdomain.com/api/graphql-minimal-test.php`

**Should return:** JSON with "Minimal GraphQL endpoint working!"

**If this works but main GraphQL doesn't:** The issue is in your GraphQL classes.

---

### **Step 4: Test Simple Autoloader**

Create a test file that uses the simple autoloader:

```php
<?php
// test-simple-autoload.php
require_once 'autoload-simple.php';

try {
    $db = App\Config\DatabaseSimple::connect();
    echo "✅ Database connection successful with simple autoloader!";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
```

---

## 🔧 **Common 500 Error Causes & Fixes**

### **1. Parse Errors in PHP Files**

**Fix:** Check `error-debug-500.php` for syntax errors

### **2. Missing Files**

**Fix:** Make sure all files from your local project are uploaded:

- `autoload.php`
- `src/Config/DatabaseSimple.php`
- `src/GraphQL/SimpleGraphQL.php`
- All repository files

### **3. Wrong File Permissions**

**Fix:** Set permissions:

- Folders: 755
- PHP files: 644

### **4. Class Loading Issues**

**Fix:** Use the `autoload-simple.php` instead of the complex one

### **5. Database Connection Issues**

**Fix:** The database error might be causing a fatal error. Check if your Awardspace database is active.

---

## 🎯 **Quick Fix Steps**

1. **Upload `ultra-simple-test.php`** - Test basic PHP
2. **Upload `error-debug-500.php`** - See exact error
3. **Replace `autoload.php` with `autoload-simple.php`**
4. **Upload `api/graphql-minimal-test.php`** - Test basic GraphQL
5. **Fix whatever the debug tool shows you**

---

## 📞 **Report Back**

Test each file and tell me:

1. What does `ultra-simple-test.php` show?
2. What does `error-debug-500.php` show?
3. What does `api/graphql-minimal-test.php` show?

**This will tell us exactly what's causing your 500 error!** 🎯

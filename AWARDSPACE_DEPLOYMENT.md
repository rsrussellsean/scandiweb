# 🚀 Awardspace Deployment Guide

## 📁 **Files to Upload to Awardspace**

Upload your entire `backend` folder to your Awardspace hosting account:

### **Required Backend Files:**

```
backend/
├── api/
│   ├── graphql-real.php          # Main GraphQL endpoint
│   ├── test-db.php              # Database test
│   └── products.php             # Legacy endpoints (if needed)
├── src/
│   ├── Config/
│   │   └── DatabaseSimple.php   # Database connection
│   ├── GraphQL/
│   │   └── SimpleGraphQL.php    # GraphQL engine
│   ├── Repositories/
│   │   ├── ProductRepository.php
│   │   ├── CategoryRepository.php
│   │   └── OrderRepository.php
│   └── Models/
│       └── (all model files)
├── autoload.php                 # Class autoloader
└── .htaccess                   # Apache configuration
```

## 🔧 **Step-by-Step Deployment:**

### **1. Upload Backend Files**

- Use FTP client (FileZilla) or Awardspace File Manager
- Upload the entire `backend` folder to your domain root
- Your structure should be: `yourdomain.com/backend/api/graphql-real.php`

### **2. Create .htaccess File**

Create `.htaccess` in your backend folder:

```apache
# Enable CORS for GraphQL API
<IfModule mod_headers.c>
    Header always set Access-Control-Allow-Origin "*"
    Header always set Access-Control-Allow-Methods "POST, GET, OPTIONS"
    Header always set Access-Control-Allow-Headers "Content-Type, Authorization"
</IfModule>

# Handle preflight requests
RewriteEngine On
RewriteCond %{REQUEST_METHOD} OPTIONS
RewriteRule ^(.*)$ $1 [R=200,L]

# Enable error reporting for debugging
php_flag display_errors on
php_flag log_errors on
```

### **3. Test Database Connection**

Visit: `https://yourdomain.com/backend/api/test-db.php`

You should see:

- ✅ Database connection: SUCCESS
- Sample categories and products data

### **4. Test GraphQL Endpoint**

Visit: `https://yourdomain.com/backend/api/graphql-real.php`

You should see:

- `{"errors":[{"message":"Invalid GraphQL request"}]}` (normal for GET request)

### **5. Update Frontend Configuration**

In your React app, update `src/utils/graphql.js`:

```javascript
// Change from localhost to your Awardspace domain
const response = await fetch(
  "https://yourdomain.com/backend/api/graphql-real.php",
  {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      query,
      variables,
    }),
  }
);
```

## 🧪 **Testing Your Deployment**

### **Test 1: Database Connection**

```bash
curl https://yourdomain.com/backend/api/test-db.php
```

### **Test 2: GraphQL Categories**

```bash
curl -X POST https://yourdomain.com/backend/api/graphql-real.php \
  -H "Content-Type: application/json" \
  -d '{"query":"query { categories { name } }"}'
```

### **Test 3: GraphQL Products**

```bash
curl -X POST https://yourdomain.com/backend/api/graphql-real.php \
  -H "Content-Type: application/json" \
  -d '{"query":"query { products { id name } }"}'
```

## 🔒 **Security Considerations**

### **For Production:**

1. **Hide sensitive files:**

   ```apache
   # In .htaccess
   <Files "*.php">
       Order Allow,Deny
       Deny from all
   </Files>

   <Files "test-db.php">
       Order Allow,Deny
       Deny from all
   </Files>
   ```

2. **Use environment variables:**

   ```php
   // Instead of hardcoded credentials
   $host = $_ENV['DB_HOST'] ?? 'fdb1033.awardspace.net';
   $dbname = $_ENV['DB_NAME'] ?? '4652023_ecommerce';
   ```

3. **Remove test files** after deployment works

## 🎯 **Final Architecture**

```
Frontend (React) → Awardspace Server → Awardspace Database
     ↓                    ↓                    ↓
localhost:5174    yourdomain.com/backend    MySQL DB
```

**Once deployed, your entire stack runs on Awardspace with direct database access!**

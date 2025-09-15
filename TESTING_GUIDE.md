# GraphQL Testing Guide for localhost:8000

## Prerequisites
Make sure you have:
1. PHP server running: `cd "c:\Users\russell.s.gonzalve\Documents\scandiweb_revision\scandiweb\backend"; C:\xampp\php\php.exe -S localhost:8000`
2. Your database credentials updated in `DatabaseSimple.php`

## Step 1: Update Database Credentials

Edit `backend/src/Config/DatabaseSimple.php` and replace these lines with your actual Awardspace credentials:

```php
$host = 'your-awardspace-host.awardspace.net';     // e.g., fdb1030.awardspace.net
$dbname = 'your_database_name';                    // e.g., 4368892_scandiweb
$username = 'your_username';                       // e.g., 4368892_scandiweb
$password = 'your_password';                       // Your database password
```

## Step 2: Test Database Connection

Visit: `http://localhost:8000/api/test-connection.php`

Expected result: Should show "✅ Database connection successful!" and count of products/categories.

## Step 3: Test GraphQL Endpoints

### Test in Browser Console
Open your browser's developer console and run these tests:

#### Test Categories:
```javascript
fetch('http://localhost:8000/api/graphql-simple.php', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    query: 'query GetCategories { categories { name } }'
  })
})
.then(res => res.json())
.then(data => console.log('Categories:', data))
.catch(err => console.error('Error:', err));
```

#### Test Products:
```javascript
fetch('http://localhost:8000/api/graphql-simple.php', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    query: 'query GetProducts { products { id name inStock category brand } }'
  })
})
.then(res => res.json())
.then(data => console.log('Products:', data))
.catch(err => console.error('Error:', err));
```

#### Test Single Product:
```javascript
fetch('http://localhost:8000/api/graphql-simple.php', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    query: 'query GetProduct($id: ID!) { product(id: $id) { id name description } }',
    variables: { id: 'your-product-id' }  // Replace with actual product ID
  })
})
.then(res => res.json())
.then(data => console.log('Single Product:', data))
.catch(err => console.error('Error:', err));
```

## Step 4: Test Your React App

1. Start your React app: `npm run dev`
2. Open your app in browser
3. Check browser DevTools → Network tab
4. Navigate through your app and verify:
   - Navbar loads categories via GraphQL
   - Product list loads products via GraphQL
   - Product details load via GraphQL
   - Cart checkout uses GraphQL

## Expected Network Requests:
- Method: POST
- URL: `http://localhost:8000/api/graphql-simple.php`
- Request Body: JSON with `query` and `variables`
- Response: JSON with `data` field

## Troubleshooting:

### Error: "Database connection failed"
- Check your database credentials in `DatabaseSimple.php`
- Verify your Awardspace database is accessible
- Test connection at: `http://localhost:8000/api/test-connection.php`

### Error: "Unable to connect to the remote server"
- Make sure PHP server is running on localhost:8000
- Check if port 8000 is available
- Try accessing: `http://localhost:8000/api/graphql-simple.php` directly in browser

### Error: "Unknown GraphQL query"
- Check the query syntax
- Make sure you're sending valid GraphQL queries

### React App Issues:
- Check browser console for JavaScript errors
- Verify CORS headers are working
- Check that frontend is making requests to the correct GraphQL endpoint

## Success Indicators:
✅ Database connection test passes
✅ GraphQL queries return data in expected format
✅ React app loads without errors
✅ Network tab shows POST requests to GraphQL endpoint
✅ All functionality works the same as before

## File Structure:
```
backend/
├── api/
│   ├── graphql-simple.php        # Main GraphQL endpoint
│   └── test-connection.php       # Database test
└── src/
    ├── Config/
    │   └── DatabaseSimple.php    # Database connection
    └── Repositories/
        ├── CategoryRepositorySimple.php
        └── ProductRepositorySimple.php
```

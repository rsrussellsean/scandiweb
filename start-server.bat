@echo off
echo Starting GraphQL-enabled development server...
echo.
echo Server will be available at: http://localhost:8000
echo GraphQL endpoint: http://localhost:8000/backend/api/graphql.php
echo Database connection test: http://localhost:8000/backend/api/test-connection.php
echo GraphQL test page: http://localhost:8000/test-graphql.html
echo.
echo Press Ctrl+C to stop the server
echo.
cd /d "c:\Users\russell.s.gonzalve\Documents\scandiweb_revision\scandi2\scandiweb"
php -S localhost:8000

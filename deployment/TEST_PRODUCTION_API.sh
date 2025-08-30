#!/bin/bash
# Test commands to verify your API is working in production mode

echo "=== API Testing Commands for EC2 ==="
echo ""

echo "1. Test basic API endpoint:"
echo "   curl -i http://13.203.76.188/api/test"
echo ""

echo "2. Test authentication with correct credentials:"
echo "   curl -X POST http://13.203.76.188/api/auth/login \\"
echo "     -H 'Content-Type: application/json' \\"
echo "     -d '{\"username\":\"admin\",\"password\":\"admin123\"}'"
echo ""

echo "3. Test with demo user (if created):"
echo "   curl -X POST http://13.203.76.188/api/auth/login \\"
echo "     -H 'Content-Type: application/json' \\"
echo "     -d '{\"username\":\"demo\",\"password\":\"demo123\"}'"
echo ""

echo "4. Test admin login:"
echo "   curl -X POST http://13.203.76.188/api/auth/admin/login \\"
echo "     -H 'Content-Type: application/json' \\"
echo "     -d '{\"username\":\"admin\",\"password\":\"admin123\"}'"
echo ""

echo "5. Check server environment:"
echo "   curl -i http://13.203.76.188/ | head -20"
echo ""

echo "=== What You Should See ==="
echo "✓ JSON responses instead of HTML"
echo "✓ Proper error messages like: {\"error\":\"Invalid username or password\"}"
echo "✓ No Vite development scripts in responses"
echo "✓ 401/400 status codes for invalid credentials (not HTML pages)"
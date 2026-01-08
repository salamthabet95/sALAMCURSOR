#!/bin/bash

# Test Deploy Script
# Usage: ./test-deploy.sh [domain]

DOMAIN=${1:-"yourdomain.com"}
BASE_URL="https://${DOMAIN}"

echo "🔍 Testing deployment for: ${DOMAIN}"
echo "=================================="
echo ""

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Test 1: Main page
echo -n "Testing main page... "
STATUS=$(curl -s -o /dev/null -w "%{http_code}" "${BASE_URL}/")
if [ "$STATUS" = "200" ]; then
    echo -e "${GREEN}✅ OK${NC} (Status: $STATUS)"
else
    echo -e "${RED}❌ FAILED${NC} (Status: $STATUS)"
fi

# Test 2: Test page
echo -n "Testing test.html... "
STATUS=$(curl -s -o /dev/null -w "%{http_code}" "${BASE_URL}/test.html")
if [ "$STATUS" = "200" ]; then
    echo -e "${GREEN}✅ OK${NC} (Status: $STATUS)"
else
    echo -e "${RED}❌ FAILED${NC} (Status: $STATUS)"
fi

# Test 3: API test connection
echo -n "Testing API... "
STATUS=$(curl -s -o /dev/null -w "%{http_code}" "${BASE_URL}/api/test-connection.php")
if [ "$STATUS" = "200" ]; then
    echo -e "${GREEN}✅ OK${NC} (Status: $STATUS)"
    # Get API response
    RESPONSE=$(curl -s "${BASE_URL}/api/test-connection.php")
    echo "   API Response: $(echo $RESPONSE | jq -r '.timestamp' 2>/dev/null || echo 'N/A')"
else
    echo -e "${RED}❌ FAILED${NC} (Status: $STATUS)"
fi

# Test 4: Prayer times API
echo -n "Testing Prayer Times API... "
STATUS=$(curl -s -o /dev/null -w "%{http_code}" "${BASE_URL}/api/prayer-times.php?city=رام%20الله")
if [ "$STATUS" = "200" ]; then
    echo -e "${GREEN}✅ OK${NC} (Status: $STATUS)"
else
    echo -e "${RED}❌ FAILED${NC} (Status: $STATUS)"
fi

# Test 5: Assets (CSS)
echo -n "Testing CSS assets... "
STATUS=$(curl -s -o /dev/null -w "%{http_code}" "${BASE_URL}/assets/css/main.css")
if [ "$STATUS" = "200" ]; then
    echo -e "${GREEN}✅ OK${NC} (Status: $STATUS)"
else
    echo -e "${YELLOW}⚠️  WARNING${NC} (Status: $STATUS) - CSS might not exist"
fi

# Test 6: Assets (JS)
echo -n "Testing JS assets... "
STATUS=$(curl -s -o /dev/null -w "%{http_code}" "${BASE_URL}/assets/js/main.js")
if [ "$STATUS" = "200" ]; then
    echo -e "${GREEN}✅ OK${NC} (Status: $STATUS)"
else
    echo -e "${YELLOW}⚠️  WARNING${NC} (Status: $STATUS) - JS might not exist"
fi

echo ""
echo "=================================="
echo "✅ Testing complete!"
echo ""
echo "To test manually, visit:"
echo "  - Main: ${BASE_URL}/"
echo "  - Test: ${BASE_URL}/test.html"
echo "  - API: ${BASE_URL}/api/test-connection.php"

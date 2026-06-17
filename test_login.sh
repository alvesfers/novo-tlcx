#!/bin/bash

COOKIE_JAR="/tmp/cookies.txt"
BASE_URL="http://127.0.0.1:8000"

echo "1. Acessando página de login..."
curl -s -c $COOKIE_JAR "$BASE_URL/signin" > /tmp/login_page.html

# Extrair CSRF token
CSRF_TOKEN=$(grep -oP 'csrf-token" content="\K[^"]+' /tmp/login_page.html)
echo "CSRF Token encontrado: ${CSRF_TOKEN:0:20}..."

echo ""
echo "2. Fazendo login..."
curl -s -b $COOKIE_JAR -c $COOKIE_JAR -X POST "$BASE_URL/signin" \
  -d "email=admin@tlc.local&password=password&_token=$CSRF_TOKEN" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -H "Referer: $BASE_URL/signin" \
  -L > /tmp/redirect.html

echo "Login feito. Verificando..."

echo ""
echo "3. Acessando página de dirigentes..."
curl -s -b $COOKIE_JAR "$BASE_URL/dirigentes" | head -100 > /tmp/dirigentes.html

if grep -q "x-data\|x-table-enhanced\|table-enhanced" /tmp/dirigentes.html; then
  echo "✓ Componente x-table-enhanced detectado!"
else
  echo "✗ Componente x-table-enhanced NÃO encontrado"
fi

if grep -q "Dirigentes" /tmp/dirigentes.html; then
  echo "✓ Página de Dirigentes carregou"
else
  echo "✗ Página de Dirigentes não carregou"
fi

echo ""
echo "Primeiras 50 linhas do HTML:"
head -50 /tmp/dirigentes.html

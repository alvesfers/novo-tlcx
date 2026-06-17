const http = require('http');
const https = require('https');
const { URL } = require('url');

const BASE_URL = 'http://127.0.0.1:8000';

function request(method, path, body = null) {
  return new Promise((resolve, reject) => {
    const url = new URL(path, BASE_URL);
    const options = {
      method,
      headers: {
        'User-Agent': 'Mozilla/5.0',
        'Accept': 'text/html,application/xhtml+xml'
      },
      hostname: url.hostname,
      port: url.port,
      path: url.pathname + url.search
    };

    const req = http.request(options, (res) => {
      let data = '';
      res.on('data', chunk => data += chunk);
      res.on('end', () => resolve({ status: res.statusCode, headers: res.headers, body: data }));
    });

    req.on('error', reject);
    if (body) req.write(body);
    req.end();
  });
}

async function test() {
  console.log('Testando se servidor está respondendo...');
  try {
    const signin = await request('GET', '/signin');
    console.log('✓ Servidor respondendo (status ' + signin.status + ')');

    // Verificar se a página contém Alpine.js
    if (signin.body.includes('Alpine')) {
      console.log('✓ Alpine.js carregado na página');
    }

    console.log('✓ Teste concluído - servidor está funcionando!');
  } catch (e) {
    console.error('✗ Erro:', e.message);
  }
}

test();

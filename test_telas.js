const { chromium } = require('playwright');

(async () => {
  const browser = await chromium.launch();
  const page = await browser.newPage();
  
  try {
    // Login
    console.log('1. Acessando página de login...');
    await page.goto('http://127.0.0.1:8000/signin');
    
    // Preencher formulário de login
    console.log('2. Fazendo login...');
    await page.fill('input[name="email"]', 'admin@tlc.local');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    
    // Aguardar redirecionamento
    await page.waitForNavigation({ timeout: 10000 });
    console.log('3. Login realizado');
    
    // Acessar página de dioceses
    console.log('4. Acessando página de dioceses...');
    await page.goto('http://127.0.0.1:8000/dioceses');
    
    // Verificar se a tabela está visível
    const diocesesExistem = await page.locator('table tbody tr').count();
    console.log(`5. Dioceses encontradas na tabela: ${diocesesExistem}`);
    
    if (diocesesExistem > 0) {
      // Clicar na primeira diocese para ver os detalhes
      console.log('6. Clicando na primeira diocese...');
      const primeiroNome = await page.locator('table tbody tr:first-child td:first-child a');
      const href = await primeiroNome.getAttribute('href');
      console.log(`7. URL da diocese: ${href}`);
      
      await primeiroNome.click();
      await page.waitForLoadState('networkidle');
      
      // Verificar se a página de detalhes carregou
      const titulo = await page.locator('h1').first();
      const textoTitulo = await titulo.textContent();
      console.log(`8. Título da página de detalhes: ${textoTitulo}`);
      
      // Verificar se os elementos de detalhes estão presentes
      const temEmail = await page.locator('text=/Email/').count();
      const temStatus = await page.locator('text=/Status/').count();
      const temEstatisticas = await page.locator('h2:has-text("Estatísticas")').count();
      
      console.log(`9. Email presente: ${temEmail > 0}`);
      console.log(`10. Status presente: ${temStatus > 0}`);
      console.log(`11. Estatísticas presentes: ${temEstatisticas > 0}`);
      
      // Screenshot
      await page.screenshot({ path: 'dioceses_show.png' });
      console.log('12. Screenshot da página de dioceses: dioceses_show.png');
      
      // Voltar para dioceses
      await page.goto('http://127.0.0.1:8000/dioceses');
      
      // Acessar página de núcleos
      console.log('13. Acessando página de núcleos...');
      await page.goto('http://127.0.0.1:8000/nucleos');
      
      const nucleosExistem = await page.locator('table tbody tr').count();
      console.log(`14. Núcleos encontrados: ${nucleosExistem}`);
      
      if (nucleosExistem > 0) {
        await page.locator('table tbody tr:first-child td:first-child a').click();
        await page.waitForLoadState('networkidle');
        
        const tituloNucleo = await page.locator('h1').first().textContent();
        console.log(`15. Título da página de núcleo: ${tituloNucleo}`);
        
        await page.screenshot({ path: 'nucleos_show.png' });
        console.log('16. Screenshot da página de núcleo: nucleos_show.png');
      }
      
      // Acessar página de secretarias
      console.log('17. Acessando página de secretarias...');
      await page.goto('http://127.0.0.1:8000/secretarias');
      
      const secretariasExistem = await page.locator('table tbody tr').count();
      console.log(`18. Secretarias encontradas: ${secretariasExistem}`);
      
      if (secretariasExistem > 0) {
        await page.locator('table tbody tr:first-child td:first-child a').click();
        await page.waitForLoadState('networkidle');
        
        const tituloSecretaria = await page.locator('h1').first().textContent();
        console.log(`19. Título da página de secretaria: ${tituloSecretaria}`);
        
        await page.screenshot({ path: 'secretarias_show.png' });
        console.log('20. Screenshot da página de secretaria: secretarias_show.png');
      }
    }
    
    console.log('\n✅ TODOS OS TESTES COMPLETADOS COM SUCESSO');
    
  } catch (error) {
    console.error('\n❌ ERRO:', error.message);
    await page.screenshot({ path: 'error.png' });
    process.exit(1);
  } finally {
    await browser.close();
  }
})();

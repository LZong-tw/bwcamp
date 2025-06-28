/**
 * Laravel E2E Testing Helpers
 */

/**
 * Login as a specific user type
 * @param {Page} page - Playwright page object
 * @param {string} userType - admin, manager, user
 */
export async function loginAs(page, userType = 'admin') {
  const credentials = {
    admin: { email: 'admin@test.com', password: 'password' },
    manager: { email: 'manager@test.com', password: 'password' },
    user: { email: 'user@test.com', password: 'password' }
  };

  const cred = credentials[userType];
  
  await page.goto('/login');
  await page.fill('input[name="email"]', cred.email);
  await page.fill('input[name="password"]', cred.password);
  await page.click('button[type="submit"]');
  
  // Wait for redirect after login
  await page.waitForURL(/\/(?!login)/);
}

/**
 * Clear Laravel cache and sessions
 * @param {Page} page - Playwright page object
 */
export async function clearLaravelCache(page) {
  // This would need to be implemented based on your cache clearing endpoint
  // or you might want to use artisan commands before tests
}

/**
 * Wait for Livewire component to load
 * @param {Page} page - Playwright page object
 * @param {string} component - Livewire component name
 */
export async function waitForLivewire(page, component = null) {
  // Wait for Livewire to initialize
  await page.waitForFunction(() => window.Livewire !== undefined);
  
  if (component) {
    await page.waitForSelector(`[wire\\:id]`, { state: 'attached' });
  }
}

/**
 * Fill a form and handle CSRF token
 * @param {Page} page - Playwright page object
 * @param {Object} formData - Key-value pairs of form fields
 * @param {string} formSelector - CSS selector for the form
 */
export async function fillLaravelForm(page, formData, formSelector = 'form') {
  for (const [field, value] of Object.entries(formData)) {
    const selector = `${formSelector} [name="${field}"]`;
    const element = await page.locator(selector);
    
    if (await element.count() > 0) {
      const elementType = await element.getAttribute('type');
      const tagName = await element.evaluate(el => el.tagName.toLowerCase());
      
      if (elementType === 'checkbox' || elementType === 'radio') {
        if (value) await element.check();
      } else if (tagName === 'select') {
        await element.selectOption(value);
      } else {
        await element.fill(value.toString());
      }
    }
  }
}

/**
 * Wait for toast notification to appear
 * @param {Page} page - Playwright page object
 * @param {string} type - success, error, warning, info
 */
export async function waitForToast(page, type = null) {
  const selector = type ? `.toast.toast-${type}` : '.toast';
  await page.waitForSelector(selector, { state: 'visible' });
}

/**
 * Handle file upload in Laravel
 * @param {Page} page - Playwright page object
 * @param {string} inputSelector - File input selector
 * @param {string} filePath - Path to file to upload
 */
export async function uploadFile(page, inputSelector, filePath) {
  const fileChooserPromise = page.waitForEvent('filechooser');
  await page.click(inputSelector);
  const fileChooser = await fileChooserPromise;
  await fileChooser.setFiles(filePath);
}
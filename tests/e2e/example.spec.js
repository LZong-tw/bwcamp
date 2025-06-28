const { test, expect } = require('@playwright/test');
const { loginAs, waitForLivewire, fillLaravelForm } = require('./helpers/laravel-helpers');

test.describe('Buddhist Camp Management System', () => {
  
  test('homepage loads correctly', async ({ page }) => {
    await page.goto('/');
    
    // Check if the page title contains expected text
    await expect(page).toHaveTitle(/營隊管理系統/);
    
    // Check for main navigation or key elements
    const mainContent = page.locator('body');
    await expect(mainContent).toBeVisible();
  });

  test('login functionality', async ({ page }) => {
    await page.goto('/login');
    
    // Check login form exists
    await expect(page.locator('form')).toBeVisible();
    await expect(page.locator('input[name="email"]')).toBeVisible();
    await expect(page.locator('input[name="password"]')).toBeVisible();
    
    // Fill login form
    await fillLaravelForm(page, {
      email: 'admin@test.com',
      password: 'password'
    });
    
    // Submit form
    await page.click('button[type="submit"]');
    
    // Wait for redirect (adjust URL pattern based on your app)
    await page.waitForURL(/\/(?!login)/);
    
    // Verify successful login (adjust selector based on your app)
    const loggedInIndicator = page.locator('.user-menu, .logout-link, [data-test="user-dropdown"]').first();
    await expect(loggedInIndicator).toBeVisible();
  });

  test('camp registration flow', async ({ page }) => {
    // Login first
    await loginAs(page, 'user');
    
    // Navigate to camp registration
    await page.goto('/camps');
    
    // Wait for Livewire components to load
    await waitForLivewire(page);
    
    // Check if camps are listed
    const campsList = page.locator('.camp-list, .camps-container, [data-test="camps"]').first();
    await expect(campsList).toBeVisible();
    
    // Look for registration buttons or links
    const registerButton = page.locator('a:has-text("報名"), button:has-text("報名"), .register-btn').first();
    
    if (await registerButton.count() > 0) {
      await registerButton.click();
      
      // Wait for registration form
      await expect(page.locator('form')).toBeVisible();
      
      // Fill basic registration information
      await fillLaravelForm(page, {
        name: '測試學員',
        phone: '0912345678',
        email: 'test@example.com'
      });
      
      // Note: Adjust form fields based on your actual registration form
    }
  });

  test('admin dashboard access', async ({ page }) => {
    // Login as admin
    await loginAs(page, 'admin');
    
    // Navigate to admin dashboard
    await page.goto('/admin');
    
    // Check for admin-specific elements
    const adminContent = page.locator('.admin-dashboard, .management-panel, [data-test="admin-panel"]').first();
    
    if (await adminContent.count() > 0) {
      await expect(adminContent).toBeVisible();
    }
    
    // Check for camp management links
    const campManagement = page.locator('a:has-text("營隊管理"), a:has-text("Camp Management")').first();
    if (await campManagement.count() > 0) {
      await expect(campManagement).toBeVisible();
    }
  });

  test('responsive design on mobile', async ({ page }) => {
    // Set mobile viewport
    await page.setViewportSize({ width: 375, height: 667 });
    
    await page.goto('/');
    
    // Check if mobile navigation works
    const mobileMenu = page.locator('.mobile-menu, .hamburger, .nav-toggle').first();
    
    if (await mobileMenu.count() > 0) {
      await mobileMenu.click();
      
      // Check if navigation menu appears
      const navMenu = page.locator('.nav-menu, .mobile-nav').first();
      await expect(navMenu).toBeVisible();
    }
  });

});
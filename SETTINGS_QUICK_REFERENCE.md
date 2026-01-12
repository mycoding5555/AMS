# Settings Feature - Quick Reference

## Access the Settings Page
1. Login to admin dashboard
2. Click on **Settings** in the sidebar (⚙️ icon)
3. URL: `http://localhost:8000/admin/settings`

## Settings Categories

### 🏢 Application Settings
- **App Name** - What to call your application
- **Language** - English or Khmer (ភាសាខ្មែរ)
- **Theme** - Light or Dark mode
- **Timezone** - Your local time zone

### 📅 Date Range Settings
- **Start Date** - When operations began
- **Close Date** - When operations will end

### 🏛️ Company Information
- **Company Name**
- **Phone Number**
- **Email Address**
- **Physical Address**

### 💰 Financial Settings
- **Currency** - USD, EUR, GBP, KHR, JPY, AUD
- **Tax Rate** - Percentage (0-100%)

### ⚙️ System Settings
- **Maintenance Mode** - On/Off (restricts access during maintenance)

## How to Use Settings in Code

### In a Controller
```php
use App\Helpers\SettingsHelper;

$appName = SettingsHelper::getAppName();
$language = SettingsHelper::getLanguage();
$theme = SettingsHelper::getTheme();
$currency = SettingsHelper::getCurrency();
$timezone = SettingsHelper::getTimezone();
```

### In a Blade Template
```blade
<!-- Get app name -->
{{ \App\Helpers\SettingsHelper::getAppName() }}

<!-- Check if dark theme is enabled -->
@if(\App\Helpers\SettingsHelper::getTheme() === 'dark')
    <body class="dark-mode">
    ...
    </body>
@endif

<!-- Check language for internationalization -->
@if(\App\Helpers\SettingsHelper::getLanguage() === 'km')
    <!-- Display Khmer content -->
@endif
```

### Direct Database Access
```php
use App\Models\Setting;

// Get a setting
$value = Setting::get('key_name', 'default_value');

// Set a setting
Setting::set('key_name', 'value');

// Get all settings as array
$all = Setting::query()->get();
```

## Key Names for Reference
- `app_name`
- `app_start_date`
- `app_close_date`
- `language` (values: 'en', 'km')
- `theme` (values: 'light', 'dark')
- `currency`
- `timezone`
- `company_name`
- `company_address`
- `company_phone`
- `company_email`
- `tax_rate`
- `maintenance_mode` (values: 'on', 'off')

## Database Table
Settings are stored in the `settings` table with structure:
- `id` - Primary key
- `key` - Unique setting identifier
- `value` - The setting value
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

## Validation
All form inputs are validated:
- Text fields: required, max 255 chars
- Email: must be valid email format
- Dates: must be valid date format
- Language/Theme/Maintenance: enum validation
- Tax Rate: 0-100%
- Phone: max 20 characters

## Tips & Best Practices

1. **Language Support**
   - Always provide content in both English and Khmer
   - Use the language setting to switch UI languages

2. **Theme Implementation**
   - Apply CSS classes based on theme setting
   - Dark theme should have sufficient contrast
   - Respect user's system preference as initial value

3. **Timezone Usage**
   - Always store times in UTC in database
   - Convert to selected timezone for display
   - Use Carbon for timezone conversions:
   ```php
   $date = now()->setTimezone(SettingsHelper::getTimezone());
   ```

4. **Tax Rate**
   - Store as percentage (0-100)
   - Multiply by 0.01 when calculating actual tax
   - Example: 10% tax rate = 10, multiply by 0.01 = 0.1

5. **Maintenance Mode**
   - Use in middleware to restrict access
   - Show maintenance page to non-admin users
   - Keep admin access during maintenance

## Troubleshooting

**Settings not saving?**
- Check if user has admin role
- Verify database permissions
- Check validation errors in response

**Theme not applying?**
- Clear browser cache
- Verify CSS is loading
- Check JavaScript for theme switching logic

**Language not changing?**
- Ensure translation files exist
- Check locale middleware
- Verify language setting is being read correctly

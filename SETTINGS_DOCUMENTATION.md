# Settings Feature Documentation

## Overview
The Settings feature allows administrators to manage various application-wide configurations including dates, language, theme, company information, and financial settings.

## Features Implemented

### 1. **Start and Close Date**
- Set when the apartment management system started operations
- Set when the apartment management system will close or end operations
- Useful for historical record-keeping and planning

### 2. **Language Support**
- **English** - Default language
- **Khmer (ភាសាខ្មែរ)** - Full support for Cambodian language

### 3. **Theme Support**
- **Light Theme** - Default, ideal for bright environments
- **Dark Theme** - For reducing eye strain in low-light environments

### 4. **Other Settings**

#### Application Settings
- **App Name** - Customizable application name (default: "Apartment Management System")
- **Timezone** - Select from multiple timezones including UTC, Asia/Phnom_Penh, Asia/Bangkok, etc.

#### Company Information
- **Company Name** - Your organization's name
- **Company Address** - Full address
- **Company Phone** - Contact phone number
- **Company Email** - Contact email address

#### Financial Settings
- **Currency** - Select currency (USD, EUR, GBP, KHR, JPY, AUD)
- **Tax Rate** - Percentage for tax calculations

#### System Settings
- **Maintenance Mode** - Enable/disable maintenance mode to restrict user access during maintenance

## Database Structure

### Settings Table
```sql
CREATE TABLE settings (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    key VARCHAR(255) NOT NULL UNIQUE,
    value LONGTEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
)
```

## File Structure

### Model
- **Location:** `app/Models/Setting.php`
- **Methods:**
  - `get($key, $default = null)` - Retrieve a setting value
  - `set($key, $value)` - Update or create a setting
  - `getAllSettings()` - Get all settings as array

### Controller
- **Location:** `app/Http/Controllers/Admin/SettingController.php`
- **Methods:**
  - `index()` - Display settings page
  - `update(Request $request)` - Update settings

### Helper Class
- **Location:** `app/Helpers/SettingsHelper.php`
- **Methods:**
  - `get($key, $default = null)` - Get setting value
  - `getTheme()` - Get current theme
  - `getLanguage()` - Get current language
  - `getCurrency()` - Get current currency
  - `getTimezone()` - Get current timezone
  - `isMaintenanceMode()` - Check if maintenance mode is enabled
  - `getAppName()` - Get application name
  - `getStartDate()` - Get app start date
  - `getCloseDate()` - Get app close date

### View
- **Location:** `resources/views/admin/settings/index.blade.php`
- Features a comprehensive form with validation and error handling

### Migration
- **Location:** `database/migrations/2026_01_12_120000_create_settings_table.php`
- Creates the settings table with key-value structure

### Seeder
- **Location:** `database/seeders/SettingsSeeder.php`
- Seeds default settings into the database

## Routes

### Admin Routes
```php
Route::get('settings', [Admin\SettingController::class, 'index'])->name('settings.index');
Route::post('settings', [Admin\SettingController::class, 'update'])->name('settings.update');
```

**Access:** `GET /admin/settings` - View settings page
**Submit:** `POST /admin/settings` - Update settings

## Usage Examples

### In Controllers
```php
use App\Helpers\SettingsHelper;

$language = SettingsHelper::getLanguage(); // 'en' or 'km'
$theme = SettingsHelper::getTheme();        // 'light' or 'dark'
$currency = SettingsHelper::getCurrency();  // 'USD', 'KHR', etc.
$timezone = SettingsHelper::getTimezone();  // 'UTC', 'Asia/Phnom_Penh', etc.
```

### In Views (Blade)
```blade
<!-- Get a setting value directly -->
{{ \App\Helpers\SettingsHelper::get('app_name') }}

<!-- Check theme -->
@if(\App\Helpers\SettingsHelper::getTheme() === 'dark')
    <!-- Dark theme specific content -->
@endif
```

### In Models
```php
use App\Models\Setting;

// Get a setting
$language = Setting::get('language', 'en');

// Set a setting
Setting::set('theme', 'dark');
```

## Validation Rules

The settings form validates:
- `app_name` - Required, string, max 255 characters
- `app_start_date` - Optional, valid date format
- `app_close_date` - Optional, valid date format
- `language` - Required, either 'en' or 'km'
- `theme` - Required, either 'light' or 'dark'
- `currency` - Required, string, max 10 characters
- `timezone` - Required, valid timezone string
- `company_name` - Optional, string, max 255
- `company_address` - Optional, string
- `company_phone` - Optional, string, max 20
- `company_email` - Optional, valid email format
- `tax_rate` - Optional, numeric, min 0, max 100
- `maintenance_mode` - Required, either 'on' or 'off'

## Default Settings

Upon seeding, the following default settings are created:

| Key | Default Value |
|-----|---------------|
| app_name | Apartment Management System |
| app_start_date | null |
| app_close_date | null |
| language | en |
| theme | light |
| currency | USD |
| timezone | UTC |
| company_name | (empty) |
| company_address | (empty) |
| company_phone | (empty) |
| company_email | (empty) |
| tax_rate | 0 |
| maintenance_mode | off |

## Admin Interface Features

The settings page includes:

1. **Application Settings Section**
   - App name input
   - Language dropdown (English/Khmer)
   - Theme toggle (Light/Dark)
   - Timezone selection

2. **Date Range Section**
   - Start date picker
   - Close/Closure date picker
   - Helper text for each field

3. **Company Information Section**
   - Company name input
   - Company phone input
   - Company email input
   - Company address textarea

4. **Financial Settings Section**
   - Currency dropdown
   - Tax rate percentage input

5. **System Settings Section**
   - Maintenance mode toggle

6. **User Feedback**
   - Success alerts on save
   - Error alerts with validation messages
   - Dismissible alert boxes

## Security Considerations

- All settings are validated on the server side
- Only authenticated admin users can access settings
- CSRF protection is enabled on the form
- All user inputs are properly sanitized

## Future Enhancements

Potential future additions:
- Email notification settings
- Backup and restore settings
- System logs configuration
- API key management
- Role-based settings
- Settings versioning/audit trail
- Import/Export settings
- Multi-language labels in database

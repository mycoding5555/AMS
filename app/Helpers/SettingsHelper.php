<?php

namespace App\Helpers;

use App\Models\Setting;

class SettingsHelper
{
    /**
     * Get a setting value
     */
    public static function get($key, $default = null)
    {
        return Setting::get($key, $default);
    }

    /**
     * Get theme setting
     */
    public static function getTheme()
    {
        return self::get('theme', 'light');
    }

    /**
     * Get language setting
     */
    public static function getLanguage()
    {
        return self::get('language', 'en');
    }

    /**
     * Get currency setting
     */
    public static function getCurrency()
    {
        return self::get('currency', 'USD');
    }

    /**
     * Get timezone setting
     */
    public static function getTimezone()
    {
        return self::get('timezone', 'UTC');
    }

    /**
     * Check if maintenance mode is enabled
     */
    public static function isMaintenanceMode()
    {
        return self::get('maintenance_mode') === 'on';
    }

    /**
     * Get app name
     */
    public static function getAppName()
    {
        return self::get('app_name', 'Apartment Management System');
    }

    /**
     * Get app start date
     */
    public static function getStartDate()
    {
        return self::get('app_start_date');
    }

    /**
     * Get app close date
     */
    public static function getCloseDate()
    {
        return self::get('app_close_date');
    }

    /**
     * Get company name
     */
    public static function getCompanyName()
    {
        return self::get('company_name', '');
    }

    /**
     * Get company address
     */
    public static function getCompanyAddress()
    {
        return self::get('company_address', '');
    }

    /**
     * Get company phone
     */
    public static function getCompanyPhone()
    {
        return self::get('company_phone', '');
    }

    /**
     * Get company email
     */
    public static function getCompanyEmail()
    {
        return self::get('company_email', '');
    }

    /**
     * Get tax rate
     */
    public static function getTaxRate()
    {
        return (float) self::get('tax_rate', 0);
    }

    /**
     * Format amount with currency symbol
     */
    public static function formatCurrency($amount)
    {
        $currency = self::getCurrency();
        $symbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'KHR' => '៛',
            'JPY' => '¥',
            'AUD' => 'A$',
        ];
        
        $symbol = $symbols[$currency] ?? $currency . ' ';
        
        if ($currency === 'KHR') {
            return number_format($amount, 0) . ' ' . $symbol;
        }
        
        return $symbol . number_format($amount, 2);
    }

    /**
     * Format date with timezone
     */
    public static function formatDate($date, $format = 'Y-m-d')
    {
        if (!$date) return null;
        
        $timezone = self::getTimezone();
        return \Carbon\Carbon::parse($date)->setTimezone($timezone)->format($format);
    }

    /**
     * Format datetime with timezone
     */
    public static function formatDateTime($datetime, $format = 'Y-m-d H:i:s')
    {
        if (!$datetime) return null;
        
        $timezone = self::getTimezone();
        return \Carbon\Carbon::parse($datetime)->setTimezone($timezone)->format($format);
    }

    /**
     * Get current time in app timezone
     */
    public static function now()
    {
        $timezone = self::getTimezone();
        return \Carbon\Carbon::now($timezone);
    }

    /**
     * Calculate tax amount
     */
    public static function calculateTax($amount)
    {
        $taxRate = self::getTaxRate();
        return $amount * ($taxRate / 100);
    }

    /**
     * Get all company details as array
     */
    public static function getCompanyDetails()
    {
        return [
            'name' => self::getCompanyName(),
            'address' => self::getCompanyAddress(),
            'phone' => self::getCompanyPhone(),
            'email' => self::getCompanyEmail(),
        ];
    }
}

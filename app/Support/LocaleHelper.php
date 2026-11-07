<?php

namespace App\Support;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Support\Facades\App;

class LocaleHelper
{
    /**
     * Bengali digit mappings.
     *
     * @var array<string, string>
     */
    protected static array $bnDigits = [
        '0' => '০',
        '1' => '১',
        '2' => '২',
        '3' => '৩',
        '4' => '৪',
        '5' => '৫',
        '6' => '৬',
        '7' => '৭',
        '8' => '৮',
        '9' => '৯',
    ];

    /**
     * English to Bengali day names.
     *
     * @var array<string, string>
     */
    protected static array $bnDays = [
        'Saturday' => 'শনিবার',
        'Sunday' => 'রবিবার',
        'Monday' => 'সোমবার',
        'Tuesday' => 'মঙ্গলবার',
        'Wednesday' => 'বুধবার',
        'Thursday' => 'বৃহস্পতিবার',
        'Friday' => 'শুক্রবার',
    ];

    /**
     * English to Bengali month names.
     *
     * @var array<string, string>
     */
    protected static array $bnMonths = [
        'January' => 'জানুয়ারি',
        'February' => 'ফেব্রুয়ারি',
        'March' => 'মার্চ',
        'April' => 'এপ্রিল',
        'May' => 'মে',
        'June' => 'জুন',
        'July' => 'জুলাই',
        'August' => 'আগস্ট',
        'September' => 'সেপ্টেম্বর',
        'October' => 'অক্টোবর',
        'November' => 'নভেম্বর',
        'December' => 'ডিসেম্বর',
        'Jan' => 'জানু',
        'Feb' => 'ফেব্রু',
        'Mar' => 'মার্চ',
        'Apr' => 'এপ্রিল',
        'May' => 'মে',
        'Jun' => 'জুন',
        'Jul' => 'জুলাই',
        'Aug' => 'আগস্ট',
        'Sep' => 'সেপ্টে',
        'Oct' => 'অক্টো',
        'Nov' => 'নভে',
        'Dec' => 'ডিসে',
    ];

    /**
     * Convert number to localized digits.
     */
    public static function number(int|float|string|null $number): string
    {
        if ($number === null || $number === '') {
            return '';
        }

        $formatted = is_numeric($number) ? number_format((float) $number) : (string) $number;

        if (App::getLocale() === 'bn') {
            return strtr($formatted, self::$bnDigits);
        }

        return $formatted;
    }

    /**
     * Format topbar header date with city.
     */
    public static function headerDate(?DateTimeInterface $date = null): string
    {
        $dt = $date ? Carbon::instance($date) : Carbon::now();
        $isBn = App::getLocale() === 'bn';

        if ($isBn) {
            $dayName = self::$bnDays[$dt->format('l')] ?? $dt->format('l');
            $day = self::number($dt->format('d'));
            $month = self::$bnMonths[$dt->format('F')] ?? $dt->format('F');
            $year = self::number($dt->format('Y'));

            return "{$dayName}, {$day} {$month} {$year}  |  ঢাকা";
        }

        return $dt->format('l, d F Y').'  |  Dhaka';
    }

    /**
     * Format a general date.
     */
    public static function formatDate(?DateTimeInterface $date = null, string $format = 'd M Y'): string
    {
        if (! $date) {
            return '';
        }

        $dt = Carbon::instance($date);
        $isBn = App::getLocale() === 'bn';

        if ($isBn) {
            $formatted = $dt->format($format);
            $formatted = strtr($formatted, self::$bnMonths);
            $formatted = strtr($formatted, self::$bnDays);

            return strtr($formatted, self::$bnDigits);
        }

        return $dt->format($format);
    }

    /**
     * Format date & time for articles.
     */
    public static function formatDateTime(?DateTimeInterface $date = null): string
    {
        if (! $date) {
            return '';
        }

        $dt = Carbon::instance($date);
        $isBn = App::getLocale() === 'bn';

        if ($isBn) {
            $datePart = self::formatDate($dt, 'd M Y');
            $timePart = self::number($dt->format('h:i'));
            $meridiem = $dt->format('A') === 'AM' ? 'সকাল' : 'সন্ধ্যা';
            if ((int) $dt->format('H') >= 12 && (int) $dt->format('H') < 16) {
                $meridiem = 'দুপুর';
            } elseif ((int) $dt->format('H') >= 16 && (int) $dt->format('H') < 19) {
                $meridiem = 'বিকেল';
            } elseif ((int) $dt->format('H') >= 19 || (int) $dt->format('H') < 5) {
                $meridiem = 'রাত';
            }

            return "{$datePart}, {$meridiem} {$timePart}";
        }

        return $dt->format('d M Y, h:i A');
    }

    /**
     * Localize diffForHumans relative time.
     */
    public static function diffForHumans(?DateTimeInterface $date = null): string
    {
        if (! $date) {
            return '';
        }

        $dt = Carbon::instance($date);

        if (App::getLocale() === 'bn') {
            $diff = $dt->diffForHumans();

            return strtr($diff, self::$bnDigits);
        }

        return $dt->diffForHumans();
    }

    /**
     * Localize category or subcategory name.
     */
    public static function categoryName(?string $name): string
    {
        if (! $name) {
            return '';
        }

        return __($name);
    }
}

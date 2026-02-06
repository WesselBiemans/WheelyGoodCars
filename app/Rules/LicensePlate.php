<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class LicensePlate implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remove any spaces or dashes for validation
        $normalized = strtoupper(str_replace([' ', '-'], '', $value));

        // Check if the length is valid (6 characters)
        if (strlen($normalized) !== 6) {
            $fail('Het kenteken moet 6 tekens bevatten.');
            return;
        }

        // license plate patterns
        $patterns = [
            // XX-99-99
            '/^[A-Z]{2}[0-9]{4}$/',

            // 99-99-XX
            '/^[0-9]{4}[A-Z]{2}$/',

            // 99-XX-99
            '/^[0-9]{2}[A-Z]{2}[0-9]{2}$/',

            // XX-99-XX
            '/^[A-Z]{2}[0-9]{2}[A-Z]{2}$/',

            // XX-XX-99
            '/^[A-Z]{2}[A-Z]{2}[0-9]{2}$/',

            // 99-XX-XX
            '/^[0-9]{2}[A-Z]{2}[A-Z]{2}$/',

            // 99-XXX-9
            '/^[0-9]{2}[A-Z]{3}[0-9]{1}$/',

            // 9-XXX-99
            '/^[0-9]{1}[A-Z]{3}[0-9]{2}$/',

            // XX-999-X
            '/^[A-Z]{2}[0-9]{3}[A-Z]{1}$/',

            // X-999-XX
            '/^[A-Z]{1}[0-9]{3}[A-Z]{2}$/',

            // XXX-99-X
            '/^[A-Z]{3}[0-9]{2}[A-Z]{1}$/',

            // X-999-XX
            '/^[0-9]{1}[A-Z]{3}[0-9]{2}$/',

            // XX-999-X
            '/^[A-Z]{2}[0-9]{3}[A-Z]{1}$/',

            // XXX-99-X
            '/^[A-Z]{3}[0-9]{2}[A-Z]{1}$/',
        ];

        // Check if the normalized license plate matches any valid pattern
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $normalized)) {
                return; // Valid license plate
            }
        }

        // Invalid license plate
        $fail('Het kenteken heeft geen geldig Nederlands kenteken formaat.');
    }
}

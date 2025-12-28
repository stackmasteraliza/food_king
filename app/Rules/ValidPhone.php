<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Smartisan\Settings\Facades\Settings;
use PragmaRX\Countries\Package\Countries;

class ValidPhone implements Rule
{
    public $message = '';
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $site_default_phone_digit_length = Settings::group('site')->get('site_default_phone_digit_length');

        if ((int)$site_default_phone_digit_length !== strlen($value)) {
            $this->message = 'The :attribute number should be ' . $site_default_phone_digit_length . ' digits long.';
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->message;
    }
}
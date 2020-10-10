<?php
/**
 * Created by PhpStorm.
 * User: Jana
 * Date: 13. 11. 2018
 * Time: 21:58
 */

namespace Jss\Form\Validator;


class FormValidator
{
    const MIN = __CLASS__ . '::min';
    const MAX = __CLASS__ . '::max';
    const RANGE = __CLASS__ . '::range';
    const EQUAL = __CLASS__ . '::equal';
    const NOT_EQUAL = __CLASS__ . '::notEqual';
    const MIN_LENGTH = __CLASS__ . '::minLength';
    const MAX_LENGTH = __CLASS__ . '::maxLength';
    const LENGTH = __CLASS__ . '::length';
    const PATTERN = __CLASS__ . '::pattern';
    const REGEX = __CLASS__ . '::pattern';
    const INTEGER = __CLASS__ . '::integer';
    const FLOAT = __CLASS__ . '::float';
    const FILLED = __CLASS__ . '::filled';
    const REQUIRED = __CLASS__ . '::filled';
    const IS_IN = __CLASS__ . '::isIn';
    const IS_NOT_IN = __CLASS__ . '::isNotIn';
    const EMAIL = __CLASS__ . '::email';
    const BANK = __CLASS__ . '::bank';
    const URL = __CLASS__ . '::url';
    const RC = __CLASS__ . '::rc';
    const PSC = __CLASS__ . '::psc';
    const MOBIL = __CLASS__ . '::mobil';
    const IP = __CLASS__ . '::ip';
    const PHONE = __CLASS__ . '::phone';

    public static $messages = [
        self::MIN => 'Please enter a value greater than or equal to %d.',
        self::MAX => 'Please enter a value less than or equal to %d.',
        self::RANGE => 'Please enter a value between %d and %d.',
        self::EQUAL => 'Please enter %s.',
        self::NOT_EQUAL => 'This value should not be %s.',
        self::MIN_LENGTH => 'Please enter at least %d characters.',
        self::MAX_LENGTH => 'Please enter no more than %d characters.',
        self::LENGTH => 'Please enter a value %d characters long.',
        self::PATTERN => '', //TODO:
        self::REGEX => '', //TODO:
        self::INTEGER => 'Please enter a valid number.',
        self::FLOAT => 'Please enter a valid number.',
        self::FILLED => '', //TODO:
        self::REQUIRED => '', //TODO:
        self::IS_IN => '', //TODO:
        self::IS_NOT_IN => '', //TODO:
        self::EMAIL => 'Please enter a valid email address.',
        self::BANK => '', //TODO:
        self::RC => '', //TODO:
        self::MOBIL => '', //TODO:
        self::PSC => '', //TODO:
        self::URL => 'Please enter a valid URL.',
        self::IP => 'Please enter a valid IP.',
        self::PHONE => 'Please enter a valid phone number.',
    ];

    public static $javascriptCodes = [
        self::MIN => 'min',
        self::MAX => 'max',
        self::RANGE => 'range',
        self::EQUAL => 'equal',
        self::NOT_EQUAL => 'not_equal',
        self::MIN_LENGTH => 'min_length',
        self::MAX_LENGTH => 'max_length',
        self::LENGTH => 'length',
        self::PATTERN => 'pattern',
        self::REGEX => 'regex',
        self::INTEGER => 'integer',
        self::FLOAT => 'float',
        self::FILLED => 'filled',
        self::REQUIRED => 'required',
        self::IS_IN => 'is_in',
        self::IS_NOT_IN => 'is_not_in',
        self::EMAIL => 'email',
        self::BANK => 'bank',
        self::RC => 'rc',
        self::MOBIL => 'mobil',
        self::PSC => 'psc',
        self::URL => 'url',
        self::IP => 'ip',
        self::PHONE => 'phone',
    ];

    /**
     * @param $value
     * @param $min
     * @return bool
     */
    public static function min($value, $min)
    {
        return Validator::min($value, $min);
    }

    public static function max($value, $max)
    {
        return Validator::max($value, $max);
    }

    public static function range($value, $min, $max)
    {
        return Validator::range($value, $min, $max);
    }

    public static function equal($value, $equalTo)
    {
        return Validator::equal($value, $equalTo);
    }

    public static function notEqual($value, $equalTo)
    {
        return Validator::notEqual($value, $equalTo);
    }

    public static function minLength($value, $minLength)
    {
        return Validator::minLength($value, $minLength);
    }

    public static function maxLength($value, $maxLength)
    {
        return Validator::maxLength($value, $maxLength);

    }

    public static function length($value, $length)
    {
        return Validator::length($value, $length);
    }

    public static function filled($value)
    {
        return Validator::filled($value);
    }

    public static function pattern($value, $pattern)
    {
        return Validator::pattern($value, $pattern);

    }

    public static function integer($value)
    {
        return Validator::integer($value);
    }

    public static function float($value)
    {
        return Validator::float($value);
    }

    public static function isIn($value, array $values = [])
    {
        return Validator::isIn($value, $values);
    }

    public static function isNotIn($value, array $values = [])
    {
        return Validator::isNotIn($value, $values);
    }

    public static function bank($value)
    {
        return Validator::bank($value);
    }

    public static function url($value)
    {
        return Validator::url($value);
    }

    public static function rc($value, $birthDate = null, $sex = null)
    {
        return Validator::rc($value, $birthDate, $sex);
    }

    public static function mobil($value)
    {
        return Validator::mobil($value);
    }

    public static function psc($value)
    {
        return Validator::psc($value);
    }

    public static function ip($value)
    {
        return Validator::ip($value);
    }

    public static function email($value)
    {
        return Validator::email($value);
    }

    public static function phone($value)
    {
        return Validator::phone($value);
    }


}

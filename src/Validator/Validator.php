<?php
/**
 * Created by PhpStorm.
 * User: jana
 * Date: 06.11.2018
 * Time: 15:15
 */

namespace Jss\Form\Validator;


use DateTime;

class Validator
{

    /**
     * @param $value
     * @param $min
     * @return bool
     */
    public static function min($value, $min)
    {
        return $value >= $min;
    }

    public static function max($value, $max)
    {
        return $value <= $max;
    }

    public static function range($value, $min, $max)
    {
        return (self::min($value, $min) && self::max($value, $max));
    }

    public static function equal($value, $equalTo)
    {
        return $value == $equalTo;
    }

    public static function notEqual($value, $equalTo)
    {
        return !self::equal($value, $equalTo);
    }

    public static function filled($value)
    {
        if (is_null($value) || $value === '') return false;
        return true;
    }

    /**
     * @param $value
     * @param $minLength
     * @return bool
     */
    public static function minLength($value, $minLength)
    {
        return mb_strlen($value) >= $minLength;
    }

    /**
     * @param $value
     * @param $maxLength
     * @return bool
     */
    public static function maxLength($value, $maxLength)
    {
        return mb_strlen($value) <= $maxLength;
    }

    public static function length($value, $length)
    {
        return mb_strlen($value) == $length;
    }


    public static function pattern($value, $pattern)
    {
        return preg_match($pattern, $value);
    }

    public static function integer($value)
    {
        return is_int($value) || is_string($value) && preg_match('#^-?[0-9]+\z#', $value);
    }

    public static function float($value)
    {
        return is_float($value) || is_int($value) || is_string($value) && preg_match('#^-?[0-9]*[.]?[0-9]+\z#', $value);
    }

    public static function isIn($value, array $values = [])
    {
        return in_array($value, $values);
    }

    public static function isNotIn($value, array $values = [])
    {
        return !self::isIn($value, $values);
    }

    public static function bank($value)
    {
        //todo:
        return true;
    }

    public static function url($value)
    {
        $alpha = "a-z\x80-\xFF";
        $subDomain = "[-_0-9$alpha]";
        $domain = "[0-9$alpha](?:[-0-9$alpha]{0,61}[0-9$alpha])?";
        $topDomain = "[$alpha](?:[-0-9$alpha]{0,17}[$alpha])?";
        $domainName = "(?:(?:$subDomain+\\.)*?$domain\\.)?$topDomain";
        return (bool)preg_match("(^https?://(?:$domainName|\\d{1,3}\.\\d{1,3}\.\\d{1,3}\.\\d{1,3}|\[[0-9a-f:]{3,39}\])(:\\d{1,5})?(/\\S*)?\\z)i", $value);

    }

    public static function rc($value, $birthdate = null, $sex = null)
    {
        if (!preg_match('#^(\d\d)(\d\d)(\d\d)[ /]?(\d\d\d)(\d?)$#', $value, $matches))
            return FALSE;
        list(, $year, $month, $day, $ext, $c) = $matches;

        if ($c === '')
        {
            if ($year >= 54)
                return FALSE;
            $year += 1900;
            if ($month > 50)
                $month -= 50;
            return checkdate($month, $day, $year);
        }
        $mod = ($year . $month . $day . $ext) % 11;
        if ($mod === 10)
            $mod = 0;
        if ($mod !== (int)$c)
            return FALSE;
        // kontrola data
        $year += $year < 54?2000:1900;
        if ($month > 70 && $year > 2003)
            $month -= 70;
        elseif ($month > 50)
            $month -= 50;
        elseif ($month > 20 && $year > 2003)
            $month -= 20;
        if (strlen($month) == 1) $month = '0' . $month;
        // kontrola budoucnosti a platnosti datumu
        if ("$year$month$day" > Date('Ymd'))
            return FALSE;
        $platne = checkdate($month, $day, $year);
        if ($birthdate)
        {
            if (date('Y-m-d', strtotime($birthdate)) != "$year-$month-$day") $platne = false;
        }
        if ($sex && in_array($sex, ['M', 'Z']))
        {
            if ($matches[2] > 50 && $sex == 'M') $platne = false;
            if ($matches[2] < 50 && $sex == 'Z') $platne = false;
        }
        return $platne;
    }

    public static function psc($value)
    {
        return self::pattern($value, '~^[0-9]{5}$~');
    }

    public static function mobil($value)
    {
        return self::pattern($value, '~[0-9]{9}~');
    }

    public static function ip($value)
    {
        //todo:
        return true;
    }

    public static function email($value)
    {
        $atom = "[-a-z0-9!#$%&'*+/=?^_`{|}~]"; // RFC 5322 unquoted characters in local-part
        $alpha = "a-z\x80-\xFF"; // superset of IDN
        return (bool)preg_match("(^
			(\"([ !#-[\\]-~]*|\\\\[ -~])+\"|$atom+(\\.$atom+)*)  # quoted or unquoted
			@
			([0-9$alpha]([-0-9$alpha]{0,61}[0-9$alpha])?\\.)+    # domain - RFC 1034
			[$alpha]([-0-9$alpha]{0,17}[$alpha])?                # top domain
		\\z)ix", $value);
    }

    public static function phone($value)
    {
        //todo:
        return true;
    }

    public static function date($value, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $value);
        return $d && $d->format($format) === $value;
    }

    public static function datetime($value, $format = "Y-m-d H:i:s")
    {
        return self::date($value, $format);
    }

    public static function time($value)
    {
        $reg = "~([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?~";
        return self::pattern($value, $reg);

    }

    public function __call($name, $args)
    {
        if (method_exists(__CLASS__, $name))
        {
            return call_user_func_array([__CLASS__, $name], $args);
        }
    }


}
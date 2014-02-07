<?php

/**
 * Класс для валидации ISBN
 */
class IsbnValidator
{
    static public function factory() {
        return new self;
    }

    /**
     * Функция для валидации ISBN
     *
     * @param $code string ISBN код
     * @return boolean
     */
    public function isNumberValid($code = '') {
        if (empty($code)) {
            return false;
        }

        if (!preg_match('/[\d]{0,3}[-]{0,1}[\d]{1,5}[-]{0,1}[\d]{1,8}[-]{0,1}[\d]{1,6}[-]{0,1}([0-9]|[x])/i', $code)) {
            return false;
        }

        $code = str_replace('-', '', $code);
        $codeLength = strlen($code);
        $checkNumber = substr($code, -1, 1);
        $checkNumber = is_numeric($checkNumber) ? intval($checkNumber) : 10;
        $checkSum = 0;

        /*
         * Старый ISBN-формат состоит из 10 цифр,
         * новый из 13 цифр (введен c 2007 года)
         */
        if ($codeLength == 10) {
            for ($i=1; $i<=9; $i++) {
                $checkSum += $code[$i-1]*$i;
            }

            return ($checkSum % 11) == $checkNumber;
        } elseif ($codeLength == 13) {
            for ($i=1; $i<=12; $i++) {
                $checkSum += $code[$i-1]*((($i % 2) == 0) ? 3 : 1);
            }

            return ((($checkSum % 10) == $checkNumber) || (($checkSum % 10) == (10 - $checkNumber)));
        } else {
            return false;
        }
    }
}

// Использование
if (IsbnValidator::factory()->isNumberValid('978-5-458-03028-1')) {
    print 'valid';
} else {
    print 'invalid';
}


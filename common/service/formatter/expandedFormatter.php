<?php

namespace common\service\formatter;

use Yii;
use yii\i18n\Formatter;

class expandedFormatter extends Formatter
{
    public function asTranslit($value): string
    {
        $converter = array(
            'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
            'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
            'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
            'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
            'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
            'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
            'э' => 'e',    'ю' => 'yu',   'я' => 'ya',

            'А' => 'A',    'Б' => 'B',    'В' => 'V',    'Г' => 'G',    'Д' => 'D',
            'Е' => 'E',    'Ё' => 'E',    'Ж' => 'Zh',   'З' => 'Z',    'И' => 'I',
            'Й' => 'Y',    'К' => 'K',    'Л' => 'L',    'М' => 'M',    'Н' => 'N',
            'О' => 'O',    'П' => 'P',    'Р' => 'R',    'С' => 'S',    'Т' => 'T',
            'У' => 'U',    'Ф' => 'F',    'Х' => 'H',    'Ц' => 'C',    'Ч' => 'Ch',
            'Ш' => 'Sh',   'Щ' => 'Sch',  'Ь' => '',     'Ы' => 'Y',    'Ъ' => '',
            'Э' => 'E',    'Ю' => 'Yu',   'Я' => 'Ya',
        );

        $new = '';

        $string = trim($value);

        if (!empty($string)) {
            $string = strtr($string, $converter);
            $string = mb_ereg_replace('[-]+', '-', $string);
            $string= trim($string, '-');
            $new .= $string;
        }

        return $new;
    }

    public function asDriverStatus($data, $status)
    {
        $result = $data->statusDriver[$status];

        if ($status == 0) {$result = '<p class="text-info">'.$data->statusDriver[$status].'</p>';};
        if ($status == 1) {$result = '<p class="text-success">'.$data->statusDriver[$status].'</p>';};
        if ($status == 2) {$result = '<p class="text-danger">'.$data->statusDriver[$status].'</p>';};
        if ($status == 3) {$result = '<p class="text-warning">'.$data->statusDriver[$status].'</p>';};

        return $result;
    }

    public function asBeginDay(int $date): int
    {
        return Yii::$app->formatter->asTimestamp(date("Y-m-d 00:00:00 +05", $date));
    }

    public function asEndDay(int $date): int
    {
        return Yii::$app->formatter->asTimestamp(date("Y-m-d 23:59:59 +05", $date));
    }

    public function asNextShift():int
    {
        $dayShift = $this->asBeginDay(time())+(9*60*60);
        $nightShift = $this->asBeginDay(time())+(21*60*60);

        if ($dayShift > Yii::$app->formatter->asTimestamp(time())){
            return $dayShift;
        }

        if ($nightShift < Yii::$app->formatter->asTimestamp(time())){
            return $dayShift+(24*60*60);
        }

        return $nightShift;
    }

}
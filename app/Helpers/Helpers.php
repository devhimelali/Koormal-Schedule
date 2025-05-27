<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

function getColumnName($column) {
    return DB::table('column_names')->where('column_name', $column)->first()->column_title;
}

if (!function_exists('formatDataValue')) {
    function formatDataValue($value) {
        $html = '';
        if (str_contains($value, "uploads")) {

            if (strpos($value, "\n") !== false) {
                $array = explode("\n", $value);

                foreach ($array as $val) {
                    $html .= checkFormatValue($val);
                }
            } else {
                $html .= checkFormatValue($value);
            }
            return $html;
        }

        if (is_string($value) && isValidDate($value)) {
            try {
                $parsedDate = \Carbon\Carbon::parse($value);
                return $parsedDate->format('d/m/Y');
            } catch (\Exception $e) {
            }
        }

        return e($value);
    }
}

if (!function_exists('isValidDate')) {
    function isValidDate($value) {
        $dateFormats = ['d/m/Y'];
        foreach ($dateFormats as $format) {
            if (Carbon::hasFormat($value, $format)) {
                Carbon::createFromFormat('d/m/Y', $value);
            }
        }
        return false;
    }
}

if (!function_exists('checkFormatValue')) {
    function checkFormatValue($value) {
        if (filter_var($value, FILTER_VALIDATE_URL)) {

            $imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
            foreach ($imageTypes as $imageType) {
                if (strpos($value, $imageType) !== false) {
                    return '<a href="' . $value . '" target="_blank">'
                        . '<img src="' . $value . '" alt="Image" style="max-width: 100px; max-height: 100px; padding: 28px 10px 0 10px;"></a>';
                }
            }

            if (preg_match('/\.(mp4|avi|mov|wmv)$/i', $value)) {
                return '<a class="btn btn-sm text-white btn-primary" data-bs-toggle="modal" data-bs-target="#viewVideo" data-href="'
                    . $value . '" type="button">Watch Video</a>';
            }

            return '<a href="' . $value . '" target="_blank">' . $value . '</a>';
        }
        return e($value);
    }
}

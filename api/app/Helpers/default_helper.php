<?php

/**
 * Get Statuses
 */
if (!function_exists('getStatuses')) {
    function getStatuses()
    {
        return [
            0 => 'Inctive',
            1 => 'Active',
        ];
    }
}

if (!function_exists('getQStatuses')) {
    function getQStatuses()
    {
        return [
            0 => 'No',
            1 => 'Yes',
        ];
    }
}

/**
 * View date Picker Format Function
 */
if (!function_exists('date_picker_format')) {
    function date_picker_format($date)
    {
        return date('d/m/Y', strtotime($date));
    }
}

/**
 * View date Format Function
 */
if (!function_exists('view_date_format')) {
    function view_date_format($date)
    {
        return date('d/M/Y', strtotime($date));
    }
}

/**
 * change given date to db date format
 */
if (!function_exists('dbdate_format')) {
    function dbdate_format($date)
    {
        $rdate = str_replace('/', '-', $date);
        $gdate = strtotime($rdate);
        return date('Y-m-d', $gdate);
    }
}

/**
 * find date difference
 */
if (!function_exists('date_difference')) {
    function date_difference($date)
    {
        $date1 = date_create($date);
        $date2 = date_create(date('Y-m-d'));
        $diff = date_diff($date1, $date2);
        return $diff;
    }
}

/**
 * Form time string
 */
if (!function_exists('form_textual_timestring')) {
    function form_textual_timestring($timestring, $sign = '', $for = 'php')
    {
        $textualTimeString = '';
        $newTimeString = '';

        if (strpos($timestring, ':')) {
            $timeStrArr = explode(':', $timestring);
            if (count($timeStrArr) == 3) {
                $hour = (int) $timeStrArr[0];
                $minute = (int) $timeStrArr[1];
                $second = (int) $timeStrArr[2];

                switch ($for) {
                    case 'php':
                        if ($hour) {
                            $newTimeString .= ' ' . $sign . $hour . ' hours';
                        }

                        if ($minute) {
                            $newTimeString .=
                                ' ' . $sign . $minute . ' minutes';
                        }

                        if ($second) {
                            $newTimeString .=
                                ' ' . $sign . $second . ' seconds';
                        }
                        break;
                    case 'sql':
                        if ($hour) {
                            $newTimeString .= ' ' . $sign . $hour . ' HOUR';
                        }

                        if ($minute) {
                            $newTimeString .= ' ' . $sign . $minute . ' MINUTE';
                        }

                        if ($second) {
                            $newTimeString .= ' ' . $sign . $second . ' SECOND';
                        }
                        break;
                    default:
                }

                $textualTimeString = trim($newTimeString);
            }
        } else {
            $textualTimeString = $sign . $timestring;
        }

        return $textualTimeString;
    }
}

if (!function_exists('formDateTime')) {
    function formDateTime($date, $time)
    {
        return date('Y-m-d H:i:s', strtotime($date . ' ' . $time));
    }
}

if (!function_exists('calculateTime')) {
    function calculateTime($datetime, $timings)
    {
        $new_datetime = $datetime;
        if ($timings) {
            foreach ($timings as $timing) {
                if ($timing) {
                    $textual_duration = form_textual_timestring(
                        $timing[0],
                        $timing[1]
                    );
                    $new_datetime = date(
                        'Y-m-d H:i:s',
                        strtotime($textual_duration, strtotime($new_datetime))
                    );
                }
            }
        }
        return $new_datetime;
    }
}

/**
 * Settings
 */
if (!function_exists('getSettings')) {
    function getSettings($code = 'config_system')
    {
        $settingsData = [];
        $modelSetting = model('SettingModel', false);
        $settings = $modelSetting->getSettings($code);

        return $settings;
    }
}

if (!function_exists('getSetting')) {
    function getSetting($keyword, $code = 'config_system')
    {
        $modelSetting = model('SettingModel', false);
        $setting = $modelSetting->getSetting($code, $keyword);
        if ($setting) {
            return $setting->value;
        } else {
            return '';
        }
    }
}

/**
 * Get distance
 */
if (!function_exists('getDistance')) {
    function getDistance(
        $locationFrom,
        $locationTo,
        $unit = 'mi',
        $wunit = true
    ) {
        $latitudeFrom = $locationFrom['latitude'];
        $longitudeFrom = $locationFrom['longitude'];
        $latitudeTo = $locationTo['latitude'] ?? 0;
        $longitudeTo = $locationTo['longitude'] ?? 0;

        // Calculate distance between latitude and longitude
        $theta = $longitudeFrom - $longitudeTo;
        $dist =
            sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +
            cos(deg2rad($latitudeFrom)) *
                cos(deg2rad($latitudeTo)) *
                cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        // Convert unit and return distance
        $unit = strtolower($unit);
        if ($unit == 'km') {
            $distance = round($miles * 1.609344, 2);
            $distanceUnit = 'km';
        } elseif ($unit == 'm') {
            $distance = round($miles * 1609.344, 2);
            $distanceUnit = 'meters';
        } else {
            $distance = round($miles, 2);
            $distanceUnit = 'miles';
        }

        if ($wunit) {
            return $distance . ' ' . $distanceUnit;
        } else {
            return $distance;
        }
    }
}

<?php
/*
Insert to functions.php
Use [toan_calendar] to view
*/

add_shortcode('toan_calendar','toan_calendar_func');
function toan_calendar_func(){
    function INT($d) {
        return floor($d);
    }

    function jdFromDate($dd, $mm, $yy) {
        $a = INT((14 - $mm) / 12);
        $y = $yy + 4800 - $a;
        $m = $mm + 12 * $a - 3;
        $jd = $dd + INT((153 * $m + 2) / 5) + 365 * $y + INT($y / 4) - INT($y / 100) + INT($y / 400) - 32045;
        if ($jd < 2299161) {
            $jd = $dd + INT((153* $m + 2)/5) + 365 * $y + INT($y / 4) - 32083;
        }
        return $jd;
    }

    function jdToDate($jd) {
        if ($jd > 2299160) { // After 5/10/1582, Gregorian calendar
            $a = $jd + 32044;
            $b = INT((4*$a+3)/146097);
            $c = $a - INT(($b*146097)/4);
        } else {
            $b = 0;
            $c = $jd + 32082;
        }
        $d = INT((4*$c+3)/1461);
        $e = $c - INT((1461*$d)/4);
        $m = INT((5*$e+2)/153);
        $day = $e - INT((153*$m+2)/5) + 1;
        $month = $m + 3 - 12*INT($m/10);
        $year = $b*100 + $d - 4800 + INT($m/10);
        //echo "day = $day, month = $month, year = $year\n";
        return array($day, $month, $year);
    }

    function getNewMoonDay($k, $timeZone) {
        $T = $k/1236.85; // Time in Julian centuries from 1900 January 0.5
        $T2 = $T * $T;
        $T3 = $T2 * $T;
        $dr = M_PI/180;
        $Jd1 = 2415020.75933 + 29.53058868*$k + 0.0001178*$T2 - 0.000000155*$T3;
        $Jd1 = $Jd1 + 0.00033*sin((166.56 + 132.87*$T - 0.009173*$T2)*$dr); // Mean new moon
        $M = 359.2242 + 29.10535608*$k - 0.0000333*$T2 - 0.00000347*$T3; // Sun's mean anomaly
        $Mpr = 306.0253 + 385.81691806*$k + 0.0107306*$T2 + 0.00001236*$T3; // Moon's mean anomaly
        $F = 21.2964 + 390.67050646*$k - 0.0016528*$T2 - 0.00000239*$T3; // Moon's argument of latitude
        $C1=(0.1734 - 0.000393*$T)*sin($M*$dr) + 0.0021*sin(2*$dr*$M);
        $C1 = $C1 - 0.4068*sin($Mpr*$dr) + 0.0161*sin($dr*2*$Mpr);
        $C1 = $C1 - 0.0004*sin($dr*3*$Mpr);
        $C1 = $C1 + 0.0104*sin($dr*2*$F) - 0.0051*sin($dr*($M+$Mpr));
        $C1 = $C1 - 0.0074*sin($dr*($M-$Mpr)) + 0.0004*sin($dr*(2*$F+$M));
        $C1 = $C1 - 0.0004*sin($dr*(2*$F-$M)) - 0.0006*sin($dr*(2*$F+$Mpr));
        $C1 = $C1 + 0.0010*sin($dr*(2*$F-$Mpr)) + 0.0005*sin($dr*(2*$Mpr+$M));
        if ($T < -11) {
            $deltat= 0.001 + 0.000839*$T + 0.0002261*$T2 - 0.00000845*$T3 - 0.000000081*$T*$T3;
        } else {
            $deltat= -0.000278 + 0.000265*$T + 0.000262*$T2;
        };
        $JdNew = $Jd1 + $C1 - $deltat;
        //echo "JdNew = $JdNew\n";
        return INT($JdNew + 0.5 + $timeZone/24);
    }

    function getSunLongitude($jdn, $timeZone) {
        $T = ($jdn - 2451545.5 - $timeZone/24) / 36525; // Time in Julian centuries from 2000-01-01 12:00:00 GMT
        $T2 = $T * $T;
        $dr = M_PI/180; // degree to radian
        $M = 357.52910 + 35999.05030*$T - 0.0001559*$T2 - 0.00000048*$T*$T2; // mean anomaly, degree
        $L0 = 280.46645 + 36000.76983*$T + 0.0003032*$T2; // mean longitude, degree
        $DL = (1.914600 - 0.004817*$T - 0.000014*$T2)*sin($dr*$M);
        $DL = $DL + (0.019993 - 0.000101*$T)*sin($dr*2*$M) + 0.000290*sin($dr*3*$M);
        $L = $L0 + $DL; // true longitude, degree
        //echo "\ndr = $dr, M = $M, T = $T, DL = $DL, L = $L, L0 = $L0\n";
        $L = $L*$dr;
        $L = $L - M_PI*2*(INT($L/(M_PI*2))); // Normalize to (0, 2*PI)
        return INT($L/M_PI*6);
    }

    function getLunarMonth11($yy, $timeZone) {
        $off = jdFromDate(31, 12, $yy) - 2415021;
        $k = INT($off / 29.530588853);
        $nm = getNewMoonDay($k, $timeZone);
        $sunLong = getSunLongitude($nm, $timeZone); // sun longitude at local midnight
        if ($sunLong >= 9) {
            $nm = getNewMoonDay($k-1, $timeZone);
        }
        return $nm;
    }

    function getLeapMonthOffset($a11, $timeZone) {
        $k = INT(($a11 - 2415021.076998695) / 29.530588853 + 0.5);
        $last = 0;
        $i = 1; // We start with the month following lunar month 11
        $arc = getSunLongitude(getNewMoonDay($k + $i, $timeZone), $timeZone);
        do {
            $last = $arc;
            $i = $i + 1;
            $arc = getSunLongitude(getNewMoonDay($k + $i, $timeZone), $timeZone);
        } while ($arc != $last && $i < 14);
        return $i - 1;
    }

    /* Comvert solar date dd/mm/yyyy to the corresponding lunar date */
    function convertSolar2Lunar($dd, $mm, $yy, $timeZone) {
        $dayNumber = jdFromDate($dd, $mm, $yy);
        $k = INT(($dayNumber - 2415021.076998695) / 29.530588853);
        $monthStart = getNewMoonDay($k+1, $timeZone);
        if ($monthStart > $dayNumber) {
            $monthStart = getNewMoonDay($k, $timeZone);
        }
        $a11 = getLunarMonth11($yy, $timeZone);
        $b11 = $a11;
        if ($a11 >= $monthStart) {
            $lunarYear = $yy;
            $a11 = getLunarMonth11($yy-1, $timeZone);
        } else {
            $lunarYear = $yy+1;
            $b11 = getLunarMonth11($yy+1, $timeZone);
        }
        $lunarDay = $dayNumber - $monthStart + 1;
        $diff = INT(($monthStart - $a11)/29);
        $lunarLeap = 0;
        $lunarMonth = $diff + 11;
        if ($b11 - $a11 > 365) {
            $leapMonthDiff = getLeapMonthOffset($a11, $timeZone);
            if ($diff >= $leapMonthDiff) {
                $lunarMonth = $diff + 10;
                if ($diff == $leapMonthDiff) {
                    $lunarLeap = 1;
                }
            }
        }
        if ($lunarMonth > 12) {
            $lunarMonth = $lunarMonth - 12;
        }
        if ($lunarMonth >= 11 && $diff < 4) {
            $lunarYear -= 1;
        }
        return array($lunarDay, $lunarMonth, $lunarYear, $lunarLeap);
    }

    /* Convert a lunar date to the corresponding solar date */
    function convertLunar2Solar($lunarDay, $lunarMonth, $lunarYear, $lunarLeap, $timeZone) {
        if ($lunarMonth < 11) {
            $a11 = getLunarMonth11($lunarYear-1, $timeZone);
            $b11 = getLunarMonth11($lunarYear, $timeZone);
        } else {
            $a11 = getLunarMonth11($lunarYear, $timeZone);
            $b11 = getLunarMonth11($lunarYear+1, $timeZone);
        }
        $k = INT(0.5 + ($a11 - 2415021.076998695) / 29.530588853);
        $off = $lunarMonth - 11;
        if ($off < 0) {
            $off += 12;
        }
        if ($b11 - $a11 > 365) {
            $leapOff = getLeapMonthOffset($a11, $timeZone);
            $leapMonth = $leapOff - 2;
            if ($leapMonth < 0) {
                $leapMonth += 12;
            }
            if ($lunarLeap != 0 && $lunarMonth != $leapMonth) {
                return array(0, 0, 0);
            } else if ($lunarLeap != 0 || $off >= $leapOff) {
                $off += 1;
            }
        }
        $monthStart = getNewMoonDay($k + $off, $timeZone);
        return jdToDate($monthStart + $lunarDay - 1);
    }

    function alhn() {
        $arr = array_slice(convertSolar2Lunar(date('d'), date('m'), date('Y'), 7), 0, 3);
        $arr[0] = str_pad($arr[0], 2, '0', STR_PAD_LEFT);
        $arr[1] = str_pad($arr[1], 2, '0', STR_PAD_LEFT);
        unset($arr[2]);
        return implode("/", $arr);
    }
    ob_start();
    ?>
    <div class="toan_calendar">
        <div class="todayDay"><?php echo date('d');?></div>
        <div class="todayDate">
            <span class="todayDate_duonglich"><?php echo sprintf(__('Tháng %s','devvn'),date('m/Y'))?></span>
            <span class="todayDate_amlich"><?php echo sprintf(__('%s (âm lịch)','devvn'),alhn())?></span>
        </div>
    </div>
    <style>
        .toan_calendar,
        .toan_calendar *{
            color: #000;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }
        .toan_calendar {
            width: 140px;
            float: left;
            overflow: hidden;
        }
        .todayDay{
            background: url('data:image/png;base64,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') no-repeat center center;
            width: 32px;
            height: 33px;
            color: #007cc2;
            font-weight: 700;
            text-align: center;
            white-space: nowrap;
            line-height: 33px;
            font-size: 18px;
            float: left;
        }
        .todayDate {
            margin: 0 0 0 40px;
            line-height: 17px;
        }
        span.todayDate_duonglich {
            color: #007cc2;
        }
        span.todayDate_amlich {
            font-size: 11px;
            color: #989696;
        }
    </style>
    <?php
    return ob_get_clean();
}

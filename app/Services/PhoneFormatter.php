<?php

namespace App\Services;

class PhoneFormatter
{
    /**
     * 格式化台灣電話（手機、市話、分機）
     * 格式化為漂亮顯示版 (02-2345-6789 #123)
     */
    public static function format(?string $raw): string
    {
        if (empty($raw)) {
            return '';
        }

        // 1. 拆分分機 (捕捉 #, ext, 轉, x 等關鍵字)
        $parts = preg_split('/(#|ext|轉|x)/i', $raw);
        $mainRaw = $parts[0];
        $extension = isset($parts[1]) ? trim($parts[1]) : null;

        // 2. 清理主號碼，只留數字
        $clean = preg_replace('/\D/', '', $mainRaw);
        $length = strlen($clean);
        $formatted = $clean;

        // 3. 判斷格式
        if ($length === 10 && str_starts_with($clean, '09')) {
            // 手機：09xx-xxx-xxx
            $formatted = substr($clean, 0, 4) . '-' . substr($clean, 4, 3) . '-' . substr($clean, 7);
        } elseif (str_starts_with($clean, '0')) {
            // 市話
            $areaCode2 = ['02', '03', '04', '05', '06', '07', '08'];
            $prefix2 = substr($clean, 0, 2);

            if (in_array($prefix2, $areaCode2)) {
                // 兩碼區碼 (02-2345-6789)
                $mainNumber = substr($clean, 2);
                $mid = (strlen($mainNumber) >= 8) ? 4 : 3;
                $formatted = $prefix2 . '-' . substr($mainNumber, 0, $mid) . '-' . substr($mainNumber, $mid);
            } else {
                // 三碼區碼 (037-123-456)
                $formatted = substr($clean, 0, 3) . '-' . substr($clean, 3, 3) . '-' . substr($clean, 6);
            }
        }

        // 4. 黏回分機
        return $extension ? "{$formatted} #{$extension}" : $formatted;
    }

    /**
     * 格式化為純數字撥號版 (0223456789,123)
     * 為什麼用逗號 ,？
     * 一個逗號 (,)：在電信標準中代表「暫停 2 秒」。
     * 當手機撥打 0223456789,123 時，它會先撥通主號，等待 2 秒（通常此時對方總機已接起並要求輸入分機），然後自動送出 123。
     */
    public static function dial(?string $raw): string
    {
        if (empty($raw)) {
            return '';
        }

        // 1. 拆分分機
        $parts = preg_split('/(#|ext|轉|x)/i', $raw);
        $mainRaw = $parts[0];
        $extension = isset($parts[1]) ? trim($parts[1]) : null;

        // 2. 清理主號碼只留數字
        $cleanMain = preg_replace('/\D/', '', $mainRaw);

        // 3. 如果有分機，撥號時用逗號(,)連接，手機會等待接通後自動輸入分機
        if ($extension) {
            $cleanExt = preg_replace('/\D/', '', $extension);
            return $cleanMain . ',' . $cleanExt;
        }

        return $cleanMain;
    }

}
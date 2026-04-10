<?php

namespace App\Enums;

enum AttendanceStatus: int
{
    case No = 0;
    case Yes = 1;
    case Maybe = 2;
    case Unreachable = 3;
    case Partial = 4;

    // 取得中文標籤
    public function label(): string
    {
        return match($this) {
            self::No => '不參加',
            self::Yes => '參加',
            self::Maybe => '尚未決定',
            self::Unreachable => '聯絡不上',
            self::Partial => '無法全程',
        };
    }

    // 取得對應的 CSS class
    public function colorClass(): string
    {
        return match($this) {
            self::Yes => 'text-success',
            self::No => 'text-danger',
            self::Maybe, self::Unreachable, self::Partial => 'text-secondary',
        };
    }
}

<?php

namespace App\Enums;

enum Gender: string
{
    case Male = 'M';
    case Female = 'F';
    case NonConforming = 'NC'; //Non-binary / intersex / gender non-conforming 非常規性別
    case NotToSpecify = 'NS'; //Prefer not to specify / prefer to self-describe 不提供

    public function label(): string
    {
        return match($this) {
            self::Male => '男',
            self::Female => '女',
            self::NonConforming => '非常規性別',
            self::NotToSpecify => '不提供',
        };
    }

    // 新增：從標籤（男/女）找到對應的 Enum 實例
    public static function fromLabel(string $label): ?self
    {
        foreach (self::cases() as $case) {
            if ($case->label() === $label) {
                return $case;
            }
        }
        return null; // 或者回傳一個預設值，例如 self::NotToSpecify
    }
}
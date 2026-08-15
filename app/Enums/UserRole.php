<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case APPLICANT = 'applicant';
    case REVIEWER = 'reviewer';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::APPLICANT => 'Pemohon (Applicant)',
            self::REVIEWER => 'Penelaah (Reviewer)',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::ADMIN => 'bg-purple-100 text-purple-800 border-purple-200',
            self::APPLICANT => 'bg-teal-100 text-teal-800 border-teal-200',
            self::REVIEWER => 'bg-blue-100 text-blue-800 border-blue-200',
        };
    }

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

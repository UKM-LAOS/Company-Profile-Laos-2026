<?php

namespace Database\Seeders;

use App\Models\Shortlink;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ShortlinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::first();
        $userId = $admin?->id ?? 1;

        $shortlinks = [
            [
                'user_id' => $userId,
                'destination_url' => 'https://forms.gle/laos-recruitment-2026',
                'short_code' => 'oprec-2026',
                'click_count' => 128,
                'is_active' => true,
                'expires_at' => Carbon::now()->addMonths(6),
            ],
            [
                'user_id' => $userId,
                'destination_url' => 'https://github.com/UKM-LAOS',
                'short_code' => 'github',
                'click_count' => 450,
                'is_active' => true,
                'expires_at' => null,
            ],
            [
                'user_id' => $userId,
                'destination_url' => 'https://instagram.com/ukmlaos',
                'short_code' => 'ig',
                'click_count' => 890,
                'is_active' => true,
                'expires_at' => null,
            ],
            [
                'user_id' => $userId,
                'destination_url' => 'https://discord.gg/ukmlaos-community',
                'short_code' => 'komunitas',
                'click_count' => 312,
                'is_active' => true,
                'expires_at' => null,
            ],
        ];

        foreach ($shortlinks as $link) {
            Shortlink::firstOrCreate(
                ['short_code' => $link['short_code']],
                $link
            );
        }
    }
}


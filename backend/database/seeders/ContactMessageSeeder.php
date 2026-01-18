<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContactMessage;
use App\Models\RateLimitViolation;
use Carbon\Carbon;

class ContactMessageSeeder extends Seeder
{
    /**
     * Seed contact messages and some spam violations for testing.
     */
    public function run(): void
    {
        $names = [
            ['Mario', 'Lassu'],
            ['Jana', 'Kováčová'],
            ['Peter', 'Novák'],
            ['Mária', 'Horváthová'],
            ['Tomáš', 'Baláž'],
            ['Eva', 'Szabová'],
            ['Martin', 'Varga'],
            ['Zuzana', 'Tóthová'],
            ['Andrej', 'Molnár'],
            ['Lucia', 'Kučerová'],
        ];

        $messages = [
            'Dobrý deň, zaujíma ma 3-izbový byt na 2. poschodí. Je ešte voľný? Ďakujem za info.',
            'Chcel by som sa informovať o cene parkovacieho miesta v garáži.',
            'Máte ešte voľné byty s balkónom orientovaným na juh?',
            'Prosím o zaslanie cenníka a pôdorysov dostupných bytov.',
            'Kedy je možné si dohodnúť obhliadku bytov?',
            'Zaujíma ma financovanie hypotékou - spolupracujete s nejakou bankou?',
            'Je možné rezervovať byt bez zálohy?',
            'Aká je predpokladaná doba kolaudácie?',
            'Aké sú mesačné náklady na byt?',
            'Máte ešte voľné 2-izbové byty?',
            'Prosím o kontakt - chcem sa dohodnúť na stretnutí.',
            'Je súčasťou bytu aj pivnica alebo komora?',
        ];

        $ips = [
            '192.168.1.100',
            '10.0.0.50',
            '172.16.0.25',
            '192.168.0.1',
            '10.10.10.10',
        ];

        // Create messages for last 30 days
        for ($day = 30; $day >= 0; $day--) {
            $date = Carbon::now()->subDays($day);
            
            // Random 1-4 messages per day
            $msgCount = rand(1, 4);
            
            for ($i = 0; $i < $msgCount; $i++) {
                $name = $names[array_rand($names)];
                
                ContactMessage::create([
                    'first_name' => $name[0],
                    'last_name' => $name[1],
                    'email' => strtolower($name[0]) . '.' . strtolower($name[1]) . '@example.com',
                    'phone' => '+421' . rand(900000000, 999999999),
                    'message' => $messages[array_rand($messages)],
                    'is_read' => $day > 7 ? true : (rand(0, 1) == 1),
                    'ip_address' => $ips[array_rand($ips)],
                    'created_at' => $date->copy()->addHours(rand(8, 20))->addMinutes(rand(0, 59)),
                    'updated_at' => $date->copy()->addHours(rand(8, 20))->addMinutes(rand(0, 59)),
                ]);
            }
        }

        // Add some spam violations for specific days
        $spamIps = [
            '45.33.32.156',
            '185.142.236.0',
            '91.213.50.0',
        ];

        // Spam attempts on specific days
        $spamDays = [2, 5, 8, 12, 15, 20, 25];
        
        foreach ($spamDays as $day) {
            $date = Carbon::now()->subDays($day);
            $ip = $spamIps[array_rand($spamIps)];
            
            // Multiple violations from same IP
            $violationCount = rand(3, 8);
            
            for ($i = 0; $i < $violationCount; $i++) {
                RateLimitViolation::create([
                    'ip_address' => $ip,
                    'route' => 'api/contact',
                    'method' => 'POST',
                    'created_at' => $date->copy()->addMinutes(rand(0, 60)),
                    'updated_at' => $date->copy()->addMinutes(rand(0, 60)),
                ]);
            }
        }

        $this->command->info('Created contact messages and spam violations for last 30 days.');
    }
}

<?php

use Illuminate\Database\Seeder;
use App\Models\Tooth;

class TeethTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teeth = [
            [
                "jaw" => 0,
                "index" => 1,
                "icon_file_name" => "tooth-1.svg"
            ],
            [
                "jaw" => 0,
                "index" => 2,
                "icon_file_name" => "tooth-2.svg"
            ],
            [
                "jaw" => 0,
                "index" => 3,
                "icon_file_name" => "tooth-3.svg"
            ],
            [
                "jaw" => 0,
                "index" => 4,
                "icon_file_name" => "tooth-4.svg"
            ],
            [
                "jaw" => 0,
                "index" => 5,
                "icon_file_name" => "tooth-5.svg"
            ],
            [
                "jaw" => 0,
                "index" => 6,
                "icon_file_name" => "tooth-6.svg"
            ],
            [
                "jaw" => 0,
                "index" => 7,
                "icon_file_name" => "tooth-7.svg"
            ],
            [
                "jaw" => 0,
                "index" => 8,
                "icon_file_name" => "tooth-8.svg"
            ],
            [
                "jaw" => 0,
                "index" => 9,
                "icon_file_name" => "tooth-8.svg",
                "reverse" => 1
            ],
            [
                "jaw" => 0,
                "index" => 10,
                "icon_file_name" => "tooth-7.svg",
                "reverse" => 1
            ],
            [
                "jaw" => 0,
                "index" => 11,
                "icon_file_name" => "tooth-6.svg",
                "reverse" => 1
            ],
            [
                "jaw" => 0,
                "index" => 12,
                "icon_file_name" => "tooth-5.svg",
                "reverse" => 1
            ],
            [
                "jaw" => 0,
                "index" => 13,
                "icon_file_name" => "tooth-4.svg",
                "reverse" => 1
            ],
            [
                "jaw" => 0,
                "index" => 14,
                "icon_file_name" => "tooth-3.svg",
                "reverse" => 1
            ],
            [
                "jaw" => 0,
                "index" => 15,
                "icon_file_name" => "tooth-2.svg",
                "reverse" => 1
            ],
            [
                "jaw" => 0,
                "index" => 16,
                "icon_file_name" => "tooth-1.svg",
                "reverse" => 1
            ],
            [
                "jaw" => 1,
                "index" => 32,
                "icon_file_name" => "tooth-32.svg"
            ],
            [
                "jaw" => 1,
                "index" => 31,
                "icon_file_name" => "tooth-31.svg"
            ],
            [
                "jaw" => 1,
                "index" => 30,
                "icon_file_name" => "tooth-30.svg"
            ],
            [
                "jaw" => 1,
                "index" => 29,
                "icon_file_name" => "tooth-29.svg"
            ],
            [
                "jaw" => 1,
                "index" => 28,
                "icon_file_name" => "tooth-28.svg"
            ],
            [
                "jaw" => 1,
                "index" => 27,
                "icon_file_name" => "tooth-27.svg"
            ],
            [
                "jaw" => 1,
                "index" => 26,
                "icon_file_name" => "tooth-26.svg"
            ],
            [
                "jaw" => 1,
                "index" => 25,
                "icon_file_name" => "tooth-25.svg"
            ],
            [
                "jaw" => 1,
                "index" => 24,
                "icon_file_name" => "tooth-25.svg",
                "reverse" => 1
            ],
            [
                "jaw" => 1,
                "index" => 23,
                "icon_file_name" => "tooth-26.svg",
                "reverse" => 1
            ],
            [
                "jaw" => 1,
                "index" => 22,
                "icon_file_name" => "tooth-27.svg",
                "reverse" => 1
            ],
            [
                "jaw" => 1,
                "index" => 21,
                "icon_file_name" => "tooth-28.svg",
                "reverse" => 1
            ],
            [
                "jaw" => 1,
                "index" => 20,
                "icon_file_name" => "tooth-29.svg",
                "reverse" => 1
            ],
            [
                "jaw" => 1,
                "index" => 19,
                "icon_file_name" => "tooth-30.svg",
                "reverse" => 1
            ],
            [
                "jaw" => 1,
                "index" => 18,
                "icon_file_name" => "tooth-31.svg",
                "reverse" => 1
            ],
            [
                "jaw" => 1,
                "index" => 17,
                "icon_file_name" => "tooth-32.svg",
                "reverse" => 1
            ]
        ];

        foreach ($teeth as $key => $value) {
            Tooth::updateOrCreate(
                ['index' => $value['index']],
                ["jaw" => $value['jaw'], "icon_file_name" => $value['icon_file_name'], "reverse" => $value['reverse'] ?? 0]
            );
        }
    }
}

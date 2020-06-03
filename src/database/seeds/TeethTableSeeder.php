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
                "id" => 1,
                "jaw" => 0,
                "icon_file_name" => "tooth-1.svg"
            ],
            [
                "id" => 2,
                "jaw" => 0,
                "icon_file_name" => "tooth-2.svg"
            ],
            [
                "id" => 3,
                "jaw" => 0,
                "icon_file_name" => "tooth-3.svg"
            ],
            [
                "id" => 4,
                "jaw" => 0,
                "icon_file_name" => "tooth-4.svg"
            ],
            [
                "id" => 5,
                "jaw" => 0,
                "icon_file_name" => "tooth-5.svg"
            ],
            [
                "id" => 6,
                "jaw" => 0,
                "icon_file_name" => "tooth-6.svg"
            ],
            [
                "id" => 7,
                "jaw" => 0,
                "icon_file_name" => "tooth-7.svg"
            ],
            [
                "id" => 8,
                "jaw" => 0,
                "icon_file_name" => "tooth-8.svg"
            ],
            [
                "id" => 9,
                "jaw" => 0,
                "icon_file_name" => "tooth-8.svg",
                "reverse" => 1
            ],
            [
                "id" => 10,
                "jaw" => 0,
                "icon_file_name" => "tooth-7.svg",
                "reverse" => 1
            ],
            [
                "id" => 11,
                "jaw" => 0,
                "icon_file_name" => "tooth-6.svg",
                "reverse" => 1
            ],
            [
                "id" => 12,
                "jaw" => 0,
                "icon_file_name" => "tooth-5.svg",
                "reverse" => 1
            ],
            [
                "id" => 13,
                "jaw" => 0,
                "icon_file_name" => "tooth-4.svg",
                "reverse" => 1
            ],
            [
                "id" => 14,
                "jaw" => 0,
                "icon_file_name" => "tooth-3.svg",
                "reverse" => 1
            ],
            [
                "id" => 15,
                "jaw" => 0,
                "icon_file_name" => "tooth-2.svg",
                "reverse" => 1
            ],
            [
                "id" => 16,
                "jaw" => 0,
                "icon_file_name" => "tooth-1.svg",
                "reverse" => 1
            ],
            [
                "id" => 32,
                "jaw" => 1,
                "icon_file_name" => "tooth-32.svg"
            ],
            [
                "id" => 31,
                "jaw" => 1,
                "icon_file_name" => "tooth-31.svg"
            ],
            [
                "id" => 30,
                "jaw" => 1,
                "icon_file_name" => "tooth-30.svg"
            ],
            [
                "id" => 29,
                "jaw" => 1,
                "icon_file_name" => "tooth-29.svg"
            ],
            [
                "id" => 28,
                "jaw" => 1,
                "icon_file_name" => "tooth-28.svg"
            ],
            [
                "id" => 27,
                "jaw" => 1,
                "icon_file_name" => "tooth-27.svg"
            ],
            [
                "id" => 26,
                "jaw" => 1,
                "icon_file_name" => "tooth-26.svg"
            ],
            [
                "id" => 25,
                "jaw" => 1,
                "icon_file_name" => "tooth-25.svg"
            ],
            [
                "id" => 24,
                "jaw" => 1,
                "icon_file_name" => "tooth-25.svg",
                "reverse" => 1
            ],
            [
                "id" => 23,
                "jaw" => 1,
                "icon_file_name" => "tooth-26.svg",
                "reverse" => 1
            ],
            [
                "id" => 22,
                "jaw" => 1,
                "icon_file_name" => "tooth-27.svg",
                "reverse" => 1
            ],
            [
                "id" => 21,
                "jaw" => 1,
                "icon_file_name" => "tooth-28.svg",
                "reverse" => 1
            ],
            [
                "id" => 20,
                "jaw" => 1,
                "icon_file_name" => "tooth-29.svg",
                "reverse" => 1
            ],
            [
                "id" => 19,
                "jaw" => 1,
                "icon_file_name" => "tooth-30.svg",
                "reverse" => 1
            ],
            [
                "id" => 18,
                "jaw" => 1,
                "icon_file_name" => "tooth-31.svg",
                "reverse" => 1
            ],
            [
                "id" => 17,
                "jaw" => 1,
                "icon_file_name" => "tooth-32.svg",
                "reverse" => 1
            ]
        ];

        foreach ($teeth as $key => $value) {
            Tooth::updateOrCreate(
                ['id' => $value['id']],
                ["jaw" => $value['jaw'], "icon_file_name" => $value['icon_file_name'], "reverse" => $value['reverse'] ?? 0]
            );
        }
    }
}

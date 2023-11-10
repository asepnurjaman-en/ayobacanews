<?php

namespace Database\Seeders;

use App\Models\Info;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Info::insert([
			[
				'type'		=> 'profile',
				'title'		=> 'Profil',
				'content' 	=> '<p>Tentang kami.</p>',
				'file'		=> 'u-r9xcFbJRs',
				'file_type'	=> 'video',
				'user_id'	=> 1
			]
        ]);
    }
}

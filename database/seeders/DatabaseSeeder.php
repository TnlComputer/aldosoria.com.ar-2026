<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // UsersTableSeeder es local y no se sube al repositorio
        if (class_exists(UsersTableSeeder::class)) {
            $this->call([UsersTableSeeder::class]);
        }
    }
}

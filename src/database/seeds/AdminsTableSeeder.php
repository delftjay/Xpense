<?php

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new Admin();
		$admin->username = 'root';
		$admin->password = 'Kingofcomedy@1999';
		$admin->save();
    }
}

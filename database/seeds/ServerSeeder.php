<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ServerSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('servers')->truncate();
        for ($i = 1; $i <= 20; $i++) {
            DB::table('servers')->insert([
                'name' => 'Server ' . $i,
                'ip_address' => '154.254.44.166',
                'dns_name' => 'https://class-test.lotuslms.com',
                'provider' => config('constants.PROVIDER.AWS'),
                'description' => 'AWS Server',
                'bbbUrl' => 'https://class-test.lotuslms.com/bigbluebutton/',
                'bbbSecret' => '8uKWDCY2rFg2lEke7SU5GljYWSStS3Ypsw6eUZFc',
                'maxUsers' => 200 + $i * 10,
                'usedUsers' => 0,
                'is_active' => true,
                'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
                'deleted_at' => null
            ]);
        }
    }

}

<?php

use Illuminate\Database\Seeder;
use App\Msloginhd;
use App\Mslogindt;
use App\Msmenu;

class LoginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu = Msmenu::all();
        $user = Msloginhd::all();

        foreach ($user as $key => $row) {
            foreach ($menu as $key => $value) {
                $detail = new Mslogindt();
                $detail->KodeUser = $row->KodeUser;
                $detail->ItemIndex = $value->ItemIndex;
                $detail->Nama = $value->Nama;
                $detail->UserUpdate = '';
                $detail->LastUpdate = date('Y-m-d H:i:s');
                $detail->save();
            }
        }

    }
}

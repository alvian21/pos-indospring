<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Trhpp;

class HppSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hpp:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hpp schedule';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $previous =  date("Y-m-d", strtotime('-1 month', strtotime(date('Y-m-d'))));
        $previousperiode = date("Ym", strtotime($previous));
        $lastperiode = date('Ym');

        $hpp = Trhpp::where('Periode', $previousperiode)->get();
        $lokasi = ['P1', 'P2'];
        foreach ($hpp as $key => $value) {
            foreach ($lokasi as $key => $row) {
                $cek = Trhpp::where('Periode', $lastperiode)->where('KodeBarang', $value->KodeBarang)->where('KodeLokasi', $row)->first();
                if (!$cek) {
                    $new = new Trhpp();
                    $new->Periode = $lastperiode;
                    $new->KodeBarang = $value->KodeBarang;
                    $new->KodeLokasi = $row;
                    $new->Hpp = $value->Hpp;
                    $new->save();
                }
            }
        }
    }
}

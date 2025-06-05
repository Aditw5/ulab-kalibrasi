<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReservasiReminderCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservasireminder:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        $tomorrowDate = date('Y-m-d', strtotime('+1 day'));
        $dataAPR = DB::table('antrianpasienregistrasi_t')
            ->select(
                'norec as norec_apr',
                'namapasien',
                'noreservasi',
                'notelepon',
                'nocmfk',
                'tanggalreservasi',
                'norujukan',
                'tglinput'
            )
            ->where('statusenabled', true)
            ->whereBetween('tanggalreservasi', [$tomorrowDate . ' 00:00', $tomorrowDate . ' 23:59'])
            ->orderByDesc('tanggalreservasi')
            ->get();
        
        Log::info("Reminder Reservasi START ");
        foreach ($dataAPR as $item) {
            try {
                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['norec_apr'] = $item->norec_apr;
                $insert = app('App\Http\Controllers\Registrasi\DaftarPasienPerjanjianCtrl')->reminderPasienReservasi($objetoRequest);
                if($insert['status'] == 201){
                    Log::info("Reminder WA pasien reservasi berhasil pada no noreservasi ". $item->noreservasi) ;
                }else{
                    Log::info("Reminder WA pasien reservasi gagal pada no reservasi ". $item->noreservasi);
                }
            } catch  (\Exception $e) {
                Log::info("Reminder WA pasien reservasi  gagal pada no noreservasi ". $item->noreservasi . " ". $e->getMessage(). " ". $e->getLine());
            }
        }
        Log::info("Reminder Reservasi END ");
        // return 0;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use GuzzleHttp\Client;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup database';

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
        $client = new Client();
        $url = config('app.api_url') . 'auth/login';
        $response = $client->post($url, [
            'form_params' => [
                'UserLogin' => "backup",
                'UserPassword' => "backup",
            ]
        ]);

        $response = $response->getBody()->getContents();
        $response = json_decode($response, true);
        if ($response['status']) {
            $this->sendBackup($response['access_token']);
        }
    }

    public function sendBackup($token)
    {
        $client = new Client();
        $date = date('Y-m-d');
        $path = Storage::path(env('APP_NAME'));
        $file = File::allFiles($path);
        arsort($file);
        $respath = "";
        $filename = "";
        foreach ($file as $key => $value) {
            if (Str::contains($value->getFilename(), $date)) {
                $respath = $value->getPathname();
                $filename = $value->getFilename();
            }
        }

        if (!empty($respath)) {
            $url = config('app.api_url') . 'backup';
            $response = $client->request('POST', $url, [
                'multipart' => [
                    [
                        'name'     => 'database',
                        'contents' => fopen($respath, 'r')
                    ],
                    [
                        'name'     => 'KodeLokasi',
                        'contents' => "CLOUD",
                    ]
                ],
                'headers' => [
                    'Authorization' => "Bearer " . $token
                ],
            ]);

            $response = $response->getBody()->getContents();
            $response = json_decode($response, true);
            if ($response['status']) {
                File::deleteDirectory(storage_path("app/" . env('APP_NAME') . "/"));
            }
        }
    }
}

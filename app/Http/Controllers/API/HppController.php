<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\HppImport;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Spatie\Backup\BackupDestination\BackupDestination;
use Spatie\Backup\Events\BackupZipWasCreated;
use Spatie\Backup\Events\BackupWasSuccessful;
use Illuminate\Support\Facades\Storage;
use Spatie\Backup\BackupDestination\Backup;

class HppController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->file('data');
        Excel::import(new HppImport, $data);
        return response()->json(['status' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function handle()
    {
        // $event = new BackupDestination(null,"/storage/app/Laravel","local");
        $date = date('Y-m-d');
        $path = Storage::path("Laravel");
        $file = File::allFiles($path);
        // Artisan::call("backup:clean");
        arsort($file);
        foreach ($file as $key => $value) {
            if (Str::contains($value->getFilename(), $date)) {
                dd($value);
            }
        }
        // dd($file[0]->getPathname());
        // session(['path_backup' => $path]);
    }
}

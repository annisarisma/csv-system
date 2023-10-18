<?php

namespace App\Http\Controllers;

use App\Events\CsvImportEvent;
use App\Models\Csv;
use App\Models\Temporary;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CsvController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Manage session
        if (Session::has('filename')) {

            $filename = Session::get('filename');
            foreach ($filename as $file_csv) {
                $temporary = Temporary::where('name', $file_csv)->first();

                if ($temporary) {
                    $file_path = storage_path('app/public/temporary/' . $temporary->name);
                    File::delete($file_path);

                    $temporary->delete();
                }
            }
            Session::remove('filename');
        }

        return view('csv-create', [
            'title' => 'CSV Create'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function temporary_store(Request $request)
    {
        // If Input file is CSV
        if ($request->hasFile('csv')) {
            $csv_file = $request->file('csv');
            $csv_filename = hexdec(uniqid()) . '.' . $csv_file->extension();
            $csv_file->storeAs('/public/temporary/', $csv_filename);

            Temporary::create([
                'name' => $csv_filename
            ]);
            Session::push('filename', $csv_filename);
            return $csv_filename;
        }
    }

    public function temporary_destroy(Request $request)
    {
        $temporary = Temporary::where('name', $request->csv)->first();
        if ($temporary) {
            $file_path = storage_path('app/public/temporary/' . $temporary->name);
            if (File::exists($file_path)) {
                File::delete($file_path);

                Temporary::where(['name' => $temporary->name])->delete();
                return 'deleted';
            } else {
                return 'not found';
            }
        }
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'csv' => 'required',
            ],
            [
                'csv.required' => '.csv file should be uploaded',
            ]
        );

        $filename = Session::get('filename');

        // Search session one by one
        $temporary = Temporary::where('name', $filename)->first();
        $extension = pathinfo($temporary->name, PATHINFO_EXTENSION);
        Storage::move('public/temporary/' . $temporary->name, 'public/csv_file/' . $temporary->name);

        // Check if file extension is correct
        if ($extension == 'csv') {

            // Store csvFile to database
            $csvFile = new Csv([
                'user_id' => auth()->id(),
                'filename' => $temporary->name,
                'status' => 'Pending',
            ]);
            $csvFile->save();

            // Broadcast Event
            $user_search = User::where('id', auth()->id())->first();
            $name = $user_search->username;
            event(new CsvImportEvent($name));
            
            return redirect('/csv-create')->with('success-alert', [
                'message' => 'Upload file successfully'
            ]);
        } else {
            return view('csv-create', with('success-alert', [
                'message' => 'File extension not accept, require .csv file'
            ]));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Csv $csv)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Csv $csv)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Csv $csv)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Csv $csv)
    {
        //
    }
}

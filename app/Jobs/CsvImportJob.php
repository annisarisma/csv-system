<?php

namespace App\Jobs;

use App\Events\JobStatusEvent;
use App\Models\Csv;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use League\Csv\Reader as CsvReader;

class CsvImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $temporary;
    protected $csvFileId;

    /**
     * Create a new job instance.
     */
    public function __construct($temporary, $csvFileId)
    {
        $this->temporary = $temporary;
        $this->csvFileId = $csvFileId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $temporary = $this->temporary;
        $csvFileId = $this->csvFileId;

        $this->broadcastStatus($csvFileId, "Processing");
        
        try {

            $file_path = storage_path('app/public/csv_file/' . $temporary);

            //load the CSV document from a file path
            $csv = CsvReader::createFromPath($file_path, 'r');
            $csv->setHeaderOffset(0);

            // Read record one by one
            foreach ($csv as $record) {
                $duplicate_record = Product::where('unique_key', $record['UNIQUE_KEY'])->first();
                if ($duplicate_record == null) {
                    // If record doesnt exist, create new entries
                    $product = new Product([
                        'csv_id' => $csvFileId,
                        'unique_key' => $record['UNIQUE_KEY'],
                        'product_title' => $record['PRODUCT_TITLE'],
                        'product_description' => $record['PRODUCT_DESCRIPTION'],
                        'style' => $record['STYLE#'],
                        'sanmar_mainframe_color' => $record['SANMAR_MAINFRAME_COLOR'],
                        'size' => $record['SIZE'],
                        'color_name' => $record['COLOR_NAME'],
                        'piece_price' => $record['PIECE_PRICE'],
                    ]);
    
                    $product->save();
                } else {
                    // If record already exist, update old entries
                    $duplicate_record->update([
                        'unique_key' => $record['UNIQUE_KEY'],
                        'product_title' => $record['PRODUCT_TITLE'],
                        'product_description' => $record['PRODUCT_DESCRIPTION'],
                        'style' => $record['STYLE#'],
                        'sanmar_mainframe_color' => $record['SANMAR_MAINFRAME_COLOR'],
                        'size' => $record['SIZE'],
                        'color_name' => $record['COLOR_NAME'],
                        'piece_price' => $record['PIECE_PRICE'],
                    ]);
                }
            }
            $this->broadcastStatus($csvFileId, "Finished");
        } catch (\Exception $e) {
            $this->broadcastStatus($csvFileId, "Failed");
        }
    }

    protected function broadcastStatus($csvFileId, $status)
    {
        $csvFile = Csv::where('id', $csvFileId)->first();
        $csvFile->update([
            'status' => $status
        ]);
        event(new JobStatusEvent($csvFile->id, $csvFile->status));
    }
}

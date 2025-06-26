<?php

namespace App\Jobs;

use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class GenerateReceiptPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Sale $sale)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->sale->load(['shop', 'items.fertilizer']);

        $pdf = Pdf::loadView('pdf.receipt', [
            'sale' => $this->sale,
            'shop' => $this->sale->shop
        ]);

        $content = $pdf->download()->getOriginalContent();

        Storage::disk('local')->put('receipts/' . $this->sale->receipt_no . '.pdf', $content);
    }
}

<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RecapitulationExport implements FromView
{
    private $dataRecap;

    public function __construct($dataRecap)
    {
        $this->dataRecap = $dataRecap;
    }

    public function view(): View
    {
        return view('guest.recap.xlsx', $this->dataRecap);
    }
}

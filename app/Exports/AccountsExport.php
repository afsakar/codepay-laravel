<?php

namespace App\Exports;

use App\Models\Account;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AccountsExport implements FromQuery, WithHeadings, ShouldAutoSize
{

    use Exportable;

    public $selected = [];

    public function __construct($ids)
    {
        $this->selected = $ids;
    }

    public function headings(): array
    {
        return [
            'Account Name',
            'Description',
            'Balance',
            'Currency',
        ];
    }

    public function query()
    {
        return Account::query()->select('name', 'description', 'balance', 'currency_id')->where(function ($query) {
            foreach ($this->selected as $id) {
                $query->orWhere('id', $id);
            }
        });
    }
}

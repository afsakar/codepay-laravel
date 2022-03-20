<?php

namespace App\Http\Livewire\Sales;

use App\Http\Livewire\DataTable\WithBulkActions;
use App\Http\Livewire\DataTable\WithCachedRows;
use App\Http\Livewire\DataTable\WithPerPagePagination;
use App\Http\Livewire\DataTable\WithSorting;
use App\Http\Livewire\DataTable\WithToastNotification;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\WithHolding;
use Carbon\Carbon;
use Livewire\Component;

class InvoiceList extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows, WithToastNotification;

    public $search = "";
    public Invoice $editing;
    public $createMode = false;
    public $deleteModal = false;
    public $editingModal = false;
    public $customers;
    public $withholdings;
    public $withholding;

    protected $queryString = [];

    public $filters = [
        'search' => "",
        'status' => "",
    ];

    public function rules()
    {
        return [
            'editing.company_id' => 'required|exists:companies,id',
            'editing.customer_id' => 'required|exists:customers,id',
            'editing.withholding_id' => 'required|in:'.$this->withholding,
            'editing.status' => 'required|in:draft,paid,cancelled',
            'editing.issue_date' => 'required|date',
            'editing.notes' => 'nullable',
            'editing.invoice_number' => 'required|unique:invoices,invoice_number,' . $this->editing->id,
            'editing.created_by' => 'required|exists:users,id',
            'editing.discount' => 'nullable|numeric',
        ];
    }

    public function validationAttributes()
    {
        return [
            'editing.customer_id' => __('Customer'),
            'editing.withholding_id' => __('Withholding Tax Rate'),
            'editing.issue_date' => __('Issue Date'),
            'editing.notes' => __('Notes'),
            'editing.invoice_number' => __('Invoice Number'),
            'editing.created_by' => __('Created By'),
            'editing.status' => __('Status'),
            'editing.discount' => __('Discount'),
        ];
    }

    public function mount()
    {
        $this->editing = $this->makeBlankInvoice();
        $this->customers = Customer::where('status', 'active')->get();
        $this->withholdings = WithHolding::where('status', 'active')->get();
        $this->withholding = WithHolding::query()->select('id')->pluck('id')->implode(',');
    }

    public function makeBlankInvoice()
    {
        return Invoice::make([
            'status' => 'draft',
            'company_id' => get_company_info()->id,
            'customer_id' => "",
            'withholding_id' => 0,
            'issue_date' => null,
            'notes' => "",
            'invoice_number' => "",
            'created_by' => auth()->user()->id,
            'discount' => 0,
        ]);
    }

    /* Editing / Creating / Deleting / Exporting */
    public function edit(Invoice $invoice)
    {
        $this->useCachedRows();
        if($this->editing->isNot($invoice)) $this->editing = $invoice;
        $this->editingModal = true;
    }

    public function create()
    {
        $this->useCachedRows();
        $this->createMode = true;
        if($this->editing->getKey()) $this->editing = $this->makeBlankInvoice();
        $this->editingModal = true;
    }

    public function save()
    {
        $this->validate();
        $this->editing->save();
        if($this->createMode) {
            $this->createMode = false;
            $this->notify('Record has been created successfully!');
        }else{
            $this->notify('Record has been updated successfully!');
        }
        $this->editingModal = false;
        $this->mount();
        $this->render();
    }

    public function close()
    {
        $this->editingModal = false;
        $this->createMode = false;
        $this->makeBlankInvoice();
        $this->resetValidation();
    }

    public function deleteSelected()
    {
        try {
            $this->selectedRowsQuery->delete();
            $this->notify('Selected records have been deleted successfully!');
        }catch (\Exception $e) {
            $this->notify('You can\'t delete '. $this->editing->name .', because it is being used by other items!', 'error');
        }
        $this->deleteModal = false;
        $this->selectAll = false;
        $this->selectPage = false;
        $this->selected = [];
        $this->mount();
        $this->render();
    }

    /* Editing / Creating / Deleting / Exporting */

    public function toggleFilters()
    {
        $this->useCachedRows();
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function getRowsQueryProperty()
    {
        $query = Invoice::query()->with('customer')->with('withholding')->where('company_id', get_company_info()->id)
            ->when($this->filters['status'], fn($query, $status) => $query->where('status', $status))
            ->when($this->filters['search'], fn($query, $search) => $query->where('invoice_number', 'like', '%'.$search.'%'))->orderBy('issue_date', 'desc');
        return $this->applySorting($query);
    }

    public function getRowsProperty()
    {
        return $this->cache(function() {
            return $this->applyPagination($this->rowsQuery);
        });
    }

    public function render()
    {
        return view('livewire.sales.invoice-list', [
            'invoices' => $this->rows
        ]);
    }
}

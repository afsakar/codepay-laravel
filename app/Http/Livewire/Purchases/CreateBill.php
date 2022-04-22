<?php

namespace App\Http\Livewire\Purchases;

use App\Http\Livewire\DataTable\WithToastNotification;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Material;
use Livewire\Component;
use Illuminate\Support\Collection;

class CreateBill extends Component
{
    use WithToastNotification;

    public Bill $bill;
    public Collection $items;

    public $materials, $material, $unit, $company, $material_id, $quantity, $price, $tax, $bill_id, $total, $currency, $unit_name;
    public $discount = 0, $taxTotal = 0, $grandTotal = 0, $totalPrice = 0;
    public $withholding;

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function mount(Bill $id)
    {
        $this->bill = $id;
        $this->bill_id = $id->id;
        $this->company = get_company_info();
        $this->materials = Material::select(['name', 'id'])->where('status', 'active')->get();
        $this->withholding = $this->bill->withholding()->first();

        if(BillItem::where('bill_id', $id->id)->count() > 0) {
            $this->items = collect();

            foreach(BillItem::where('bill_id', $id->id)->get() as $key => $item) {
                $this->items->push([
                    'material_id' => $item->material_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'bill_id' => $item->bill_id,
                ]);
                $this->material = Material::find($item->material_id);
                $this->unit_name[$key] = $this->material->unit->name;
                $this->tax[$key] = $this->material->tax->rate;
                $this->discount = $this->bill->discount;
                $this->total[$key] = number_format(($item->price * $item->quantity * (1 + $this->tax[$key] / 100)), 2);
                $this->taxTotal = $this->taxTotal + (($item->price * $item->quantity)) * ($this->tax[$key] / 100);
                $this->totalPrice += $item->price * $item->quantity;
            }

            $this->grandTotal = $this->totalPrice + $this->taxTotal - ($this->taxTotal * $this->withholding->rate / 10) - $this->discount;

        } else {
            $this->items = collect([$this->makeBlankItem()]);
        }

        $this->fill([
            'items' => $this->items,
        ]);
    }

    public function rules(): array
    {
        return [
            'items.*.material_id' => 'required|exists:materials,id',
            'items.*.bill_id' => 'required|exists:bills,id',
            'items.*.quantity' => 'required|numeric',
            'items.*.price' => 'required|numeric',
            'discount' => 'nullable|numeric',
        ];

    }

    public function validationAttributes(): array
    {
        return [
            'items.*.material_id' => __('Material'),
            'items.*.quantity' => __('Quantity'),
            'items.*.price' => __('Price'),
            'discount' => __('Discount'),
        ];
    }

    public function makeBlankItem(): array
    {
        return [
            'material_id' => null,
            'bill_id' => $this->bill_id,
            'quantity' => 1,
            'price' => 0,
        ];
    }

    public function addItem()
    {
        $this->items->push(new BillItem($this->makeBlankItem()));
    }

    public function removeItem($index)
    {
        $this->items->pull($index);
        $this->unit_name[$index] = null;
        $this->tax[$index] = null;
        $this->total[$index] = null;
    }

    public function save()
    {
        $this->validate();
        BillItem::where('bill_id', $this->bill_id)->get()->each->delete();
        foreach($this->items as $item) {
            $bill_item = new BillItem($item);
            $bill_item->save();
        }
        Bill::where('id', $this->bill_id)->first()->update([
            'discount' => $this->discount,
        ]);
        $this->notify('Selected records have been updated successfully!');
    }

    public function changeElements($index)
    {
        $price = $this->items[$index]['price'] ?? 1;
        $quantity = $this->items[$index]['quantity'] ?? 1;

        $this->total[$index] = number_format(($price * $quantity * (1 + $this->tax[$index] / 100)), 2);

    }

    public function changeMaterial($id, $index)
    {
        $this->material = Material::find($id);
        $this->unit_name[$index] = $this->material->unit->name ?? null;
        $this->tax[$index] = $this->material->tax->rate ?? null;

        $this->items = $this->items->map(function ($item, $key) use ($index) {
            if ($key == $index) {
                $item['material_id'] = $this->material->id ?? null;
                $item['quantity'] = $this->material->quantity ?? null;
                $item['price'] = $this->material->price ?? null;
            }
            return $item;
        });

        $this->items[$index];
        $this->changeElements($index);
    }

    public function render()
    {
        return view('livewire.purchases.create-bill');
    }
}

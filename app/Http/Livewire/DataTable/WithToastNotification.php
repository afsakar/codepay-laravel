<?php

namespace App\Http\Livewire\DataTable;

trait WithToastNotification
{
    public $message = '';
    public $type;

    public function notify($message, $type = 'success')
    {
        $this->message = $message;
        $this->type = $type;
        return $this->dispatchBrowserEvent('notify', ['message' => $message, 'type' => $type]);
    }

}

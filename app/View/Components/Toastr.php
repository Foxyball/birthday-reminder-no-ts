<?php

namespace App\View\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class Toastr extends Component
{
    public string $type;

    public string $title;

    public string $message;

    public bool $showButton;

    public ?string $buttonText;

    public ?string $buttonUrl;

    public string $id;

    public function __construct($type = 'success', $message = '', $title = null, $showButton = false, $buttonText = 'View more', $buttonUrl = null)
    {
        $this->type = $type;
        $this->title = $title ?? ucfirst($type);
        $this->message = $message;
        $this->showButton = filter_var($showButton, FILTER_VALIDATE_BOOLEAN);
        $this->buttonText = $buttonText;
        $this->buttonUrl = $buttonUrl;
        $this->id = 'toast-'.Str::random(8);
    }

    public function render()
    {
        return view('components.toastr');
    }
}

<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Button extends Component
{
    public $type;
    public $color;
    public $action;
    public $loadingText;
    public $size;
    public $icon;
    public function __construct(
        $type = 'button',
        $color = 'blue',
        $action = null,
        $loadingText = 'Processing...',
        $size = 'md',
        $icon = null
    ) {
        $this->type = $type;
        $this->color = $color;
        $this->action = $action;
        $this->loadingText = $loadingText;
        $this->size = $size;
        $this->icon = $icon;
    }
    public function colorClasses()
    {
        return [
            'blue'  => 'bg-blue-600 hover:bg-blue-700 text-white',
            'red'   => 'bg-red-600 hover:bg-red-700 text-white',
            'green' => 'bg-green-600 hover:bg-green-700 text-white',
            'gray'  => 'bg-gray-300 hover:bg-gray-400 text-gray-800',
        ][$this->color] ?? 'bg-blue-600 hover:bg-blue-700 text-white';
    }

    public function sizeClasses()
    {
        return [
            'sm' => 'px-3 py-1 text-sm',
            'md' => 'px-4 py-2',
            'lg' => 'px-5 py-2.5 text-lg',
        ][$this->size] ?? 'px-4 py-2';
    }
    public function render()
    {
        return view('components.button');
    }
}

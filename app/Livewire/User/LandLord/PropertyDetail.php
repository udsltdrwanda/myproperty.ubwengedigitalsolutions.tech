<?php

namespace App\Livewire\User\LandLord;

use Livewire\Component;
use App\Models\Property;

class PropertyDetail extends Component
{
    public $property;

    public function mount(Property $property)
    {
        $this->property = $property;
    }
    public function render()
    {
        return view('livewire.user.land-lord.property-detail');
    }
}

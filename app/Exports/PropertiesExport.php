<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PropertiesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $properties;

    public function __construct($properties)
    {
        $this->properties = $properties;
    }

    public function collection()
    {
        return $this->properties;
    }

    public function headings(): array
    {
        return [
            'UPI',
            'Name',
            'District',
            'Sector',
            'Cell',
            'Village',
            'Property Use',
            'Area (m²)',
            'Owned Year'
        ];
    }

    public function map($property): array
    {
        return [
            $property->upi,
            $property->name,
            optional($property->districtRelation)->name ?? '',
            optional($property->sectorRelation)->name ?? '',
            optional($property->cellRelation)->name ?? '',
            optional($property->villageRelation)->name ?? '',
            $property->property_use,
            $property->area,
            $property->owned_year,
        ];
    }
}

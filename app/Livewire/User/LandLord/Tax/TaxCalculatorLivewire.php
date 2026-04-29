<?php

namespace App\Livewire\User\LandLord\Tax;

use Livewire\Component;

class TaxCalculatorLivewire extends Component
{
    public $annualIncome = 0;
    public $bankInterest = 0;
    public $taxableIncome = 0;
    public $taxBreakdown = [];
    public $totalTax = 0;

    public function calculateTax()
    {
        // dd("ckeck");
        // Convert empty values to 0
        $this->annualIncome = empty($this->annualIncome) ? 0 : floatval($this->annualIncome);
        $this->bankInterest = empty($this->bankInterest) ? 0 : floatval($this->bankInterest);

        // Calculate taxable income (50% of rental income minus bank interest)
        $this->taxableIncome = ($this->annualIncome / 2) - $this->bankInterest;

        // Initialize tax breakdown
        $this->taxBreakdown = [
            'first_bracket' => [
                'amount' => 0,
                'tax' => 0,
            ],
            'middle_bracket' => [
                'amount' => 0,
                'tax' => 0,
            ],
            'upper_bracket' => [
                'amount' => 0,
                'tax' => 0,
            ],
        ];

        // Calculate tax for each bracket
        if ($this->taxableIncome <= 180000) {
            $this->taxBreakdown['first_bracket']['amount'] = $this->taxableIncome;
            $this->taxBreakdown['first_bracket']['tax'] = 0;
        } else {
            $this->taxBreakdown['first_bracket']['amount'] = 180000;
            $this->taxBreakdown['first_bracket']['tax'] = 0;

            if ($this->taxableIncome <= 1000000) {
                $middleBracketAmount = $this->taxableIncome - 180000;
                $this->taxBreakdown['middle_bracket']['amount'] = $middleBracketAmount;
                $this->taxBreakdown['middle_bracket']['tax'] = $middleBracketAmount * 0.2;
            } else {
                $this->taxBreakdown['middle_bracket']['amount'] = 820000;
                $this->taxBreakdown['middle_bracket']['tax'] = 820000 * 0.2;

                $upperBracketAmount = $this->taxableIncome - 1000000;
                $this->taxBreakdown['upper_bracket']['amount'] = $upperBracketAmount;
                $this->taxBreakdown['upper_bracket']['tax'] = $upperBracketAmount * 0.3;
            }
        }

        // Calculate total tax
        $this->totalTax = $this->taxBreakdown['first_bracket']['tax'] +
                         $this->taxBreakdown['middle_bracket']['tax'] +
                         $this->taxBreakdown['upper_bracket']['tax'];
    }

    public function render()
    {
        return view('livewire.user.land-lord.tax.tax-calculator-livewire')->layout('layouts.app');
    }
}

<?php

namespace App\Services\Tax;

class TaxService
{
    private array $calculators = [];

    public function addCalculator(TaxCalculatorInterface $calculator): void
    {
        $this->calculators[] = $calculator;
    }

    public function calculateTotal(float $amount): float
    {
        $total = 0;

        foreach ($this->calculators as $calculator) {
            $total += $calculator->calculate($amount);
        }

        return round($total, 2);
    }
}

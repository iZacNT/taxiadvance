<?php


namespace common\service\driver;


class PrepareDriverService
{

    /**
     * @param int $deposits Deposit
     * @param int $debt Debt
     * @param int $min Minimum deposit
     * @param int $max Maximum deposit
     * @param int $less Summ if $deposit < $min
     * @param int $more Summ if $min >=$deposit < $max
     * @return int Summ Depo = $less or $more
     */
    public function getDepoSumm(int $deposits, int $debt, int $min, int $max, int $less, int $more):int
    {
        $depo = 0;
        $calculate = $deposits-$debt;
        if ($calculate>=$min and $calculate<$max) { $depo = $more;}
        if ($calculate > 5000) { $depo = 0;}
        if ($calculate < $min ){ $depo = $less;}

        return $depo;
    }
}
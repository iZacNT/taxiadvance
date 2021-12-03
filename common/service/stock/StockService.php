<?php

namespace common\service\stock;

use backend\models\Stock;

class StockService
{
    /**
     * @param int $part_name
     * @param int $count
     * @param int $type 1 - Приход, 2 - Расход
     * @param int|null $repair_id
     * @param string|null $invoice
     * @param string|null $comment
     * @return bool
     */
    public function create(int $part_name,int $count,int $type, int $repair_id = null,string $invoice = null, string $comment = null): Stock
    {
        $stock = new Stock();
        $stock->stringNamePart = "Не важно";
        $stock->part_name = $part_name;
        $stock->count = $count;
        $stock->repair_id = $repair_id;
        $stock->invoice = $invoice;
        $stock->type = $type;
        $stock->comment = $comment;

        $stock->save();
        return $stock;
    }
}
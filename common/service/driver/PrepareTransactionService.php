<?php


namespace common\service\driver;


use backend\models\TransactionTypes;
use yii\helpers\ArrayHelper;

class PrepareTransactionService
{

    private $transactions;

    public function __construct(array $transactions)
    {
        $this->transactions = $transactions;
    }

    public function getBonusDriver(): int
    {
        $bonus = 0;
        foreach ($this->transactions as $transaction){
            if ($transaction['category_id'] == 'bonus'){
                $bonus += $transaction['amount'];
            }
        }
        \Yii::debug("Бонус: ".$bonus, __METHOD__);
        return $bonus;
    }

    public function getAmountTransactionType(): array
    {
        $types = $this->getNeedType();
        $arrSumType = [];
        foreach ($types as $type){
            $arrSumType[] = ['name' => $type->type, 'amount' => $this->getSummationByType($type->type)];
        }

        return $arrSumType;
    }

    public function getNeedType(): array
    {
        return TransactionTypes::find()->where(['summarize' => TransactionTypes::SUMMARIZE])->all();
    }

    public function getSummationByType($type): int
    {
        $amount = 0;
        foreach ($this->transactions as $transaction){
            if ($transaction['category_id'] == $type){
                $amount += $transaction['amount'];
            }
        }

        return $amount;
    }

    /**
     * Сумма всех типов транзакций
     * @return int
     */
    public function getSumOfTransactionByType(): int
    {
        $amount = 0;
        foreach($this->getAmountTransactionType() as $arr){
            $amount += $arr['amount'];
        }
        return $amount;
    }

    /**
     * Получаем все категории транзакций
     * @return array
     */
    public function getAllCategoryTransaction():array
    {
        return TransactionTypes::find()
            ->select(['group_type', 'name_group'])
            ->distinct()
            ->asArray()
            ->all();
    }

    /**
     * Подготовка массива с суммами типов транзаций
     * @param $category
     * @return array
     */
    public function getAllTypesByCategory($category): array
    {
        $withAmount = [];

        $byCategory = TransactionTypes::find()
            ->where(['group_type' => $category])
            ->asArray()
            ->all();

        foreach($byCategory as $item)
        {
            $sum = $this->getSummationByType($item['type']);
            $item['amount'] = $sum;
            $withAmount[] = $item;
        }

        return  $withAmount;
    }

    /**
     * Подсчет сумм всех видов транзакций
     * @return array
     */
    public function amountAllCategoriesInTransaction(): array
    {
        $categories = $this->getAllCategoryTransaction();
        $typeByCategories = [];
        foreach($categories as $category){
            $typeByCategories[] =
                [
                    'group' => $category['group_type'],
                    'name_group' => $category['name_group'],
                    'withAmount' => $this->getAllTypesByCategory($category['group_type'])];
        }

        return $typeByCategories;
    }

    public function prepareHtmlTransactionByType()
    {
        $html = '<div class="col-md-6">';
        $allCategoriesWithAmount = $this->amountAllCategoriesInTransaction();

        foreach ($allCategoriesWithAmount as $item)
        {
            $html .= '<div class="col-md-12"><div class="card card-widget widget-user-2">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header bg-warning">
                <h4 class="widget-user-desc">'.$item['name_group'].'</h4>
              </div>
              <div class="card-footer p-0">
                <ul class="nav flex-column">
                  '.
                $this->prepareRowTransactionType($item['withAmount'])
                .'
                </ul>
              </div>
            </div></div>';
        }
        $html .= "</div>";

        return $html;
    }

    public function prepareRowTransactionType($withAmount)
    {
        $html = '';
        $style = '';
        $icon = '';
        foreach($withAmount as $item)
        {
            if ($item['summarize'] == 1) {
                $style = 'style = "color: red;"';
                $icon = '<i class="fas fa-vote-yea"></i>';
            }
            $html .= '<li class="nav-item">
                    <a href="#" class="nav-link" '.$style.'>
                      '.$icon.' '.$item['name'].' <span class="float-right badge bg-primary">'.\Yii::$app->formatter->asCurrency($item['amount']).'</span>
                    </a>
                  </li>';
        }
        return $html;
    }

    public function prepareTableTransaction()
    {

        $html = '<div class="col-md-6"><h3>Все транзакции за период:</h3><table class="table table-bordered" style="font-size: small">
                  <thead>
                    <tr>
                      <th>Адрес</th>
                      <th>Название</th>
                      <th style="width: 100px">Сумма</th>
                    </tr>
                  </thead>
                  <tbody>';
        if ($this->transactions){

            $styleRow = '';
            foreach($this->transactions as $transaction){
                $html .='<tr>
                          <td>'.$transaction['description'].'</td>
                          <td>
                          '.$transaction['category_id'].'
                          <br>
                            '.$transaction['category_name'].'
                          </td>
                          <td>'.\Yii::$app->formatter->asCurrency($transaction['amount']).'</td>
                        </tr>';
            }
        }else{
            $html .= '<tr><td colspan="3">Ни чего не найдено.</td></tr>';
        }
        $html .= '</tbody>
                </table></div>';

        return $html;
    }
}
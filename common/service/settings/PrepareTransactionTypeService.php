<?php

namespace common\service\settings;

use backend\models\TransactionTypes;
use yii\helpers\Html;

class PrepareTransactionTypeService
{

    private $categoryTransactions;
    public $listGroupTransaction;

    public function __construct(array $req)
    {
        $this->categoryTransactions = $req;
        $this->listGroupTransaction = $this->getUniqGroupsTransactionTypes();
    }

    public function verifyWithDBase()
    {
        $transactionListInBase = TransactionTypes::find()->asArray()->all();
        foreach($this->categoryTransactions as $category){
            $trList = array_column($transactionListInBase, 'type', 'type');
            if (!array_key_exists($category['id'],$trList)){
                $this->insert($category);
            }
        }
    }

    public function insert(array $category)
    {
        $transactionType = new TransactionTypes();
        $transactionType->type = $category['id'];
        $transactionType->name = $category['name'];
        $transactionType->group_type = $category['group_id'];
        $transactionType->name_group = $category['group_name'];
        $transactionType->is_enabled = (int) $category['is_enabled'];
        $transactionType->is_editable = (int) $category['is_editable'];
        $transactionType->is_creatable = (int) $category['is_creatable'];
        $transactionType->is_affecting_driver_balance = (int) $category['is_affecting_driver_balance'];

        $transactionType->save();
        \Yii::debug(serialize($transactionType->errors), __METHOD__);

    }

    public function getUniqGroupsTransactionTypes():array
    {
        return TransactionTypes::find()
            ->select('group_type, name_group')
            ->distinct()
            ->orderBy(['name_group' => SORT_DESC])
            ->asArray()
            ->all();
    }

    public function preparePreviewTransactionType(): string
    {
        $listGroups = '';
        foreach($this->listGroupTransaction as $groupTransaction){
            $listGroups .= '<div class="col-md-4 mb-2">
                <div class="card h-100">
                  <div class="card-header">
                    <h3 class="card-title">
                      '.$groupTransaction['name_group'].'
                    </h3>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                      '.$this->getItemsFromGroupTransactionType($groupTransaction['group_type']).'  
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
              </div>';
        }

        return $listGroups;
    }

    private function getItemsFromGroupTransactionType(string $groupTransaction): string
    {
        $rButtons = '';
        $tTInfo = TransactionTypes::find()
            ->where(['group_type' => $groupTransaction])
            ->all();
            foreach($tTInfo as $transactionType) {

                $checked = $transactionType->summarize ? "checked" : "";
                $rButtons .= '
                        <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" name="transactionType[]" id="'.$transactionType["type"].'" '.$checked.' value="'.$transactionType["type"].'">
                          <label for="'.$transactionType["type"].'" class="custom-control-label">'.$transactionType["name"].'</label>
                        </div>
            ';
            }
        return $rButtons;
    }
}
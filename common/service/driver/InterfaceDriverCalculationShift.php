<?php

namespace common\service\driver;

interface InterfaceDriverCalculationShift
{
    public function __construct(CalculationShiftParams $params);

    public function getCalculationShift();
}
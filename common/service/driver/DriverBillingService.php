<?php

namespace common\service\driver;

class DriverBillingService
{

    private $plan;
    private $inputAmount;
    private $bonusYandex;
    private $carMark;
    private $fuel;
    private $period;
    private $percentPark;
    private $percentDriver;
    private $balanceYandex;
    private $depo;
    private $debtFromShift;
    private $carWash;
    private $carFuelSumm;
    private $carPhoneSumm;
    private $driverId;
    private $tupeDay;
    private $hours;
    private $billing;
    private $summPark;
    private $summDriver;

    public function __construct($requestPost)
    {
        $this->driverId = $requestPost['driverId'];
        $this->balanceYandex = $requestPost['balanceYandex'];
        $this->bonusYandex = $requestPost['bonusYandex'];
        $this->carMark = $requestPost['carMark'];
        $this->fuel = $requestPost['fuel'];
        $this->period = $requestPost['period'];
        $this->tupeDay = $requestPost['typeDay'];
        $this->inputAmount = $requestPost['inputAmount'];
        $this->depo = $requestPost['depo'];
        $this->debtFromShift = $requestPost['debtFromShift'];
        $this->carWash = $requestPost['carWash'];
        $this->carFuelSumm = $requestPost['carFuelSumm'];
        $this->carPhoneSumm = $requestPost['carPhoneSumm'];
        $this->hours = $requestPost['hours'];
        $this->billing = $requestPost['billing'];
        $this->percentPark = $requestPost['percentPark'];
        $this->percentDriver = $requestPost['percentDriver'];
        $this->summPark = $requestPost['summPark'];
        $this->summDriver = $requestPost['summDriver'];
        $this->plan = $requestPost['plan'];
    }

    public function create()
    {

    }
}
<?php

namespace App\Library\Core\Entities;


class Operation
{
    private $id;
    private $transactionDate;
    private $userId;
    private $clientType;
    private $transactionType;
    private $amount;
    private $currencyCode;
    private $eurAmount;

    /**
     * Get the value of Id 
     * 
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
 
    /** 
     * Set the value of Id 
     * 
     * @param mixed $id
     * 
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;
 
        return $this;
    }
 
    /**
     * Get the value of Transaction Date 
     * 
     * @return mixed
     */
    public function getTransactionDate()
    {
        return $this->transactionDate;
    }
 
    /** 
     * Set the value of Transaction Date 
     * 
     * @param mixed $transactionDate
     * 
     * @return self
     */
    public function setTransactionDate($transactionDate)
    {
        $this->transactionDate = $transactionDate;
 
        return $this;
    }
 
    /**
     * Get the value of User Id 
     * 
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }
 
    /** 
     * Set the value of User Id 
     * 
     * @param mixed $userId
     * 
     * @return self
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
 
        return $this;
    }
 
    /**
     * Get the value of Client Type 
     * 
     * @return mixed
     */
    public function getClientType()
    {
        return $this->clientType;
    }
 
    /** 
     * Set the value of Client Type 
     * 
     * @param mixed $clientType
     * 
     * @return self
     */
    public function setClientType($clientType)
    {
        $this->clientType = $clientType;
 
        return $this;
    }
 
    /**
     * Get the value of Transaction Type 
     * 
     * @return mixed
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }
 
    /** 
     * Set the value of Transaction Type 
     * 
     * @param mixed $transactionType
     * 
     * @return self
     */
    public function setTransactionType($transactionType)
    {
        $this->transactionType = $transactionType;
 
        return $this;
    }
 
    /**
     * Get the value of Amount 
     * 
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }
 
    /** 
     * Set the value of Amount 
     * 
     * @param mixed $amount
     * 
     * @return self
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
 
        return $this;
    }
 
    /**
     * Get the value of Currency Code 
     * 
     * @return mixed
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }
 
    /** 
     * Set the value of Currency Code 
     * 
     * @param mixed $currencyCode
     * 
     * @return self
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;
 
        return $this;
    }
 
    /**
     * Get the value of Eur Amount 
     * 
     * @return mixed
     */
    public function getEurAmount()
    {
        return $this->eurAmount;
    }
 
    /** 
     * Set the value of Eur Amount 
     * 
     * @param mixed $eurAmount
     * 
     * @return self
     */
    public function setEurAmount($eurAmount)
    {
        $this->eurAmount = $eurAmount;
 
        return $this;
    }
 
}

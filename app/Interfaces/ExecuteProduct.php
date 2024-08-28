<?php

namespace App\Interfaces;

interface ExecuteProduct
{
    public function getUrl();
    public function getMethod();
    public function getPayload($service_id);
    public function setUrl($endpoint);
    public function isExecutable();
    public function getProduct();
    public function getValue();
    public function getUser();
}

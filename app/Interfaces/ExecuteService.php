<?php

namespace App\Interfaces;

interface ExecuteService
{
    public function getUrl();
    public function getType();
    public function getId();
    public function getMethod();
    public function getUser();
    public function getPayload($service_id);
    public function setUrl($endpoint);
}

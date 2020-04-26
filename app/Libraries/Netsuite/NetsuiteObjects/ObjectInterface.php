<?php

namespace App\Libraries\Netsuite\NetSuiteObjects;

use Symfony\Component\HttpFoundation\ParameterBag;

interface ObjectInterface
{
    public function setNetsuiteConfig($config = []);
    public function get($query = []);
    public function update(ParameterBag $parameter);
    public function updateCustomFields(ParameterBag $parameter);
    public function find($id);
    public function findByTransaction($id);
    public function findByRange($start, $end, $query = []);
}

<?php
require_once 'config.php';

class PaymentMethodController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    
}
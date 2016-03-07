<?php

namespace NameFinder\Repository;

use Exception;
use PDO;

abstract class PdoBaseRepository
{
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
}

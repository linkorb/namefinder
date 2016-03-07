<?php

namespace NameFinder\Repository;

use Exception;
use PDO;

class PdoNameCheckRepository extends PdoBaseRepository
{
    public function getCheckers()
    {
        $statement = $this->pdo->prepare(sprintf(
            'SELECT checker_key FROM name_check GROUP BY checker_key ORDER BY checker_key'
        ));
        $statement->execute();

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        $res = [];
        foreach ($rows as $row) {
            $res[] = $row['checker_key'];
        }
        return $res;
    }
    
    public function exists($name, $checkerKey)
    {
        $statement = $this->pdo->prepare(sprintf(
            'SELECT * FROM name_check WHERE name=:name AND checker_key=:checker_key'
        ));
        $statement->execute(
            [
                'name' => $name,
                'checker_key' => $checkerKey
            ]
        );

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows)>0) {
            return true;
        }
        return false;
    }
    
    public function register($name, $checkerKey, $status)
    {
        $statement = $this->pdo->prepare(sprintf(
            'INSERT INTO name_check (name, checker_key, status, created_at)
            VALUES(:name, :checker_key, :status, NOW())'
        ));
        $statement->execute(
            [
                'name' => $name,
                'checker_key' => $checkerKey,
                'status' => $status
            ]
        );

    }
}

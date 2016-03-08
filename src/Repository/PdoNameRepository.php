<?php

namespace NameFinder\Repository;

use Exception;
use PDO;

class PdoNameRepository extends PdoBaseRepository
{
    public function getStatusByName($name)
    {
        $sql = sprintf(
            "SELECT checker_key, status FROM name_check WHERE name = :name;"
        );

        $statement = $this->pdo->prepare($sql);
        $statement->execute(['name' => $name]);

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        $status = [];
        foreach ($rows as $row) {
            $status[$row['checker_key']] = $row['status'];
        }
        return $status;
    }
    
    public function getByRating($rating)
    {
        $sql = sprintf(
            "SELECT name FROM name WHERE rating = :rating;"
        );

        $statement = $this->pdo->prepare($sql);
        $statement->execute(['rating' => $rating]);

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        $names = [];
        foreach ($rows as $row) {
            $names[] = $row['name'];
        }
        return $names;
    }
    
    public function setRatingByName($name, $rating)
    {
        $sql = sprintf(
            "UPDATE name SET rating=:rating WHERE name = :name;"
        );

        $statement = $this->pdo->prepare($sql);
        $statement->execute(['rating'=>$rating, 'name' => $name]);
        return true;
    }
    
    public function getRatingByName($name)
    {
        $sql = sprintf(
            "SELECT rating FROM name WHERE name = :name;"
        );

        $statement = $this->pdo->prepare($sql);
        $statement->execute(['name' => $name]);
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $rows[0]['rating'];
    }
    
    public function search($generators, $checkers, $limit = 4)
    {
        $matches = [];
        $sql = sprintf(
            "SELECT name FROM name WHERE generator IN ('" . implode("','", $generators) . "')
            ORDER BY RAND() LIMIT 1000;"
        );
        $statement = $this->pdo->prepare($sql);
        $statement->execute();

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        $names = [];
        foreach ($rows as $row) {
            $names[] = $row['name'];
        }
        
        
        foreach ($names as $name) {
            $status = $this->getStatusByName($name);
            $match = true;
            foreach ($checkers as $checker) {
                if (!isset($status[$checker])) {
                    $match = false;
                } else {
                    if ($status[$checker] != 'FREE') {
                        $match = false;
                    }
                }
            }
            if ($match) {
                if (count($matches)<$limit) {
                    $matches[] = $name;
                }
            }
        }
        return $matches;
    }
    
    public function getGenerators()
    {
        $statement = $this->pdo->prepare(sprintf(
            'SELECT generator FROM name GROUP BY generator ORDER BY generator'
        ));
        $statement->execute();

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        $res = [];
        foreach ($rows as $row) {
            $res[] = $row['generator'];
        }
        return $res;
    }
    
    public function exists($name)
    {
        $statement = $this->pdo->prepare(sprintf(
            'SELECT * FROM name WHERE name=:name'
        ));
        $statement->execute(['name' => $name]);

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows)>0) {
            return true;
        }
        return false;
    }
    
    public function register($name, $generator)
    {
        $statement = $this->pdo->prepare(sprintf(
            'INSERT INTO name (name, generator, created_at)
                VALUES(:name, :generator, NOW())'
        ));
        $statement->execute(
            [
                'name' => $name,
                'generator' => $generator
            ]
        );

    }
}

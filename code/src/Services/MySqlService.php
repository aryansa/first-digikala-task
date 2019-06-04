<?php

namespace App\Services;

use App\Services;
use PDO;
use PDOException;

class MySqlService
{
    private $pdo;

    /**
     * MySqlService constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $configs = Services::configService()->getConfig();

        $host = $configs['database']['host'];
        $db = $configs['database']['database_name'];
        $user = $configs['database']['username'];
        $pass = $configs['database']['pass'];
        $port = $configs['database']['port'];

        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function query(string $query, array $var)
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($var);

        return $stmt->fetchAll();

    }

    private static function getCacheKey(string $table, string $primaryKey)
    {
        return "row:$table:$primaryKey";
    }

    public function selectAll(string $table)
    {
        $ttl = 3600;

        $cacheKey = self::getCacheKey($table, 0);
        $cacheService = Services::cacheService();
        $value = $cacheService->get($cacheKey);

        if (!empty($value)) {
            return json_decode($value, 1);
        }

        $selectQuery = $this->query("SELECT id FROM $table ORDER BY id DESC", []);

        /* This is not a good idea to cache everything like this
         * We should use namespaces and pagers
         */
        $cacheService->set($cacheKey, json_encode($selectQuery), $ttl);

        return $selectQuery;

    }

    public function select(string $table, array $primaryKeys): array
    {
        $ttl = 3600;
        $cacheKeys = [];
        foreach ($primaryKeys as $primaryKey) {
            $cacheKeys[$primaryKey] = self::getCacheKey($table, $primaryKey);
        }

        /** @var MemcachedService $cacheService */
        $cacheService = Services::cacheService();
        $results = [];
        $getFromDb = [];
        foreach ($cacheKeys as $primaryKey => $cacheKey) {
            $value = $cacheService->get($cacheKey);
            if (!empty($value)) {
                $results[$primaryKey] = json_decode($value, 1);
            } else {
                $getFromDb[$primaryKey] = $primaryKey;
            }
        }
        if (!empty($getFromDb)) {
            $values = [];
            foreach ($getFromDb as $item) {
                $values[] = $item;
            }

            $selectResult = $this->query(
                sprintf(
                    "SELECT * FROM $table WHERE id IN (%s)",
                    implode(",", array_fill(0, count($getFromDb), "?"))
                ),
                $values
            );
            foreach ($selectResult as $value) {
                $results[$value['id']] = $value;
                $cacheService->set(self::getCacheKey($table, $value['id']), json_encode($value), $ttl);
            }
        }

        return $results;
    }

    public function insert(string $table, array $columns, array $values): array
    {
        $valuesSqlString = [];
        $sqlValues = [];
        foreach ($values as $value) {
            $valuesSqlString[] = "(" . implode(',', array_fill(0, count($value), "?")) . ")";
            $sqlValues = array_merge($sqlValues, $value);
        }

        $query = sprintf(
            "INSERT INTO $table (%s) VALUES %s",
            implode(",", $columns),
            implode(",", $valuesSqlString)
        );

        $stmt = $this->pdo->prepare($query);

        $stmt->execute($sqlValues);

        $primaryKeys = range(
            $this->pdo->lastInsertId(),
            (int)$this->pdo->lastInsertId() + count($values) - 1
        );

        $cacheService = Services::cacheService();
        foreach ($primaryKeys as $primaryKey) {
            $cacheService->delete(self::getCacheKey($table, $primaryKey));
        }
        $cacheService->delete(self::getCacheKey($table,0));
        return $primaryKeys;
    }

    public function update(string $table, array $columns, array $values): void
    {

        $columnSql = [];
        foreach ($columns as $column) {
            $columnSql[] = $column . " = ? ";
        }
        $columnSql = implode(" , ", $columnSql);

        $this->query("UPDATE $table SET $columnSql WHERE id = ?", $values);


        $cacheService = Services::cacheService();

        $cacheService->delete(self::getCacheKey($table, $values[count($values) - 1]));
        $cacheService->delete(self::getCacheKey($table, 0));
    }

    public function delete(string $table, array $primaryKeys): void
    {
        $keysSql = implode(",", array_fill(0, count($primaryKeys), "?"));

        $this->query("DELETE from $table WHERE id IN ($keysSql);", $primaryKeys);

        $cacheService = Services::cacheService();
        foreach ($primaryKeys as $primaryKey) {
            $cacheService->delete(self::getCacheKey($table, $primaryKey));
        }

        $cacheService->delete(self::getCacheKey($table, 0));
    }
}

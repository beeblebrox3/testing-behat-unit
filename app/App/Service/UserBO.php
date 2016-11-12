<?php

namespace App\Service;

use Illuminate\Database\Connection;

class UserBO
{
    protected $connection;

    const TABLE_NAME = 'user';

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    private function table()
    {
        return $this->connection->table(static::TABLE_NAME);
    }

    public function save(array $data)
    {
        $this->connection->beginTransaction();
        try {
            $record = $this->table()->insert($data);
            $this->connection->commit();
            return $record;
        } catch (\Exception $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }

    public function get(int $id)
    {
        return $this->table()->find($id);
    }

    public function delete(int $id)
    {
        return $this->table()->delete($id);
    }

    public function search(array $example)
    {
        $table = $this->table();
        foreach ($example as $field => $value) {
            $table->where($field, $value);
        }

        return $table->get();
    }

    public function enable(int $id)
    {
        $this->table()->where('id', $id)->update(['active' => 1]);
    }

    public function disable(int $id)
    {
        $this->table()->where('id', $id)->update(['active' => 0]);
    }
}
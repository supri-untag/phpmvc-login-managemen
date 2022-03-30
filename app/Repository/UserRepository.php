<?php

namespace Supri\LoginManagementVersigue\app\Repository;

use Supri\LoginManagementVersigue\app\Domain\User;

class UserRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }
    public function save(User $user) :User
    {
        $stmt = $this->connection->prepare("INSERT INTO users(id, name, password) VALUES(?,?,?)");
        $stmt->execute([$user->id,$user->name,$user->password]);
        return $user;
    }
    public function findById(string $id): ?User
    {
        $statement = $this->connection->prepare("SELECT id, name, password FROM users WHERE id = ?");
        $statement->execute([$id]);

        try {
            if ($row = $statement->fetch()) {
                $user = new User();
                $user->id = $row['id'];
                $user->name = $row['name'];
                $user->password = $row['password'];
                return $user;
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }
    public function update(User $user) :User
    {
        $stmt = $this->connection->prepare("UPDATE users SET name = ?, password = ? WHERE id = ?");
        $stmt->execute([
            $user->name, $user->password , $user->id
        ]);

        return $user;
    }
}

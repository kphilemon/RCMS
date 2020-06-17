<?php


class UserModel
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    // get all user fields by hash (for email verification)
    public function getByHash(string $hash): array
    {
        // select * from user where ...
        try {
            $stmt = $this->connection->prepare('');
            $stmt->execute();
            $data = $stmt->fetch();

            if (!$data) {
                return array();
            }
            return $data;

        } catch (PDOException $exception) {
            error_log('UserModel: getByHash: ' . $exception->getMessage() . 'hash: ' . $hash);
            throw $exception;
        }
    }

    // get all user fields by email (for log in)
    public function getByEmail(string $email): array
    {
        // select * from user where ...
        try {
            $stmt = $this->connection->prepare('');
            $stmt->execute();
            $data = $stmt->fetch();

            if (!$data) {
                return array();
            }
            return $data;

        } catch (PDOException $exception) {
            error_log('UserModel: getByEmail: ' . $exception->getMessage() . 'email: ' . $email);
            throw $exception;
        }
    }

    public function registerEmailWithHash(string $email, string $hash): bool
    {
        // use insert into on duplicate here because user might lose the verification link
        // normal insert will not allow if key exist. So, update if key exits to "refresh" the hash
        try {
            // https://dev.mysql.com/doc/refman/8.0/en/insert-on-duplicate.html
            $stmt = $this->connection->prepare('');
            return $stmt->execute();

        } catch (PDOException $exception) {
            error_log('UserModel: registerEmailWithHash: ' . $exception->getMessage() . 'email: ' . $email . 'hash:' . $hash);
            throw $exception;
        }
    }

    public function updatePasswordById(string $id, string $password): bool
    {

    }

    public function updateDetailsById(string $id): bool
    {

    }

}


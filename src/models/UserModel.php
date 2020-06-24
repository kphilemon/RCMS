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
        // select * from student where hash = :hash
        try {
            $stmt = $this->connection->prepare('SELECT * FROM student WHERE hash =?');
            $stmt->execute(["$hash"]);
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
            $stmt = $this->connection->prepare('SELECT * FROM student WHERE email=?');
            $stmt->execute([$email]);
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

    //get * from UMDB for register
    public function getByEmailReg(string $email): array
    {
        // select * from user where ...
        try {
            $stmt = $this->connection->prepare('SELECT * FROM um_database WHERE email=?');
            $stmt->execute([$email]);
            $data = $stmt->fetch();

            if (!$data) {
                return array();
            }
            return $data;

        } catch (PDOException $exception) {
            error_log('UserModel: getByEmailReg: ' . $exception->getMessage() . 'email: ' . $email);
            throw $exception;
        }
    }

    //update student table with data from UMDB
    public function insertByEmailReg(string $email, string $name, int $matrix_no, int $college_id, string $faculty, string $course, string $hash): bool
    {
        // select * from user where ...
        try {
            $stmt = $this->connection->prepare('
            INSERT INTO student (email, name, matrix_no, college_id, faculty, course, hash)
            VALUES (:email, :name, :matrix_no, :college_id, :faculty, :course, :hash)');
            return $stmt->execute([
                ":email" => $email,
                ":name" => $name,
                ":matrix_no" => $matrix_no,
                ":college_id" => $college_id,
                ":faculty" => $faculty,
                ":course" => $course,
                ":hash" => $hash
            ]);

        } catch (PDOException $exception) {
            error_log('UserModel: insertByEmailReg: ' . $exception->getMessage() . 'email: ' . $email);
            throw $exception;
        }
    }


    public function updateHash(string $email, string $hash): bool
    {
        // use insert into on duplicate here because user might lose the verification link
        // normal insert will not allow if key exist. So, update if key exits to "refresh" the hash
        try {

            $stmt = $this->connection->prepare('
            UPDATE student
            SET hash=:hash, activated=:activated
            WHERE email=:email');
            return $stmt->execute([
                ":email" => $email,
                ":hash" => $hash,
                ":activated" => 0
            ]);

        } catch (PDOException $exception) {
            error_log('UserModel: updatePassword: ' . $exception->getMessage() . 'email: ' . $email);
            throw $exception;
        }
    }

    public function updatePassword(string $email, string $password): bool
    {
        // use insert into on duplicate here because user might lose the verification link
        // normal insert will not allow if key exist. So, update if key exits to "refresh" the hash
        try {

            $stmt = $this->connection->prepare('
            UPDATE student 
            SET password=:password, activated=:activated
            WHERE email=:email');
            return $stmt->execute([
                ":email" => $email,
                ":activated" => 1,
                ":password" => $password,
            ]);

        } catch (PDOException $exception) {
            error_log('UserModel: updatePassword: ' . $exception->getMessage() . 'email: ' . $email);
            throw $exception;
        }
    }
}

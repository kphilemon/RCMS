<?php


class UMStudentModel
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function deleteAccountById(int $id, string $pass): bool
    {
        try {
            $stmt = $this->connection->prepare('DELETE FROM student WHERE id=? and password=?');
            return $stmt->execute([$id, $pass]);

        } catch (PDOException $exception) {
            error_log('UMStudentModel: deleteAccountById: ' . $exception->getMessage() . 'id: ' . $id);
            throw $exception;
        }
    }

    public function getDetailsById(int $id): array
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM student WHERE id=?');
            $stmt->execute([$id]);
            $data = $stmt->fetch();

            if (!$data) {
                return array();
            }
            return $data;

        } catch (PDOException $exception) {
            error_log('UMStudentModel: getDetailsById: ' . $exception->getMessage() . 'id: ' . $id);
            throw $exception;
        }
    }

    public function updateDetailsById(int $id, string $room_no, string $nationality, string $telephone, string $gender, string $dob, string $address, string $city, string $state, string $zip): bool
    {
        try {
            $stmt = $this->connection->prepare('UPDATE student SET room_no=:room_no, nationality=:nationality, phone=:telephone, gender=:gender, date_of_birth=:dob, address=:address, city=:city, state=:state, zip=:zip WHERE id=:id');
            $data = $stmt->execute([
                ':id'=>$id,
                ':room_no'=>$room_no,
                ':nationality'=>$nationality,
                ':telephone'=>$telephone,
                ':gender'=>$gender,
                ':dob'=>date('Y-m-d', strtotime($dob)),
                ':address'=>$address,
                ':city'=>$city,
                ':state'=>$state,
                ':zip'=>$zip
            ]);
            if ($data) {
                return false;
            }
            return true;
        } catch (PDOException $exception) {
            error_log('UMStudentModel: updateDetailsById: ' . $exception->getMessage() . 'id: ' . $id);
            throw $exception;
        }
    }

    public function updatePasswordById(int $id, string $password): bool
    {
        try {
            $stmt = $this->connection->prepare('UPDATE student SET password=? WHERE id=?');
            return $stmt->execute([$password, $id]);

        } catch (PDOException $exception) {
            error_log('UMStudentModel: updatePasswordById: ' . $exception->getMessage() . 'id: ' . $id);
            throw $exception;
        }
    }

}
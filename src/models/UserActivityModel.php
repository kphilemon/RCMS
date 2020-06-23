<?php


class UserActivityModel
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function checkRegisterActivity(string $studentid, string $activityid): bool
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM user_activity WHERE user_id=? AND activity_id=?');
            $stmt->execute([$studentid, $activityid]);
            $data = $stmt->fetchAll();

            if (!$data) {
                return false;
            }
            return true;

        } catch (PDOException $exception) {
            error_log('UserActivityModel: checkRegisterActivity: ' . $exception->getMessage());
            throw $exception;
        }

    }

    public function registerActivity(int $studentid, int $activityid): bool
    {
        try {
            $sql = 'INSERT INTO user_activity (user_id, activity_id)
                    VALUES (:user_id,:activity_id)';
            $stmt = $this->connection->prepare($sql);
            return $stmt->execute([
                ':user_id' => $studentid,
                ':activity_id' => $activityid,
            ]);

        } catch (PDOException $exception) {
            error_log('UserActivityModel: register: ' . $exception->getMessage());
            throw $exception;
        }

    }

    public function unregisterActivity(string $studentid, string $activityid): bool
    {
        try {
            $stmt = $this->connection->prepare('DELETE FROM user_activity WHERE user_id =? AND activity_id = ?');
            return $stmt->execute([$studentid, $activityid]);


        } catch (PDOException $exception) {
            error_log('UserActivityModel: unregister: ' . $exception->getMessage() . 'id: ');
            throw $exception;
        }

    }

    public function getActivityExceptId(int $id): array
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM activity WHERE NOT id= ? ORDER BY `activity_date` ASC');
            $stmt->execute([$id]);
            $data = $stmt->fetchAll();

            if (!$data) {
                return array();
            }
            return $data;

        } catch (PDOException $exception) {
            error_log('ActivityModel: getAllActivities: ' . $exception->getMessage());
            throw $exception;
        }
    }


    public function getActivityById(string $id): array
    {

        try {
            $stmt = $this->connection->prepare('SELECT * FROM activity WHERE id = ?');
            $stmt->execute([$id]);
            $data = $stmt->fetch();

            if (!$data) {
                return array();
            }
            return $data;

        } catch (PDOException $exception) {
            error_log('UserActivityModel: getById: ' . $exception->getMessage() . 'id: ' . $id);
            throw $exception;
        }
    }

    public function getUserAllActivity(string $id): array
    {

        try {
            $stmt = $this->connection->prepare('SELECT * FROM user_activity WHERE id = ? INNER JOIN user_activty.activity_id 
            ON activty.id');
            $stmt->execute([$id]);
            $data = $stmt->fetchAll();

            if (!$data) {
                return array();
            }
            return $data;

        } catch (PDOException $exception) {
            error_log('UserActivityModel: getById: ' . $exception->getMessage() . 'id: ' . $id);
            throw $exception;
        }
    }
}
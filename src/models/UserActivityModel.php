<?php


class UserActivityModel
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function checkIfUserRegistered(int $userId, int $activityId): bool
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM user_activity WHERE user_id = ? AND activity_id = ?');
            $stmt->execute([$userId, $activityId]);
            $data = $stmt->fetch();

            if (!$data) {
                return false;
            }
            return true;

        } catch (PDOException $exception) {
            error_log('UserActivityModel: checkIfUserRegistered: ' . $exception->getMessage() . ' userId: ' . $userId . ' activityId: ' . $activityId);
            throw $exception;
        }
    }

    public function registerActivity(int $userId, int $activityId): bool
    {
        try {
            $stmt = $this->connection->prepare('INSERT INTO user_activity (user_id, activity_id) VALUES (?, ?)');
            return $stmt->execute([$userId, $activityId]);

        } catch (PDOException $exception) {
            error_log('UserActivityModel: registerActivity: ' . $exception->getMessage() . ' userId: ' . $userId . ' activityId: ' . $activityId);
            throw $exception;
        }
    }

    public function unregisterActivity(int $userId, int $activityId): bool
    {
        try {
            $stmt = $this->connection->prepare('DELETE FROM user_activity WHERE user_id = ? AND activity_id = ?');
            return $stmt->execute([$userId, $activityId]);

        } catch (PDOException $exception) {
            error_log('UserActivityModel: unregisterActivity: ' . $exception->getMessage() . ' userId: ' . $userId . ' activityId: ' . $activityId);
            throw $exception;
        }
    }

    public function getAllRegisteredActivities(int $userId): array
    {
        try {
            $stmt = $this->connection->prepare('SELECT activity_id, img, name, activity_date FROM user_activity INNER JOIN activity ON user_activity.activity_id = activity.id WHERE user_activity.user_id = ? ORDER BY activity_date');
            $stmt->execute([$userId]);
            $data = $stmt->fetchAll();

            if (!$data) {
                return array();
            }
            return $data;

        } catch (PDOException $exception) {
            error_log('UserActivityModel: getAllRegisteredActivities: ' . $exception->getMessage() . 'userId: ' . $userId);
            throw $exception;
        }
    }
}
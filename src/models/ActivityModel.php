<?php


class ActivityModel
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAll(): array
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM activity WHERE activity_date >= CURDATE() ORDER BY activity_date');
            $stmt->execute();
            $data = $stmt->fetchAll();

            if (!$data) {
                return array();
            }
            return $data;

        } catch (PDOException $exception) {
            error_log('ActivityModel: getAll: ' . $exception->getMessage());
            throw $exception;
        }
    }
    
    public function getAllExceptId(int $id): array
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM activity WHERE NOT id = ? AND activity_date >= CURDATE() ORDER BY activity_date');
            $stmt->execute([$id]);
            $data = $stmt->fetchAll();

            if (!$data) {
                return array();
            }
            return $data;

        } catch (PDOException $exception) {
            error_log('ActivityModel: getAllExceptId: ' . $exception->getMessage()  . ' id: ' . $id);
            throw $exception;
        }
    }


    public function getById(int $id): array
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
            error_log('ActivityModel: getById: ' . $exception->getMessage() . ' id: ' . $id);
            throw $exception;
        }
    }

    // DEPRECATED
    public function getThisWeekendActivities(): array
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM activity WHERE (WEEKDAY(activity_date) = 5 OR WEEKDAY(activity_date) = 6) AND WEEK(activity_date, 5) = WEEK(curdate()) AND activity_date >= CURDATE() ORDER BY activity_date');
            $stmt->execute();
            $data = $stmt->fetchAll();

            if (!$data) {
                return array();
            }
            return $data;

        } catch (PDOException $exception) {
            error_log('ActivityModel: getThisWeekActivities: ' . $exception->getMessage());
            throw $exception;
        }
    }

    // DEPRECATED
    public function getThisMonthActivities(): array
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM activity WHERE MONTH(activity_date) = MONTH(curdate()) AND (activity_date) >= CURDATE() ORDER BY activity_date');
            $stmt->execute();
            $data = $stmt->fetchAll();

            if (!$data) {
                return array();
            }
            return $data;

        } catch (PDOException $exception) {
            error_log('ActivityModel: getThisMonthActivities: ' . $exception->getMessage());
            throw $exception;
        }

    }
}
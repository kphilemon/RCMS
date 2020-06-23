<?php


class ActivityModel
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAllActivities(): array
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM activity WHERE activity_date>=CURDATE() ORDER BY `activity_date` ASC');
            $stmt->execute();
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

    public function getThisWeekendActivities(): array
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM `activity` WHERE (WEEKDAY(`activity_date`)=5 OR WEEKDAY(`activity_date`)=6) AND WEEK(`activity_date`,5)=WEEK(curdate()) AND activity_date>=CURDATE() ORDER BY `activity_date` ASC ');
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


    public function getThisMonthActivities(): array
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM `activity` WHERE MONTH(`activity_date`)=MONTH(curdate()) AND (`activity_date`)>=CURDATE()
            ORDER BY `activity_date` ASC');
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
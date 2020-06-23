<?php


class FoodModel
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    //show menu available for that particular day
    public function getAllFoodByDate(string $date): array
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM food INNER JOIN food_date ON food_date.food_id = food.id AND food_date.available_date = ?');
            $stmt->execute([$date]);
            $data = $stmt->fetchAll();

            if (!$data) {
                return array();
            }
            return $data;

        } catch (PDOException $exception) {
            error_log('FoodModel: getAllFoodByDate: ' . $exception->getMessage() . 'date: ' . $date);
            throw $exception;
        }
    }

}
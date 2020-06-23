<?php


class FoodOrderModel
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }


    public function getAllOrderByUserIdAndDate(int $userId, string $orderDate): array
    {
        try {
            $stmt = $this->connection->prepare('SELECT f.id, f.name, o.quantity FROM food f, food_order o WHERE f.id = o.food_id AND o.user_id=? AND o.order_date = ?');
            $stmt->execute([$userId, $orderDate]);
            $data = $stmt->fetchAll();

            if (!$data) {
                return array();
            }
            return $data;

        } catch (PDOException $exception) {
            error_log('FoodOrderModel: getAllOrderByUserIdAndDate: ' . $exception->getMessage() . 'userId: ' . $userId);
            throw $exception;
        }
    }


    public function getAllOrderByUserId(int $userId): array
    {
        try {
            $stmt = $this->connection->prepare('SELECT f.name, o.quantity, o.order_date FROM food f INNER JOIN food_order o ON o.food_id = f.id WHERE o.user_id = ?');
            $stmt->execute([$userId]);
            $data = $stmt->fetchAll();

            if (!$data) {
                return array();
            }
            return $data;

        } catch (PDOException $exception) {
            error_log('FoodOrderModel: getAllOrderByUserId: ' . $exception->getMessage() . 'userId: ' . $userId);
            throw $exception;
        }
    }


    public function insert(string $orderDate,int $userId, int $foodId, int $quantity): bool
    {
        try {
            $sql = 'INSERT INTO food_order (order_date, user_id, food_id, quantity) VALUES (:order_date, :user_id, :food_id, :quantity) ON DUPLICATE KEY UPDATE quantity = VALUES(quantity)';
            $stmt = $this->connection->prepare($sql);
            return $stmt->execute([
                ':order_date' => $orderDate,
                ':user_id' => $userId,
                ':food_id' => $foodId,
                ':quantity' => $quantity
            ]);

        } catch (PDOException $exception) {
            error_log('FoodOrderModel: insert: ' . $exception->getMessage() . 'userId: ' . $userId);
            throw $exception;
        }
    }

    //remove order
    public function removeZeroQuantity(int $userId): bool
    {
        try {
            $stmt = $this->connection->prepare('DELETE FROM food_order WHERE user_id =? AND quantity = 0');
            return $stmt->execute([$userId]);

        } catch (PDOException $exception) {
            error_log('FoodOrderModel: removeZeroQuantity: ' . $exception->getMessage() . 'userId: ' . $userId);
            throw $exception;
        }
    }
}
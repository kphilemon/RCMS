<?php


class AccommodationModel
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getByIdAndUserId(string $id, string $userId): array
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM accommodation WHERE id = ? AND user_id = ?');
            $stmt->execute([$id, $userId]);
            $data = $stmt->fetch();

            if (!$data) {
                return array();
            }
            return $data;

        } catch (PDOException $exception) {
            error_log('AccommodationModel: getById: ' . $exception->getMessage() . 'id: ' . $id . 'userId: ' . $userId);
            throw $exception;
        }
    }

    public function getAllByUserId(string $userId): array
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM accommodation WHERE user_id = ?');
            $stmt->execute([$userId]);
            $data = $stmt->fetchAll();

            if (!$data) {
                return array();
            }
            return $data;

        } catch (PDOException $exception) {
            error_log('AccommodationModel: getAllByUserId: ' . $exception->getMessage() . 'userId: ' . $userId);
            throw $exception;
        }
    }

    public function insert(int $userId, string $startDate, string $endDate, int $collegeId, string $purpose, string $supportingDocs): bool
    {
        try {
            $sql = 'INSERT INTO accommodation (user_id, start_date, end_date, college_id, purpose, supporting_docs, status) 
                    VALUES (:user_id, :start_date, :end_date, :college_id, :purpose, :supporting_docs, :status)';
            $stmt = $this->connection->prepare($sql);
            return $stmt->execute([
                ':user_id' => $userId,
                ':start_date' => $startDate,
                ':end_date' => $endDate,
                ':college_id' => $collegeId,
                ':purpose' => $purpose,
                ':supporting_docs' => $supportingDocs,
                ':status' => STATUS_PENDING
            ]);

        } catch (PDOException $exception) {
            error_log('AccommodationModel: insert: ' . $exception->getMessage() . 'userId: ' . $userId);
            throw $exception;
        }
    }

    public function update(int $id, int $userId, string $startDate, string $endDate, int $collegeId, string $purpose, string $supportingDocs): bool
    {
        try {
            $sql = 'UPDATE accommodation SET start_date = :start_date, end_date = :end_date, college_id = :college_id, purpose = :purpose, supporting_docs = :supporting_docs 
                    WHERE id = :id AND user_id = :user_id';
            $stmt = $this->connection->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':user_id' => $userId,
                ':start_date' => $startDate,
                ':end_date' => $endDate,
                ':college_id' => $collegeId,
                ':purpose' => $purpose,
                ':supporting_docs' => $supportingDocs,
            ]);

        } catch (PDOException $exception) {
            error_log('AccommodationModel: update: ' . $exception->getMessage() . 'id: ' . $id . 'userId: ' . $userId);
            throw $exception;
        }
    }

    public function delete(int $id, int $userId): bool
    {
        try {
            $stmt = $this->connection->prepare('DELETE FROM accommodation WHERE id = ? AND user_id = ?');
            return $stmt->execute([$id, $userId]);

        } catch (PDOException $exception) {
            error_log('AccommodationModel: delete: ' . $exception->getMessage() . 'id: ' . $id . 'userId: ' . $userId);
            throw $exception;
        }
    }
}

<?php


class AccommodationModel
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getByIdAndUserId(int $id, int $userId): array
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
            error_log('AccommodationModel: getByIdAndUserId: ' . $exception->getMessage() . 'id: ' . $id . 'userId: ' . $userId);
            throw $exception;
        }
    }

    public function getAllByUserId(int $userId): array
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

    public function getDocsNameByIdUserId(int $id, int $userId): array
    {
        try {
            $stmt = $this->connection->prepare('SELECT supporting_docs FROM accommodation WHERE id = ? AND user_id = ?');
            $stmt->execute([$id, $userId]);
            $data = $stmt->fetch();

            if (!$data) {
                return array();
            }
            return $data;

        } catch (PDOException $exception) {
            error_log('AccommodationModel: getDocsNameByIdUserId: ' . $exception->getMessage() . 'id: ' . $id . 'userId: ' . $userId);
            throw $exception;
        }
    }

    public function insert(int $userId, string $checkInDate, string $checkOutDate, int $collegeId, string $purpose, ?string $supportingDocs): string
    {
        try {
            $sql = 'INSERT INTO accommodation (user_id, check_in_date, check_out_date, college_id, purpose, supporting_docs) 
                    VALUES (:user_id, :check_in_date, :check_out_date, :college_id, :purpose, :supporting_docs)';
            $stmt = $this->connection->prepare($sql);
            $success = $stmt->execute([
                ':user_id' => $userId,
                ':check_in_date' => $checkInDate,
                ':check_out_date' => $checkOutDate,
                ':college_id' => $collegeId,
                ':purpose' => $purpose,
                ':supporting_docs' => $supportingDocs,
            ]);

            if (!$success) {
                return '';
            }

            return $this->connection->lastInsertId();

        } catch (PDOException $exception) {
            error_log('AccommodationModel: insert: ' . $exception->getMessage() . 'userId: ' . $userId);
            throw $exception;
        }
    }

    public function update(int $id, int $userId, string $checkInDate, string $checkOutDate, int $collegeId, string $purpose, ?string $supportingDocs): bool
    {
        try {

            // delete uploaded file is user specifies or user uploaded new file
            if ($supportingDocs != null) {
                $data = $this->getDocsNameByIdUserId($id, $userId);
                if (empty($data)) {
                    // empty means record does not exist
                    return false;
                }

                // delete uploaded file
                if (!empty($data['supporting_docs']) && file_exists(ACCOMMODATION_UPLOAD_PATH . $data['supporting_docs'])) {
                    unlink(ACCOMMODATION_UPLOAD_PATH . $data['supporting_docs']);
                }
                $sql = 'UPDATE accommodation SET check_in_date = :check_in_date, check_out_date = :check_out_date, college_id = :college_id, purpose = :purpose, supporting_docs = :supporting_docs 
                    WHERE id = :id AND user_id = :user_id AND status = :status';
                $stmt = $this->connection->prepare($sql);
                $success = $stmt->execute([
                    ':id' => $id,
                    ':user_id' => $userId,
                    ':check_in_date' => $checkInDate,
                    ':check_out_date' => $checkOutDate,
                    ':college_id' => $collegeId,
                    ':purpose' => $purpose,
                    ':supporting_docs' => $supportingDocs,
                    ':status' => STATUS_SUBMITTED,
                ]);
            } else {
                $sql = 'UPDATE accommodation SET check_in_date = :check_in_date, check_out_date = :check_out_date, college_id = :college_id, purpose = :purpose WHERE id = :id AND user_id = :user_id AND status = :status';
                $stmt = $this->connection->prepare($sql);
                $success = $stmt->execute([
                    ':id' => $id,
                    ':user_id' => $userId,
                    ':check_in_date' => $checkInDate,
                    ':check_out_date' => $checkOutDate,
                    ':college_id' => $collegeId,
                    ':purpose' => $purpose,
                    ':status' => STATUS_SUBMITTED,
                ]);
            }

            if ($success && $stmt->rowCount() != 0) {
                return true;
            }

            return false;

        } catch (PDOException $exception) {
            error_log('AccommodationModel: update: ' . $exception->getMessage() . 'id: ' . $id . 'userId: ' . $userId);
            throw $exception;
        }
    }

    public function delete(int $id, int $userId): bool
    {
        try {
            $data = $this->getDocsNameByIdUserId($id, $userId);
            if (empty($data)) {
                // empty means record does not exist
                return false;
            }

            // delete uploaded file
            if (!empty($data['supporting_docs']) && file_exists(ACCOMMODATION_UPLOAD_PATH . $data['supporting_docs'])) {
                unlink(ACCOMMODATION_UPLOAD_PATH . $data['supporting_docs']);
            }

            $stmt = $this->connection->prepare('DELETE FROM accommodation WHERE id = ? AND user_id = ?');
            return $stmt->execute([$id, $userId]);

        } catch (PDOException $exception) {
            error_log('AccommodationModel: delete: ' . $exception->getMessage() . 'id: ' . $id . 'userId: ' . $userId);
            throw $exception;
        }
    }
}

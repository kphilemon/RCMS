<?php


class IssueModel
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getByIdAndUserId(int $id, int $userId): array
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM issue WHERE id = ? AND user_id = ?');
            $stmt->execute([$id, $userId]);
            $data = $stmt->fetch();

            if (!$data) {
                return array();
            }
            return $data;

        } catch (PDOException $exception) {
            error_log('IssueModel: getByIdAndUserId: ' . $exception->getMessage() . 'id: ' . $id . 'userId: ' . $userId);
            throw $exception;
        }
    }

    public function getReportsByUserId(int $userId): array
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM issue WHERE user_id = ?');
            $stmt->execute([$userId]);
            $data = $stmt->fetchAll();

            if (!$data) {
                return array();
            }
            return $data;

        } catch (PDOException $exception) {
            error_log('IssueModel: getReportsByUserId: ' . $exception->getMessage() . 'userId: ' . $userId);
            throw $exception;
        }
    }

    public function getDocsNameByIdUserId(int $id, int $userId): array
    {
        try {
            $stmt = $this->connection->prepare('SELECT img FROM issue WHERE id = ? AND user_id = ?');
            $stmt->execute([$id, $userId]);
            $data = $stmt->fetch();

            if (!$data) {
                return array();
            }
            return $data;

        } catch (PDOException $exception) {
            error_log('IssueModel: getDocsNameByIdUserId: ' . $exception->getMessage() . 'id: ' . $id . 'userId: ' . $userId);
            throw $exception;
        }
    }

    public function insert(int $userId, string $problemtype, string $problemlocation, string $problemdetail, ?string $problemimage): string
    {
        try {
            $sql = 'INSERT INTO issue (user_id, type, location, details, img) 
                    VALUES (:user_id, :type, :location, :details, :img)';
            $stmt = $this->connection->prepare($sql);
            $success = $stmt->execute([
                ':user_id' => $userId,
                ':type' => $problemtype,
                ':location' => $problemlocation,
                ':details' => $problemdetail,
                ':img' => $problemimage,
            ]);

            if (!$success) {
                return '';
            }

            return $this->connection->lastInsertId();

        } catch (PDOException $exception) {
            error_log('IssueModel: insert: ' . $exception->getMessage() . 'userId: ' . $userId);
            throw $exception;
        }
    }

    public function update(int $id, int $userId, string $problemtype, string $problemlocation, string $problemdetail, ?string $problemimage): bool
    {
        try {

            // delete uploaded file is user specifies or user uploaded new file
            if ($problemimage != null){
                $data = $this->getDocsNameByIdUserId($id, $userId);
                if (empty($data)){
                    // empty means record does not exist
                    return false;
                }

                // delete uploaded file
                if (!empty($data['img']) && file_exists(ISSUES_UPLOAD_PATH.$data['img'])){
                    unlink(ISSUES_UPLOAD_PATH.$data['img']);
                }

                $sql = 'UPDATE issue SET type = :type, location = :location, details = :details, img = :img 
                    WHERE id = :id AND user_id = :user_id AND status = :status';
                $stmt = $this->connection->prepare($sql);
                $success = $stmt->execute([
                    ':id' => $id,
                    ':user_id' => $userId,
                    ':type' => $problemtype,
                    ':location' => $problemlocation,
                    ':details' => $problemdetail,
                    ':img' => $problemimage,
                    ':status' => STATUS_PENDING
                ]);
            } else{
                $sql = 'UPDATE issue SET type = :type, location = :location, details = :details WHERE id = :id AND user_id = :user_id AND status = :status';
                $stmt = $this->connection->prepare($sql);
                $success = $stmt->execute([
                    ':id' => $id,
                    ':user_id' => $userId,
                    ':type' => $problemtype,
                    ':location' => $problemlocation,
                    ':details' => $problemdetail,
                    ':status' => STATUS_PENDING
                ]);
            }

            if ($success && $stmt->rowCount() != 0) {
                return true;
            }

            return false;

        } catch (PDOException $exception) {
            error_log('IssueModel: update: ' . $exception->getMessage() . 'id: ' . $id . 'userId: ' . $userId);
            throw $exception;
        }
    }

    public function delete(int $id, int $userId): bool
    {
        try {
            $data = $this->getDocsNameByIdUserId($id, $userId);
            if (empty($data)){
                // empty means record does not exist
                return false;
            }

            // delete uploaded file
            if (!empty($data['img']) && file_exists(ISSUES_UPLOAD_PATH.$data['img'])){
                unlink(ISSUES_UPLOAD_PATH.$data['img']);
            }

            $stmt = $this->connection->prepare('DELETE FROM issue WHERE id = :id AND user_id = :user_id AND status <> :status');
            return $stmt->execute([
                ':id' => $id,
                ':user_id' => $userId,
                ':status' => STATUS_IN_PROGRESS
            ]);

        } catch (PDOException $exception) {
            error_log('IssueModel: delete: ' . $exception->getMessage() . 'id: ' . $id . 'userId: ' . $userId);
            throw $exception;
        }
    }
}
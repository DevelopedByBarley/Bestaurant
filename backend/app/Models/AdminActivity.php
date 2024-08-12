<?php

namespace App\Models;

use PDO;
use PDOException;
use Exception;

class AdminActivity extends Admin
{
    public function store($body, $adminId)
    {
        try {
            // Sanitize input data
            $content = filter_var($body["content"] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
            $contentInEn = isset($body["contentInEn"]) ? filter_var($body["contentInEn"], FILTER_SANITIZE_SPECIAL_CHARS) : null;
            $adminRef_id = filter_var($adminId ?? '', FILTER_SANITIZE_SPECIAL_CHARS);

            // Prepare the SQL statement with proper placeholders
            $stmt = $this->Pdo->prepare(
                "INSERT INTO `admin_activities` (`content`, `contentInEn`, `adminRef_id`, `created_at`)
                VALUES (:content, :contentInEn, :adminRef_id, current_timestamp())"
            );

            // Bind parameters to the statement
            $stmt->bindParam(':content', $content, PDO::PARAM_STR);
            $stmt->bindParam(':contentInEn', $contentInEn, PDO::PARAM_STR);
            $stmt->bindParam(':adminRef_id', $adminRef_id, PDO::PARAM_INT);

            // Execute the statement
            $stmt->execute();
        } catch (PDOException $e) {
            // Throw an exception with a detailed error message
            throw new Exception("An error occurred during the database operation in store method in AdminActivity model: " . $e->getMessage());
        }
    }

    public function getAdminActivities()
    {
        try {
            $limit = 10;
            // Prepare the SQL statement
            $stmt = $this->Pdo->prepare(" SELECT activity.id,
                activity.content,
                activity.contentInEn,
                activity.adminRef_id,
                activity.created_at,
                admins.id as admin_id,
                admins.name as admin_name,
                admins.email as admin_email,
                admins.avatar FROM admin_activities as activity JOIN admins ON activity.adminRef_id = admins.id  ORDER BY activity.created_at DESC LIMIT $limit");
            $stmt->execute();
            $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $oldestActivityId = (int)$activities[0]['id'] - $limit;
            self::deleteExpiredActivities($oldestActivityId);

            return $activities;
        } catch (PDOException $e) {
            // Throw an exception with a detailed error message
            throw new Exception("An error occurred during the database operation in getAdminActivities method in AdminActivity model: " . $e->getMessage());
        }
    }


    private function deleteExpiredActivities($oldestActivityId)
    {
        try {
            // Step 3: Delete activities older than the oldest among the latest 6
            if ($oldestActivityId !== null) {
                $stmt = $this->Pdo->prepare("
            DELETE FROM admin_activities
            WHERE id <= :oldest_id
        ");
                $stmt->bindParam(':oldest_id', $oldestActivityId, PDO::PARAM_INT);
                $stmt->execute();
            }
        } catch (PDOException $e) {
            // Throw an exception with a detailed error message
            throw new Exception("An error occurred during the database operation in deleteExpiredActivities method in AdminActivity model: " . $e->getMessage());
        }
    }


    
}

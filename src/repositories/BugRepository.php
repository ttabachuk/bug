<?php
include('../includes/dbh.inc.php');
include('../models/Bug.php');
include('../models/BugStatus.php');
include('../models/Priority.php');

class BugRepository {

    private $pdo;
    private $table = 'bugs';
    private $model = 'Bug';

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function getAll() {
        $query = "SELECT * FROM {$this->table}";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, $this->model);
    }

    public function get($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = (:id)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, $this->model);
        return $stmt->fetch();
    }


    public function getOpenStatuses(){
        $query = "SELECT * FROM bug_status WHERE Id IN (1, 2)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, 'BugStatus');
    }

    public function getPriorities(){
        $query = "SELECT * FROM priority";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Priority');
    }

    public function insert($fields) {

        // if we assign a bug to a user that is part of a different project, update the project id of the user
        $query = "UPDATE user_details SET ProjectId = :projectId WHERE Id = :assignedToId;
                  INSERT INTO {$this->table} (projectId, ownerId, assignedToId, statusId, priorityId, summary, description, dateRaised, targetDate) 
                  VALUES(:projectId, :ownerId, :assignedToId, :statusId, :priorityId, :summary, :description, :dateRaised, :targetDate)";
        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(":projectId", $fields['project_id']);
        $stmt->bindParam(":ownerId", $fields['owner']);
        $stmt->bindParam(":assignedToId", $fields['assigned_to']);
        $stmt->bindParam(":statusId", $fields['bug_status']);
        $stmt->bindParam(":priorityId", $fields['priority']);
        $stmt->bindParam(":summary", $fields['summary']);
        $stmt->bindParam(":description", $fields['description']);
        $stmt->bindParam(":dateRaised", $fields['date_raised']);
        $stmt->bindParam(":targetDate", $fields['target_date']);

        $stmt->execute();
    }

    public function getFilteredBugs($filterType, $projectId = null) {

        $query = "SELECT * FROM bugs WHERE 1=1";
        $params = [];

        // select any bugs for a specific project
        if (!empty($projectId)) {
            $query .= " AND projectId = :projectId";
            $params[':projectId'] = $projectId;
        }

        // include open or overdue bugs
        if ($filterType === 'open') {
            $query .= " AND statusId IN (1, 2)";
        } elseif ($filterType === 'overdue') {
            $query .= " AND targetDate < CURDATE() AND statusId NOT IN (3)";
        }

        $query .= " ORDER BY projectId, targetDate ASC";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_CLASS, $this->model);
    }

    public function close($fixDescription, $id) {
        
        $query = "
            UPDATE {$this->table}
            SET
                fixDescription = :fixDescription,
                statusId = 3,
                dateClosed = NOW()
            WHERE id = :id
        ";

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(":fixDescription", $fixDescription);
        $stmt->bindParam(":id", $id);

        $stmt->execute();
    }

    public function update($fields) {

        $query = "UPDATE user_details SET ProjectId = :projectId WHERE Id = :assignedToId;

                UPDATE {$this->table}
                SET projectId = :projectId,
                    ownerId = :ownerId,
                    assignedToId = :assignedToId,
                    statusId = :statusId,
                    priorityId = :priorityId,
                    summary = :summary,
                    description = :description,
                    dateRaised = :dateRaised,
                    targetDate = :targetDate
                WHERE id = :id";

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(":projectId", $fields['project_id']);
        $stmt->bindParam(":ownerId", $fields['owner']);
        $stmt->bindParam(":assignedToId", $fields['assigned_to']);
        $stmt->bindParam(":statusId", $fields['bug_status']);
        $stmt->bindParam(":priorityId", $fields['priority']);
        $stmt->bindParam(":summary", $fields['summary']);
        $stmt->bindParam(":description", $fields['description']);
        $stmt->bindParam(":dateRaised", $fields['date_raised']);
        $stmt->bindParam(":targetDate", $fields['target_date']);
        $stmt->bindParam(":id", $fields['id']);

        $stmt->execute();
    }
}
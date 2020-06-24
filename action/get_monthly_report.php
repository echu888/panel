<?php
namespace Action {
require_once '../auth/authenticate.php';
require_once '../utility/debug.php';
require_once '../database.php';

$input = $_GET;

$report = array_key_exists('report',$input) ? $input['report'] : "";

if ($report == "") {
    echo "No report passed";
    die();    
}

$year = array_key_exists('year',$input) ? (int)$input['year'] : date('Y', strtotime('now'));
$month = array_key_exists ('month', $input) ? (int)$input['month'] : date('m', strtotime('- 1 month'));

switch ($report) {
    case "applications":
        $queryString = "SELECT COUNT(*) as total FROM Students 
            INNER JOIN StudentNote
            ON Students.student_id =  StudentNote.student_id
            where StudentNote.code = 1100
            AND MONTH(NoteTimeStamp) = $month
            AND YEAR(NoteTimeStamp) = $year";
        break;
    case "avgwaittime":
        $queryString = "";
        break;
    case "pcntnewoldstudents":
              //SUM(IF(StudentNumberClasses.number_of_finished_classes = 0, 1, 0)) AS number_of_new_students,
              //SUM(IF(StudentNumberClasses.number_of_finished_classes = 0, 0, 1)) AS number_of_continuing_students,
              //COUNT(*) AS total_students,
              //SUM(IF(StudentNumberClasses.number_of_finished_classes = 0, 1, 0)) * 100 / COUNT(*) AS percent_of_new_students,
              //SUM(IF(StudentNumberClasses.number_of_finished_classes = 0, 0, 1)) * 100 / COUNT(*) AS percent_of_continuing_students
        $queryString = "SELECT
              SUM(IF(StudentNumberClasses.number_of_finished_classes = 0, 1, 0)) * 100 / COUNT(*) AS total
            FROM Students
            INNER JOIN (
              SELECT
                Students.student_id,
                count(Groups.group_id) AS number_of_finished_classes
              FROM Students
              LEFT JOIN StudentGroup ON Students.student_id = StudentGroup.student_id
              LEFT JOIN Groups ON StudentGroup.group_id = Groups.group_id
              WHERE (Groups.startdate IS NULL 
                    OR Groups.startdate < DATE_FORMAT(" . $year . '-' . $month . '-01' . ",'%Y-%m-01'))
              AND (Groups.status IS NULL OR Groups.status = 'Finished')
              GROUP BY Students.student_id
            ) AS StudentNumberClasses ON Students.student_id = StudentNumberClasses.student_id
            INNER JOIN StudentGroup ON Students.student_id = StudentGroup.student_id
            INNER JOIN Groups ON StudentGroup.group_id = Groups.group_id
            WHERE MONTH(Groups.startdate) = $month
            AND YEAR(Groups.startdate) = $year";
        break;
    case "ttlclassesstarted":
        $queryString = "SELECT COUNT(group_id) AS total
            FROM `Groups` WHERE
            Status = 'Started'
            AND MONTH(StartDate) = $month
            AND YEAR(StartDate) = $year";
        break;
    case "ttlclassescompleted":
        $queryString = "SELECT COUNT(group_id) AS total
            FROM `Groups`
            WHERE Status = 'Finished'
            AND MONTH(ActualFinishDate) = $month
            AND YEAR(ActualFinishDate) = $year";
        break;
    case "ttlstudentsstarted":
        $queryString = "SELECT count(1) as total
            FROM `Students` 
            INNER JOIN StudentGroup
            ON StudentGroup.student_id = Students.student_id
            INNER JOIN Groups
            ON Groups.group_id = StudentGroup.group_id
            WHERE Groups.status = 'Started'
            AND MONTH(StartDate) = $month
            AND YEAR(StartDate) = $year";
        break;
    case "ttlstudentscompleted":
        $queryString = "SELECT count(1) as total
            FROM `Students` 
            INNER JOIN StudentGroup
            ON StudentGroup.student_id = Students.student_id
            INNER JOIN Groups
            ON Groups.group_id = StudentGroup.group_id
            WHERE Groups.status = 'Finished'
            AND MONTH(ActualFinishDate) = $month
            AND YEAR(ActualFinishDate) = $year";
        break;
    case "avgstudytimeweek":
        $queryString = "SELECT
            ROUND(AVG(M + Tu + W + Th + F), 1) AS total
            FROM `Groups` AS G";
        break;
}

// var_dump($queryString);

$connection = getConnection();
$statement = $connection->query( $queryString );
$records   = $statement->fetch( \PDO::FETCH_ASSOC );

$result = array();
$result = $records;
$resultString = json_encode( $result );

echo $resultString; //< json output for ajax call

}
?>

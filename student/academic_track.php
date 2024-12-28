<?php
session_start();
include '../database.php';

$studentID = $_SESSION['studentID'];

$query = "
    SELECT subject_code, subject_name, subject_units, grade
    FROM student_{$studentID}
    ORDER BY subject_code";

$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$subjects = $result->fetch_all(MYSQLI_ASSOC);

$totalUnits = 0;
$weightedGradeSum = 0;

foreach ($subjects as $subject) {
    if ($subject['grade'] !== null) {
        $subjectUnits = $subject['subject_units'];
        $totalUnits += $subjectUnits;
        $weightedGradeSum += $subject['grade'] * $subjectUnits;
    }
}

$gwa = ($totalUnits > 0) ? number_format($weightedGradeSum / $totalUnits, 2) : 0;

$profileCheckQuery = "SELECT CONCAT(sp.first_name,' ', sp.middle_name,' ', sp.last_name) AS name, sp.year_level, c.course_name, sp.created_at
                FROM student_profiles sp
                LEFT JOIN courses c ON sp.course_id = c.id
                WHERE studentID = ?";

$profileCheck = $conn->prepare($profileCheckQuery);
$profileCheck->bind_param('i', $studentID);
$profileCheck->execute();
$profileResult = $profileCheck->get_result();
$student = $profileResult->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Academic Track</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f9;
            color: #333;
        }

        .report-card {
            border: 1px solid #333;
            border-radius: 10px;
            padding: 20px;
            background-color: #fff;
            width: 80%;
            margin: 0 auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header .left,
        .header .right {
            flex: 1;
        }

        .header .left {
            padding-right: 20px;
        }

        .header .right {
            padding-left: 20px;
            text-align: right;
        }

        h2 {
            font-size: 24px;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .gwa {
            float: right;
            padding-top: 35px;
            font-size: 18px;
        }

        .gwa span {
            font-size: 20px;
            font-weight: bold;
            padding: 5px 10px;
            border: 2px solid #333;
            border-radius: 5px;
        }
        .download-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
            display: block;
            width: 200px;
            margin-left: auto;
            margin-right: auto;
        }

        .download-btn:hover {
            background-color: #45a049;
        }
        @media (max-width: 650px){
           body{
            display: block;
           }
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
</head>
<body>
    <div class="report-card">
    <h2>Student Report Card</h2>
        <div class="header">
            <div class="left">
                <p><strong>Student Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>
                <p><strong>Student ID:</strong> <?php echo $studentID; ?></p>
                <p><strong>Year level:</strong> <?php echo htmlspecialchars($student['year_level']); ?>st year</p>
            </div>
            <div class="right">
                <p><strong>Course:</strong> <?php echo htmlspecialchars($student['course_name']); ?></p>
                <p><strong>Enrollment Date:</strong> <?php echo htmlspecialchars($student['created_at']); ?></p>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Units</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subjects as $subject): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($subject['subject_code']); ?></td>
                        <td><?php echo htmlspecialchars($subject['subject_name']); ?></td>
                        <td><?php echo htmlspecialchars($subject['subject_units']); ?></td>
                        <td><?php echo $subject['grade'] !== null ? htmlspecialchars($subject['grade']) : 'N/A'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="gwa">
            <strong>GWA:</strong> <span><?php echo $gwa; ?></span>
        </div>
        <button class="download-btn" onclick="downloadPDF()">Download PDF</button>
    </div>

    <script>
    function downloadPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        doc.setFontSize(14);
        doc.text("Student Report Card", 105, 20, null, null, "center");

        doc.setFontSize(12);
        doc.text("Student Name: <?php echo addslashes(htmlspecialchars($student['name'])); ?>", 20, 30);
        doc.text("Student ID: <?php echo addslashes($studentID); ?>", 20, 40);
        doc.text("Course: <?php echo addslashes(htmlspecialchars($student['course_name'])); ?>", 20, 50);
        doc.text("Enrollment Date: <?php echo addslashes(htmlspecialchars($student['created_at'])); ?>", 20, 60);

        doc.setFontSize(11);
        const tableData = [];
        const columns = ["Subject Code", "Subject Name", "Units", "Grade"];

        <?php foreach ($subjects as $subject): ?>
            tableData.push([
                "<?php echo addslashes($subject['subject_code']); ?>",
                "<?php echo addslashes($subject['subject_name']); ?>",
                "<?php echo addslashes($subject['subject_units']); ?>",
                "<?php echo $subject['grade'] !== null ? addslashes($subject['grade']) : 'N/A'; ?>"
            ]);
        <?php endforeach; ?>

        doc.autoTable({
            head: [columns],
            body: tableData,
            startY: 70,
            margin: { top: 10 },
            styles: {
                fontSize: 10,
                cellPadding: 4,
                halign: 'center',
                valign: 'middle',
                lineColor: [44, 62, 80],
                lineWidth: 0.3,
                overflow: 'linebreak',
                cellWidth: 'auto',
            },
            headStyles: {
                fillColor: [76, 175, 80],
                textColor: [255, 255, 255],
                fontStyle: 'bold',
            },
            alternateRowStyles: {
                fillColor: [240, 240, 240],
            },
        });

        doc.text("GWA: <?php echo addslashes($gwa); ?>", 150, doc.lastAutoTable.finalY + 20);

        const studentID = "<?php echo addslashes($studentID); ?>";
        doc.save(studentID + '_Report_Card.pdf');
    }
</script>

</body>
</html>


<?php
    session_start();

    if(!isset($_SESSION['admin_id'])){
        header("Location: ../credentials/adminlogin.php");
        exit;
    }
    include './dashoard.php';
    include './admit_student.php';
    include './assign_grades.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 bg-light vh-100 d-md-block d-none">
                <nav class="nav flex-column py-4">
                    <a class="nav-link fs-5 text-dark" href="#" data-section="dashboard">Dashboard</a>
                    <a class="nav-link fs-5 text-dark" href="#" data-section="admit">Admit Students</a>
                    <a class="nav-link fs-5 text-dark" href="#" data-section="g_assessment">Grade Assessment</a>
                    <a class="nav-link fs-5 text-dark" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</a>
                </nav>
            </div>

            <!-- Mobile Navbar -->
            <nav class="navbar navbar-expand-md bg-light d-md-none">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNavbar" aria-controls="mobileNavbar" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="mobileNavbar">
                        <ul class="navbar-nav flex-column">
                            <li class="nav-item"><a class="nav-link text-dark" href="#" data-section="dashboard">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link text-dark" href="#" data-section="admit">Admit Students</a></li>
                            <li class="nav-item"><a class="nav-link text-dark" href="#" data-section="g_assessment">Grade Assessment</a></li>
                            <li class="nav-item"><a class="nav-link text-dark" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="col-md-10">
                <!-- Dashboard -->
                <div id="dashboard" class="content-section p-4">
                    <h2>Dashboard</h2>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-primary text-white text-center p-3">
                                <h4>Total Students</h4>
                                <h2><?php echo $studentCount['count']; ?></h2>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-warning text-dark text-center p-3">
                                <h4>Pending Reviews</h4>
                                <h2><?php echo $pendingReviews['count']; ?></h2>
                            </div>
                        </div>
                    </div>

                    <!-- Courses Table -->
                    <div>
                        <h4>Courses Availability</h4>
                        <form method="POST" action="update_courses.php">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Course</th>
                                        <th>Available Slots</th>
                                        <th>Update</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($courses as $course): ?>
                                    <tr>
                                        <td><?php echo $course['course_name']; ?></td>
                                        <td><?php echo $course['course_availability']; ?></td>
                                        <td>
                                            <input type="hidden" name="course_ids[]" value="<?php echo $course['id']; ?>">
                                            <input type="number" name="course_slots[]" value="<?php echo $course['course_availability']; ?>" class="form-control form-control-sm">
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-success">Update Courses</button>
                        </form>
                    </div> <!-- Courses Table Ends -->
                </div> <!-- Dashboard Ends -->

                <!-- Admit Students -->
                <div id="admit" class="content-section p-4">
                    <h2>Admit Students</h2>
                    <table class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Details</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendingStudents as $index => $student): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($student['name']); ?></td>
                                <td><?php echo htmlspecialchars($student['course_name']); ?></td>
                                <td>
                                    <a href="#studentDetailsModal" data-bs-toggle="modal" data-bs-target="#studentDetailsModal<?php echo $student['studentID']; ?>" class="btn btn-info btn-sm">Details</a>
                                </td>
                                <td>
                                    <form method="POST" action="admit_student.php" class="d-inline">
                                        <input type="hidden" name="student_id" value="<?php echo $student['studentID']; ?>">
                                        <input type="hidden" name="course_id" value="<?php echo $student['course_id']; ?>">
                                        <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                                    </form>
                                    <form method="POST" action="admit_student.php" class="d-inline">
                                        <input type="hidden" name="student_id" value="<?php echo $student['studentID']; ?>">
                                        <button type="submit" name="action" value="deny" class="btn btn-danger btn-sm">Deny</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Modal for Student Details -->
                <?php foreach ($pendingStudents as $student): ?>
                <div class="modal fade" id="studentDetailsModal<?php echo $student['studentID']; ?>" tabindex="-1" aria-labelledby="studentDetailsModalLabel<?php echo $student['studentID']; ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="studentDetailsModalLabel<?php echo $student['studentID']; ?>">Student Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
                                <p><strong>Created At:</strong> <?php echo htmlspecialchars($student['created_at']); ?></p>
                                <p><strong>Course:</strong> <?php echo htmlspecialchars($student['course_name']); ?></p>
                                <p><strong>Reason for Course:</strong> <?php echo htmlspecialchars($student['reason_for_course']); ?></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <!-- Grade Assessment -->
                <div id="g_assessment" class="content-section p-4">
                    <h2 class="mb-4">Grade Assessment</h2>
                    <?php
                    include '../database.php';

                    // Fetch approved students
                    $query = "SELECT sp.studentID, CONCAT(sp.first_name, ' ', sp.middle_name, ' ', sp.last_name) AS student_name, c.course_name
                            FROM student_profiles sp
                            JOIN courses c ON sp.course_id = c.id
                            WHERE sp.status = 'approved'";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Student Name</th>
                                        <th>Course Selected</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php while ($row = $result->fetch_assoc()): 
                                    $studentTable = "student_" . $row['studentID'];
                                    $subjectsQuery = "SELECT subject_code, subject_name FROM $studentTable";
                                    $subjectsResult = $conn->query($subjectsQuery);
                                    ?>
                                    <tr>
                                        <td><?= $row['studentID'] ?></td>
                                        <td><?= htmlspecialchars($row['student_name']) ?></td>
                                        <td><?= htmlspecialchars($row['course_name']) ?></td>
                                        <td>
                                            <button type="button" 
                                                    class="btn btn-primary btn-sm assign-grades-btn" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#assignGradesModal"
                                                    data-student-id="<?= $row['studentID'] ?>"
                                                    data-student-name="<?= htmlspecialchars($row['student_name']) ?>"
                                                    data-subjects="<?= htmlspecialchars(json_encode($subjectsResult->fetch_all(MYSQLI_ASSOC))) ?>">
                                                Assign Grades
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-danger btn-sm drop-student-btn" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#dropStudentModal"
                                                    data-student-id="<?= $row['studentID'] ?>"
                                                    data-student-name="<?= htmlspecialchars($row['student_name']) ?>">
                                                Drop Student
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">No approved students found.</div>
                    <?php endif; ?>
                </div>

                <!-- Modal for Assigning Grades -->
                <div class="modal fade" id="assignGradesModal" tabindex="-1" aria-labelledby="assignGradesLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="assignGradesLabel">Assign Grades</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="assign_grades.php" method="POST">
                                <div class="modal-body">
                                    <input type="hidden" name="student_id" id="modalStudentId">
                                    <p><strong>Student:</strong> <span id="modalStudentName"></span></p>
                                    <div id="subjectGradesContainer">
                                        <!-- Subject and grades will be dynamically inserted here -->
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Save Grades</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal for Dropping Student -->
                <div class="modal fade" id="dropStudentModal" tabindex="-1" aria-labelledby="dropStudentLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="dropStudentLabel">Drop Student</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="dropstudents.php" method="POST">
                                <div class="modal-body">
                                    <input type="hidden" name="student_id" id="dropStudentId">
                                    <p>Are you sure you want to drop <strong id="dropStudentName"></strong>?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-danger">Drop Student</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Grade Assessment Ends -->
            </div>
        </div>
    </div>

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Logout Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Are you sure you want to log out?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="logout()">Logout</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            showSection('dashboard');
        });

        document.querySelectorAll('[data-section]').forEach(link => {
            link.addEventListener('click', event => {
                event.preventDefault();
                const sectionId = link.getAttribute('data-section');
                showSection(sectionId);
            });
        });

        function showSection(sectionId) {
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.add('d-none');
            });
            document.getElementById(sectionId).classList.remove('d-none');
        }

        function logout() {
            alert("Logged out successfully!");
            window.location.href = "../logout.php";
        }

        document.addEventListener('DOMContentLoaded', () => {
        const assignGradesModal = document.getElementById('assignGradesModal');
        const modalStudentId = document.getElementById('modalStudentId');
        const modalStudentName = document.getElementById('modalStudentName');
        const subjectGradesContainer = document.getElementById('subjectGradesContainer');

        document.querySelectorAll('.assign-grades-btn').forEach(button => {
            button.addEventListener('click', () => {
                const studentId = button.getAttribute('data-student-id');
                const studentName = button.getAttribute('data-student-name');
                const subjects = JSON.parse(button.getAttribute('data-subjects'));

                modalStudentId.value = studentId;
                modalStudentName.textContent = studentName;

                const storedGrades = JSON.parse(localStorage.getItem(`grades_${studentId}`)) || {};

                let subjectRows = '';
                if (subjects.length > 0) {
                    subjects.forEach(subject => {
                        const grade = storedGrades[subject.subject_code] || '';
                        subjectRows += `
                            <div class="mb-3 row">
                                <label class="col-sm-6 col-form-label">${subject.subject_name}</label>
                                <div class="col-sm-6">
                                    <input type="number" name="grades[${subject.subject_code}]" value="${grade}" class="form-control grade-input" data-subject-code="${subject.subject_code}" required>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    subjectRows = `<div class="alert alert-warning">No subjects assigned to this student.</div>`;
                }
                subjectGradesContainer.innerHTML = subjectRows;

                document.querySelectorAll('.grade-input').forEach(input => {
                    input.addEventListener('input', () => {
                        const updatedGrades = JSON.parse(localStorage.getItem(`grades_${studentId}`)) || {};
                        updatedGrades[input.dataset.subjectCode] = input.value;
                        localStorage.setItem(`grades_${studentId}`, JSON.stringify(updatedGrades));
                    });
                });
            });
        });
    });

    document.querySelectorAll('.drop-student-btn').forEach(button => {
    button.addEventListener('click', function () {
        const studentId = this.getAttribute('data-student-id');
        const studentName = this.getAttribute('data-student-name');

        document.getElementById('dropStudentId').value = studentId;
        document.getElementById('dropStudentName').textContent = studentName;
        });
    });

    </script>
</body>
</html>

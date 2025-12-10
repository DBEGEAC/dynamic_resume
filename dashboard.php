<?php
session_start();
include 'includes/db_connection.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Handle form submissions for updating content
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_about'])) {
        $content = $_POST['content'];
        if (isset($_POST['about_id']) && !empty($_POST['about_id'])) {
            $about_id = $_POST['about_id'];
            $update_sql = "UPDATE about SET content='$content' WHERE id=$about_id";
        } else {
            $update_sql = "INSERT INTO about (content) VALUES ('$content')";
        }
        $conn->query($update_sql);
        header("Location: dashboard.php");
    } elseif (isset($_POST['add_skill'])) {
        $skill_name = $_POST['skill_name'];
        $proficiency = $_POST['proficiency'];
        $conn->query("INSERT INTO skills (skill_name, proficiency) VALUES ('$skill_name', '$proficiency')");
        header("Location: dashboard.php");
    } elseif (isset($_POST['update_skill'])) {
        $skill_id = $_POST['skill_id'];
        $skill_name = $_POST['skill_name'];
        $proficiency = $_POST['proficiency'];
        $conn->query("UPDATE skills SET skill_name='$skill_name', proficiency='$proficiency' WHERE id=$skill_id");
        header("Location: dashboard.php");
    } elseif (isset($_GET['delete_skill'])) {
        $skill_id = $_GET['delete_skill'];
        $conn->query("DELETE FROM skills WHERE id=$skill_id");
        header("Location: dashboard.php");
    } elseif (isset($_POST['add_project'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $languages_used = $_POST['languages_used'];
        $github_link = $_POST['github_link'];
        $conn->query("INSERT INTO projects (title, description, languages_used, github_link) VALUES ('$title', '$description', '$languages_used', '$github_link')");
        header("Location: dashboard.php");
    } elseif (isset($_POST['update_project'])) {
        $project_id = $_POST['project_id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $languages_used = $_POST['languages_used'];
        $github_link = $_POST['github_link'];
        $conn->query("UPDATE projects SET title='$title', description='$description', languages_used='$languages_used', github_link='$github_link' WHERE id=$project_id");
        header("Location: dashboard.php");
    } elseif (isset($_GET['delete_project'])) {
        $project_id = $_GET['delete_project'];
        $conn->query("DELETE FROM projects WHERE id=$project_id");
        header("Location: dashboard.php");
    } elseif (isset($_POST['add_education'])) {
        $institution = $_POST['institution'];
        $degree = $_POST['degree'];
        $year = $_POST['year'];
        $conn->query("INSERT INTO education (institution, degree, year) VALUES ('$institution', '$degree', '$year')");
        header("Location: dashboard.php");
    } elseif (isset($_POST['update_education'])) {
        $education_id = $_POST['education_id'];
        $institution = $_POST['institution'];
        $degree = $_POST['degree'];
        $year = $_POST['year'];
        $conn->query("UPDATE education SET institution='$institution', degree='$degree', year='$year' WHERE id=$education_id");
        header("Location: dashboard.php");
    } elseif (isset($_GET['delete_education'])) {
        $education_id = $_GET['delete_education'];
        $conn->query("DELETE FROM education WHERE id=$education_id");
        header("Location: dashboard.php");
    }
}

// Fetch About Content
$about_sql = "SELECT * FROM about LIMIT 1";
$about_result = $conn->query($about_sql);
$about_content = $about_result ? $about_result->fetch_assoc() : null;

// Fetch Skills
$skills_sql = "SELECT * FROM skills";
$skills_result = $conn->query($skills_sql);

// Fetch Projects
$projects_sql = "SELECT * FROM projects";
$projects_result = $conn->query($projects_sql);

// Fetch Education
$education_sql = "SELECT * FROM education";
$education_result = $conn->query($education_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Dashboard</h1>
        <div class="d-flex justify-content-between mb-4">
            <a href="index.php" class="btn btn-primary">View Resume</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h3>About Yourself</h3>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="about_id" value="<?php echo $about_content ? $about_content['id'] : ''; ?>">
                    <div class="mb-3">
                        <textarea class="form-control" name="content" rows="5" required><?php echo $about_content ? $about_content['content'] : ''; ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" name="update_about">Update</button>
                </form>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h3>Technical Skills</h3>
            </div>
            <div class="card-body">
                <form method="POST" class="mb-4">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="text" class="form-control" name="skill_name" placeholder="Skill Name" required>
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control" name="proficiency" placeholder="Proficiency" required>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-success" name="add_skill">Add Skill</button>
                        </div>
                    </div>
                </form>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Skill Name</th>
                            <th>Proficiency</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($skill = $skills_result->fetch_assoc()): ?>
                            <tr>
                                <form method="POST">
                                    <input type="hidden" name="skill_id" value="<?php echo $skill['id']; ?>">
                                    <td><input type="text" class="form-control" name="skill_name" value="<?php echo $skill['skill_name']; ?>" required></td>
                                    <td><input type="text" class="form-control" name="proficiency" value="<?php echo $skill['proficiency']; ?>" required></td>
                                    <td>
                                        <button type="submit" class="btn btn-primary" name="update_skill">Update</button>
                                        <a href="dashboard.php?delete_skill=<?php echo $skill['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this skill?')">Delete</a>
                                    </td>
                                </form>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h3>Projects</h3>
            </div>
            <div class="card-body">
                <form method="POST" class="mb-4">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="title" placeholder="Title" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="languages_used" placeholder="Languages Used" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="github_link" placeholder="GitHub Link" required>
                        </div>
                        <div class="col-md-3">
                            <textarea class="form-control" name="description" placeholder="Description" required></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success mt-2" name="add_project">Add Project</button>
                </form>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Languages Used</th>
                            <th>GitHub Link</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $projects_result->data_seek(0);
                        while ($project = $projects_result->fetch_assoc()): ?>
                            <tr>
                                <form method="POST">
                                    <input type="hidden" name="project_id" value="<?php echo $project['id']; ?>">
                                    <td><input type="text" class="form-control" name="title" value="<?php echo $project['title']; ?>" required></td>
                                    <td><input type="text" class="form-control" name="languages_used" value="<?php echo $project['languages_used']; ?>" required></td>
                                    <td><input type="text" class="form-control" name="github_link" value="<?php echo $project['github_link']; ?>" required></td>
                                    <td><textarea class="form-control" name="description" required><?php echo $project['description']; ?></textarea></td>
                                    <td>
                                        <button type="submit" class="btn btn-primary" name="update_project">Update</button>
                                        <a href="dashboard.php?delete_project=<?php echo $project['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this project?')">Delete</a>
                                    </td>
                                </form>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h3>Education</h3>
            </div>
            <div class="card-body">
                <form method="POST" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="institution" placeholder="Institution" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="degree" placeholder="Degree" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="year" placeholder="Year" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success mt-2" name="add_education">Add Education</button>
                </form>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Institution</th>
                            <th>Degree</th>
                            <th>Year</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($edu = $education_result->fetch_assoc()): ?>
                            <tr>
                                <form method="POST">
                                    <input type="hidden" name="education_id" value="<?php echo $edu['id']; ?>">
                                    <td><input type="text" class="form-control" name="institution" value="<?php echo $edu['institution']; ?>" required></td>
                                    <td><input type="text" class="form-control" name="degree" value="<?php echo $edu['degree']; ?>" required></td>
                                    <td><input type="text" class="form-control" name="year" value="<?php echo $edu['year']; ?>" required></td>
                                    <td>
                                        <button type="submit" class="btn btn-primary" name="update_education">Update</button>
                                        <a href="dashboard.php?delete_education=<?php echo $edu['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this education entry?')">Delete</a>
                                    </td>
                                </form>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

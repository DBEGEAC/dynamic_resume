<?php
include 'includes/db_connection.php';

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

// Fetch Personal Info
$personal_info_sql = "SELECT * FROM personal_info";
$personal_info_result = $conn->query($personal_info_sql);

// Fetch Seminars
$seminars_sql = "SELECT * FROM seminars";
$seminars_result = $conn->query($seminars_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donie Benedict E. Gariando - Resume</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .resume-container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            background-color: #343a40;
            color: white;
            padding: 20px;
            margin: -20px -20px 20px -20px;
        }
        .profile-picture {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            margin-bottom: 10px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            border-bottom: 2px solid #343a40;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="resume-container">
        <div class="header">
            <img src="your-photo.jpg" alt="Profile Picture" class="profile-picture">
            <h1>DONIE BENEDICT E. GARIANDO</h1>
            <p>BSCS - INCOMING 4 YEAR STUDENT</p>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="section">
                    <h3 class="section-title">CONTACT</h3>
                    <p>📞 09954524475</p>
                    <p>📞 09947153554</p>
                    <p>✉️ gdonie6@gmail.com</p>
                    <p>📍 Blk. 10 Lot 7, Barangay Fatima 1, Dasmariñas City, Cavite</p>
                </div>

                <div class="section">
                    <h3 class="section-title">TECHNICAL SKILLS</h3>
                    <ul>
                        <?php if ($skills_result && $skills_result->num_rows > 0): ?>
                            <?php while ($skill = $skills_result->fetch_assoc()): ?>
                                <li><?php echo htmlspecialchars($skill['skill_name']); ?> - <?php echo htmlspecialchars($skill['proficiency']); ?></li>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <li>No skills listed.</li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="section">
                    <h3 class="section-title">PERSONAL SKILLS</h3>
                    <ul>
                        <?php
                        if ($personal_info_result && $personal_info_result->num_rows > 0) {
                            $personal_info_result->data_seek(0);
                            while ($info = $personal_info_result->fetch_assoc()) {
                                if (strpos($info['label'], 'Personal Skill:') !== false) {
                                    echo '<li>' . htmlspecialchars(str_replace('Personal Skill: ', '', $info['value'])) . '</li>';
                                }
                            }
                            $personal_info_result->data_seek(0);
                        } else {
                            echo '<li>No personal skills listed.</li>';
                        }
                        ?>
                    </ul>
                </div>

                <div class="section">
                    <h3 class="section-title">PERSONAL INFORMATION</h3>
                    <ul>
                        <?php
                        if ($personal_info_result && $personal_info_result->num_rows > 0) {
                            while ($info = $personal_info_result->fetch_assoc()) {
                                if (strpos($info['label'], 'Personal Skill:') === false) {
                                    echo '<li>' . htmlspecialchars($info['label']) . ": " . htmlspecialchars($info['value']) . '</li>';
                                }
                            }
                            $personal_info_result->data_seek(0);
                        } else {
                            echo '<li>No personal information listed.</li>';
                        }
                        ?>
                    </ul>
                </div>

                <div class="section">
                    <h3 class="section-title">SEMINARS ATTENDED</h3>
                    <ul>
                        <?php if ($seminars_result && $seminars_result->num_rows > 0): ?>
                            <?php while ($seminar = $seminars_result->fetch_assoc()): ?>
                                <li><?php echo htmlspecialchars($seminar['title']); ?></li>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <li>No seminars listed.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <div class="col-md-8">
                <div class="section">
                    <h3 class="section-title">PROFILE</h3>
                    <p><?php echo $about_content ? htmlspecialchars($about_content['content']) : 'No profile content.'; ?></p>
                </div>

                <div class="section">
                    <h3 class="section-title">PROJECTS</h3>
                    <?php if ($projects_result && $projects_result->num_rows > 0): ?>
                        <?php while ($project = $projects_result->fetch_assoc()): ?>
                            <div class="mb-3">
                                <h5><?php echo htmlspecialchars($project['title']); ?></h5>
                                <p><?php echo htmlspecialchars($project['languages_used']); ?></p>
                                <p>🔗 <a href="<?php echo htmlspecialchars($project['github_link']); ?>" target="_blank">GitHub</a></p>
                                <p><?php echo htmlspecialchars($project['description']); ?></p>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No projects listed.</p>
                    <?php endif; ?>
                </div>

                <div class="section">
                    <h3 class="section-title">EDUCATION</h3>
                    <ul>
                        <?php if ($education_result && $education_result->num_rows > 0): ?>
                            <?php while ($edu = $education_result->fetch_assoc()): ?>
                                <li><?php echo htmlspecialchars($edu['institution']) . " | " . htmlspecialchars($edu['degree']) . " | " . htmlspecialchars($edu['year']); ?></li>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <li>No education listed.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
include('admin.php');
$subjectId = $_GET['subject_id'] ?? '';
$editQuizId = $_GET['edit_quiz_id'] ?? '';
$addQuiz = isset($_GET['add_quiz']) ? true : false;
$editQuestions = isset($_GET['edit_questions']) ? true : false; // Flag to edit questions
$addQuestion = isset($_GET['add_question']) ? true : false; // Flag to add question
$quizId = $_GET['quiz_id'] ?? ''; // Quiz ID for editing questions
$editQuestionId = $_GET['edit_question_id'] ?? ''; // Question ID for editing question

// Add/Update quiz logic
if (isset($_POST['add'])) {
    $quizName = $_POST['quiz_name'];
    $quizDesc = $_POST['quiz_desc'];
    $answer_limit = $_POST['wrong_answer_limit'];
    $sql_insert = "INSERT INTO quizzes (quiz_name, description, subject_id, wrong_answer_limit) VALUES ('$quizName', '$quizDesc', '$subjectId', '$answer_limit')";
    mysqli_query($conn, $sql_insert);
    header("Location: {$_SERVER['PHP_SELF']}?subject_id=$subjectId&message=quiz_added");
    exit();
}
if (isset($_POST['save'])) {
    $quiz_id = $_POST['quiz_id'];
    $quizName = $_POST['quiz_name'];
    $quizDesc = $_POST['quiz_desc'];
    $answer_limit = $_POST['wrong_answer_limit'];
    $sql_insert = "UPDATE quizzes SET quiz_name='$quizName', description='$quizDesc', subject_id='$subjectId', wrong_answer_limit='$answer_limit' WHERE id='$quiz_id'";
    mysqli_query($conn, $sql_insert);
    header("Location: {$_SERVER['PHP_SELF']}?subject_id=$subjectId");
    exit();
}

// Delete question logic

if (isset($_POST['delete_question'])) {
    $questionId = $_POST['question_id'];

    // First, delete the answers associated with the question
    $sql_delete_answers = "DELETE FROM answers WHERE question_id='$questionId'";
    mysqli_query($conn, $sql_delete_answers);

    // Then, delete the question itself
    $sql_delete_question = "DELETE FROM questions WHERE id='$questionId'";
    mysqli_query($conn, $sql_delete_question);

    // Redirect after deletion
    header("Location: {$_SERVER['PHP_SELF']}?subject_id=$subjectId&edit_questions=1&quiz_id=$quizId");
    exit();
}

// Delete quiz logic

if (isset($_POST['delete'])) {
    $deleteId = $_POST['quiz_id'];

    // First, delete the answers associated with the questions in the quiz
    $sql_delete_answers = "DELETE FROM answers WHERE question_id IN (SELECT id FROM questions WHERE quiz_id='$deleteId')";
    mysqli_query($conn, $sql_delete_answers);

    // Then, delete the questions in the quiz
    $sql_delete_questions = "DELETE FROM questions WHERE quiz_id='$deleteId'";
    mysqli_query($conn, $sql_delete_questions);

    // Finally, delete the results related to the quiz
    $sql_delete_results = "DELETE FROM results WHERE quiz_id='$deleteId'";
    mysqli_query($conn, $sql_delete_results);

    // Now, delete the quiz itself
    $sql_delete_quiz = "DELETE FROM quizzes WHERE id='$deleteId'";
    mysqli_query($conn, $sql_delete_quiz);

    // Redirect after deletion
    header("Location: {$_SERVER['PHP_SELF']}?subject_id=$subjectId");
    exit();
}


// Add question logic
if (isset($_POST['save_question'])) {
    $quizId = $_POST['quiz_id'];
    $questionText = $_POST['question_text'];
    $note = $_POST['note'];
    $sql_insert_question = "INSERT INTO questions (quiz_id, question_text, note) VALUES ('$quizId', '$questionText', '$note')";
    mysqli_query($conn, $sql_insert_question);
    $questionId = mysqli_insert_id($conn);
    $answers = $_POST['answer_text'];
    $correctAnswer = $_POST['correct_answer'];

    foreach ($answers as $index => $answer) {
        $isCorrect = ($index == $correctAnswer) ? 1 : 0;
        $sql_insert_answer = "INSERT INTO answers (question_id, answer_text, is_correct) VALUES ('$questionId', '$answer', '$isCorrect')";
        mysqli_query($conn, $sql_insert_answer);
    }
    header("Location: {$_SERVER['PHP_SELF']}?subject_id=$subjectId&edit_questions=1&quiz_id=$quizId");
    exit();
}

// Edit question logic
if (isset($_POST['edit_question'])) {
    $questionId = $_POST['question_id'];
    $questionText = $_POST['question_text'];
    $note = $_POST['note'];
    $sql_update_question = "UPDATE questions SET question_text='$questionText', note='$note' WHERE id='$questionId'";
    mysqli_query($conn, $sql_update_question);

    // Update answers
    $answers = $_POST['answer_text'];
    $correctAnswer = $_POST['correct_answer'];
    foreach ($answers as $index => $answer) {
        $isCorrect = ($index == $correctAnswer) ? 1 : 0;
        $sql_update_answer = "UPDATE answers SET answer_text='$answer', is_correct='$isCorrect' WHERE question_id='$questionId' AND answer_text='$answer'";
        mysqli_query($conn, $sql_update_answer);
    }

    header("Location: {$_SERVER['PHP_SELF']}?subject_id=$subjectId&edit_questions=1&quiz_id=$quizId");
    exit();
}

// Fetch subject info
$sql_fetch = "SELECT * FROM subjects WHERE id='$subjectId'";
$result = mysqli_query($conn, $sql_fetch);
$row = mysqli_fetch_assoc($result);
$subject_name = $row['subject_name'];
$description = $row['description'];

if (isset($_POST['edit_subject'])) {
    $newSubjectName = $_POST['subject_name'];
    $newDescription = $_POST['description'];
    $sql_update_subject = "UPDATE subjects SET subject_name='$newSubjectName', description='$newDescription' WHERE id='$subjectId'";
    mysqli_query($conn, $sql_update_subject);
    header("Location: {$_SERVER['PHP_SELF']}?subject_id=$subjectId");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script>
    function confirmDelete(form) {
        if (confirm('Are you absolutely sure you want to delete this quiz? This cannot be undone!')) {
            return true;
        } else {
            return false;
        }
    }
    </script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Subject Quizzes</title>
  <link rel="stylesheet" href="css/admin-subject.css">

</head>
    <body>
        <nav class="navbar">
            <a href="admin_home.php"><img src="media/logo.jpg" alt="Logo" aria-label="Website Logo"></a>
            <h1>Dungeon Knowledge - Admin Site</h1>
            <li><a href="logout.php">Log out</a></li>
        </nav>
        <?php 
        if (isset($_GET['edit_subject']) && $_GET['edit_subject'] == 1) {
            echo "
            <div class='quiz-section'>
                <form method='post' action='{$_SERVER['PHP_SELF']}?subject_id=$subjectId'>
                    <label for='subject_name'>Subject Name:</label><br>
                    <input type='text' name='subject_name' value='" . htmlspecialchars($subject_name) . "' required><br><br>
                    <label for='description'>Description:</label><br>
                    <textarea name='description' rows='4' cols='50' required>" . htmlspecialchars($description) . "</textarea><br><br>
                    <button type='submit' name='edit_subject' class='edit-button'>Save</button>
                    <a href='{$_SERVER['PHP_SELF']}?subject_id=$subjectId'>
                        <button type='button' class='cancel-button'>Cancel</button>
                    </a>
                </form>
            </div>";
        } else {
            echo "<h1>$subject_name</h1> <br> <h3>$description</h3>
            <div class='button-group'>
                <a href='{$_SERVER['PHP_SELF']}?subject_id=$subjectId&edit_subject=1'>
                    <button class='edit-button'>Edit Subject</button>
                </a>";
        }

        if (isset($_GET['message']) && $_GET['message'] == 'quiz_added') {
            echo "<div class='confirmation-message'>Quiz has been successfully added!</div>";
        }
        ?>

        <!-- Add Quiz Button -->
            <a href="<?php echo $_SERVER['PHP_SELF'] . '?subject_id=' . $subjectId . '&add_quiz=1'; ?>">
                <button class="edit-button">Add Quiz</button>
            </a></div>

        <br><h3>Quizzes:</h3>

        <?php
        $sql_fetch = "SELECT * FROM quizzes WHERE subject_id='$subjectId' ORDER BY quiz_name ASC";
        $result = mysqli_query($conn, $sql_fetch);

        if (mysqli_num_rows($result) > 0) {
            if ($addQuiz) {
                echo "
                <div class='quiz-section'>
                    <form action='{$_SERVER['PHP_SELF']}?subject_id=$subjectId' method='post'>
                        <input type='text' name='quiz_name' placeholder='Quiz Name' required><br><br>
                        <textarea name='quiz_desc' placeholder='Quiz Description' rows='4' cols='50' required></textarea><br><br>
                        Wrong answer limit: (min: 3)<input type='number' name='wrong_answer_limit' required min='3' step='1' value='3'/>
                        <button type='submit' name='add' class='edit-button'>Add Quiz</button>
                        <a href='{$_SERVER['PHP_SELF']}?subject_id=$subjectId'><button type='button' class='cancel-button'>Cancel</button></a>
                    </form>
                </div>";
            }

            while ($row = mysqli_fetch_assoc($result)) {
                $quiz_name = htmlspecialchars($row['quiz_name']);
                $quiz_id = htmlspecialchars($row['id']);
                $quiz_desc = htmlspecialchars($row['description']);
                $wrong_answer_limit= htmlspecialchars($row['wrong_answer_limit']);

                echo "<div class='quiz-section'>";

                if ($editQuizId == $quiz_id) {
                    echo "
                    <form action='{$_SERVER['PHP_SELF']}?subject_id=$subjectId' method='post'>
                        <input type='hidden' name='quiz_id' value='$quiz_id'>
                        <input type='text' name='quiz_name' value='$quiz_name' required><br><br>
                        <textarea name='quiz_desc' rows='4' cols='50' required>$quiz_desc</textarea><br><br>
                        Wrong answer limit: (min: 3)<input type='number' name='wrong_answer_limit' required min='3' step='1' value='$wrong_answer_limit'/>
                        <button type='submit' name='save' class='edit-button'>Save</button>
                        <a href='{$_SERVER['PHP_SELF']}?subject_id=$subjectId'><button type='button' class='cancel-button'>Cancel</button></a>
                    </form>";
                } else {
                    echo "
                    <div class='quiz-details'>
                        <div>
                            <h3>$quiz_name</h3>
                            <p>$quiz_desc</p>
                        </div>
                        <div class='button-group'>
                            <a href='{$_SERVER['PHP_SELF']}?subject_id=$subjectId&edit_quiz_id=$quiz_id'>
                                <button class='edit-button'>Edit Quiz</button>
                            </a>
                            <a href='{$_SERVER['PHP_SELF']}?subject_id=$subjectId&edit_questions=1&quiz_id=$quiz_id'>
                                <button class='edit-questions-button'>Edit Questions</button>
                            </a>
                            <a href='{$_SERVER['PHP_SELF']}?subject_id=$subjectId&add_question=1&quiz_id=$quiz_id'>
                                <button class='add-question-button'>Add Question</button>
                            </a>
                            <a href='leaderboard.php?quiz_id=$quiz_id&subject_id=$subjectId'>
                                <button class='add-question-button'>View Leaderboard</button>
                            </a>
                            <form action='{$_SERVER['PHP_SELF']}?subject_id=$subjectId' method='post' style='display:inline;' onsubmit='return confirmDelete(this);'>
                                <input type='hidden' name='quiz_id' value='$quiz_id'>
                                <button type='submit' name='delete' class='delete-button'>Delete</button>
                            </form>
                        </div>
                    </div>";
                }

                // Add Question Form under the selected quiz
                if ($addQuestion && $quiz_id == $_GET['quiz_id']) {
                    echo "
                    <div class='question-section'>
                        <h3>Add New Question</h3>
                        <form action='{$_SERVER['PHP_SELF']}?subject_id=$subjectId' method='post'>
                            <input type='hidden' name='quiz_id' value='$quiz_id'>
                            <label for='question_text'>Question Text</label><br>
                            <input type='text' name='question_text' required><br><br>
                            <label for='note'>Note (optional)</label><br>
                            <textarea name='note' rows='4' cols='50'></textarea><br><br>

                            <h4>Answers:</h4>
                            <div class='answers-section'>
                                <label for='answer_1'>Answer 1:</label><br>
                                <input type='text' name='answer_text[0]' required><br><br>
                                <label for='answer_2'>Answer 2:</label><br>
                                <input type='text' name='answer_text[1]' required><br><br>
                                <label for='answer_3'>Answer 3:</label><br>
                                <input type='text' name='answer_text[2]' required><br><br>
                                <label for='answer_4'>Answer 4:</label><br>
                                <input type='text' name='answer_text[3]' required><br><br>

                                <label for='correct_answer'>Correct Answer:</label><br>
                                <select name='correct_answer'>
                                    <option value='0'>Answer 1</option>
                                    <option value='1'>Answer 2</option>
                                    <option value='2'>Answer 3</option>
                                    <option value='3'>Answer 4</option>
                                </select><br><br>

                                <button type='submit' name='save_question' class='edit-button'>Save Question</button>
                                <a href='{$_SERVER['PHP_SELF']}?subject_id=$subjectId&edit_questions=1&quiz_id=$quiz_id'>
                                    <button type='button' class='cancel-button'>Cancel</button>
                                </a>
                            </div>
                        </form>
                    </div>";
                }

                // Display questions for the selected quiz if editing questions
                if ($editQuestions && $quiz_id == $_GET['quiz_id']) {
                    $sql_fetch_questions = "SELECT * FROM questions WHERE quiz_id='$quiz_id'";
                    $questions_result = mysqli_query($conn, $sql_fetch_questions);
                    if (mysqli_num_rows($questions_result) > 0) {
                        echo "<h3>Questions for this quiz:</h3><a href='{$_SERVER['PHP_SELF']}?subject_id=$subjectId'><button type='button' class='cancel-button' style='justify-content: flex-end;'>Cancel</button></a>";;
                        $count=0;
                        while ($question = mysqli_fetch_assoc($questions_result)) {
                            $count++;
                            $questionId = htmlspecialchars($question['id']);
                            $questionText = htmlspecialchars($question['question_text']);
                            $note = htmlspecialchars($question['note']);

                            echo "<p>$count. $questionText 
                                <a href='{$_SERVER['PHP_SELF']}?subject_id=$subjectId&edit_questions=1&quiz_id=$quiz_id&edit_question_id=$questionId'>
                                    <button class='edit-button'>Edit</button></a>
                                <form action='{$_SERVER['PHP_SELF']}?subject_id=$subjectId' method='post' style='display:inline;' onsubmit='return confirmDelete(this);'>
                                    <input type='hidden' name='question_id' value='$questionId'>
                                    <button type='submit' name='delete_question' class='delete-button'>Delete</button>
                                </form>
                            </p>";

                            // If editing the question
                            if ($editQuestionId == $questionId) {
                                echo "
                                <div class='question-section'>
                                    <h3>Edit Question</h3>
                                    <form action='{$_SERVER['PHP_SELF']}?subject_id=$subjectId' method='post'>
                                        <input type='hidden' name='question_id' value='$questionId'>
                                        <label for='question_text'>Question Text</label><br>
                                        <input type='text' name='question_text' value='$questionText' required><br><br>
                                        <label for='note'>Note (optional)</label><br>
                                        <textarea name='note' rows='4' cols='50'>$note</textarea><br><br>
                                        <h4>Answers:</h4>
                                        <div class='answers-section'>";

                                // Display answers for the question
                                $sql_fetch_answers = "SELECT * FROM answers WHERE question_id='$questionId'";
                                $answers_result = mysqli_query($conn, $sql_fetch_answers);
                                $answerIndex = 0;
                                $correctAnswerIndex = -1; // This will store the correct answer's index

                                // Loop through answers to determine which one is correct
                                while ($answer = mysqli_fetch_assoc($answers_result)) {
                                    $answerText = htmlspecialchars($answer['answer_text']);
                                    $isCorrect = $answer['is_correct'];

                                    // Check if this answer is correct
                                    if ($isCorrect) {
                                        $correctAnswerIndex = $answerIndex; // Store the correct answer's index
                                    }

                                    echo "
                                    <label for='answer_$answerIndex'>Answer " . ($answerIndex + 1) . ":</label><br>
                                    <input type='text' name='answer_text[]' value='$answerText' required><br><br>";

                                    $answerIndex++;
                                }

                                echo "
                                <label for='correct_answer'>Correct Answer:</label><br>
                                <select name='correct_answer'>";

                                // Loop through the answers and preselect the correct one
                                for ($i = 0; $i < 4; $i++) {
                                    $selected = ($i == $correctAnswerIndex) ? 'selected' : '';
                                    echo "<option value='$i' $selected>Answer " . ($i + 1) . "</option>";
                                }

                                echo "</select><br><br>";

                                echo"<button type='submit' name='edit_question' class='edit-button'>Save Changes</button>
                                <a href='{$_SERVER['PHP_SELF']}?subject_id=$subjectId&edit_questions=1&quiz_id=$quiz_id'>
                                    <button type='button' class='cancel-button'>Cancel</button>
                                </a>
                            </div>
                        </form>
                    </div>";
                            }
                        }
                    }else{
                        echo"No questions for this quiz";
                    }
                }


                echo "</div>"; // .quiz-section
            }
        } else {
            echo "<p>No quizzes available.</p>";
            if ($addQuiz) {
                echo "
                <div class='quiz-section'>
                    <form action='{$_SERVER['PHP_SELF']}?subject_id=$subjectId' method='post'>
                        <input type='text' name='quiz_name' placeholder='Quiz Name' required><br><br>
                        <textarea name='quiz_desc' placeholder='Quiz Description' rows='4' cols='50' required></textarea><br><br>
                        <button type='submit' name='add' class='edit-button'>Add Quiz</button>
                        <a href='{$_SERVER['PHP_SELF']}?subject_id=$subjectId'><button type='button' class='cancel-button'>Cancel</button></a>
                    </form>
                </div>";
            }
        }
        ?>

    </body>
</html>
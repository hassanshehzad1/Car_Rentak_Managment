<?php
include('../php/conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'create':
            addFeedback($conn);
            break;
        case 'read':
            readFeedback($conn);
            break;
        case 'update':
            updateFeedback($conn);
            break;
        case 'delete':
            deleteFeedback($conn);
            break;
    }
}

//  CREATE FEEDBACK
function addFeedback($conn)
{
    if (isset($_POST['feedbackDetails'])) {
        
        $booking_id = $_POST['booking_id'];
        $ratings = $_POST['ratings'];
        $comments = $_POST['comments'];

        if (empty($booking_id) || empty($ratings)) {
            echo "All fields are required.";
            return;
        }

        if ($ratings < 1 || $ratings > 5) {
            echo "Ratings must be greater than 1 and less than 5";
            return;
        }

        $sql = "INSERT INTO Feedback ( BookingID, Ratings, Comments) VALUES ( ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis",  $booking_id, $ratings, $comments);

        if ($stmt->execute()) {
            echo "Feedback Successfully Added!";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

//  READ FEEDBACK
function readFeedback($conn)
{
    $sql = "SELECT * FROM Feedback";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo $row;
        }
    } else {
        echo "No Feedback Found!";
    }
}

//  UPDATE FEEDBACK
function updateFeedback($conn)
{
    if (isset($_POST['feedback_id'])) {
        $feedback_id = $_POST['feedback_id'];
        $ratings = $_POST['ratings'];
        $comments = $_POST['comments'];

        if (empty($feedback_id) || empty($ratings)) {
            echo "FeedBack ID and ratings are required to update feedback";
            return;
        }

        if ($ratings < 1 || $ratings > 5) {
            echo "Ratings is always greather than 1 less than 5.";
            return;
        }

        $sql = "UPDATE Feedback SET Ratings=?, Comments=? WHERE Feedback_ID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isi", $ratings, $comments, $feedback_id);

        if ($stmt->execute()) {
            echo "Feedback Updated Successfully!";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

//  DELETE FEEDBACK
function deleteFeedback($conn)
{
    if (isset($_POST['feedback_id'])) {
        $feedback_id = $_POST['feedback_id'];

        $sql = "DELETE FROM Feedback WHERE Feedback_ID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $feedback_id);

        if ($stmt->execute()) {
            echo "Feedback Deleted Successfully!";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>

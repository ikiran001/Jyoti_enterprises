<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'] ?? '';
    $message = $_POST['message'] ?? '';
    $rating = $_POST['rating'] ?? 5;

    $conn = new mysqli("localhost", "root", "", "jyoti_enterprises");
    if ($conn->connect_error) die("DB error");

    $stmt = $conn->prepare("INSERT INTO testimonials (name, message, rating) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $name, $message, $rating);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo "<script>alert('Thank you! Your testimonial has been submitted for review.'); window.location.href='about.php';</script>";
} else {
    echo "Invalid request";
}
?>

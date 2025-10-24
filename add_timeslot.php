<?php
include "config.php";

if (isset($_POST['add_timeslot'])) {
    $start_time = $_POST['start_time'];
    $end_time   = $_POST['end_time'];

    // PHP validation
    $now       = new DateTime();
    $max_limit = (new DateTime())->modify("+4 months");

    $start = new DateTime($start_time);
    $end   = new DateTime($end_time);

    $open_time  = (clone $start)->setTime(11, 0, 0);
    $close_time = (clone $start)->setTime(23, 59, 59);

    if ($start < $now) {
        echo "<div class='alert alert-danger text-center'>❌ Start time cannot be before today.</div>";
    } elseif ($start > $max_limit) {
        echo "<div class='alert alert-danger text-center'>❌ Start time cannot be more than 4 months ahead.</div>";
    } elseif ($end <= $start) {
        echo "<div class='alert alert-danger text-center'>❌ End time must be after start time.</div>";
    } elseif ($end > $max_limit) {
        echo "<div class='alert alert-danger text-center'>❌ End time cannot be more than 4 months ahead.</div>";
    } elseif ($start < $open_time || $start > $close_time) {
        echo "<div class='alert alert-danger text-center'>❌ Start time must be between 11:00 AM and 12:00 AM.</div>";
    } elseif ($end < $open_time || $end > $close_time) {
        echo "<div class='alert alert-danger text-center'>❌ End time must be between 11:00 AM and 12:00 AM.</div>";
    } else {
        $sql = "INSERT INTO timeslot (start_time, end_time) 
                VALUES ('$start_time', '$end_time')";
        if (mysqli_query($connection, $sql)) {
            header("Location: list_timeslot.php?msg=added");
            exit;
        } else {
            echo "<div class='alert alert-danger text-center'>Error: " . mysqli_error($connection) . "</div>";
        }
    }
}

// Generate date limits for input fields
$today     = date("Y\TH:i");
$max_date  = date("Y-m-d\TH:i", strtotime("+4 months"));
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add Timeslot</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f8f9fa;
    }
    .card {
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.1);
    }
    .btn-primary {
      border-radius: 8px;
      padding: 10px;
      font-weight: 600;
    }
   
 
    h2 {
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="container my-5">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card p-4">
          <h2 class="text-center text-primary mb-4">⏰ Add Timeslot</h2>
          <form method="POST">

            <div class="mb-3">
              <label class="form-label fw-semibold">Start Time</label>
              <input type="datetime-local" name="start_time" 
                     min="<?php echo $today; ?>" max="<?php echo $max_date; ?>" 
                     class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">End Time</label>
              <input type="datetime-local" name="end_time" 
                     min="<?php echo $today; ?>" max="<?php echo $max_date; ?>" 
                     class="form-control" required>
            </div>

            <div class="d-grid">
              <button type="submit" name="add_timeslot" class="btn btn-primary">
                ➕ Add Timeslot
              </button>
            </div>

          </form>

          <!-- Back Button -->
          <div class="text-center">
            <a href="list_timeslot.php" class="btn btn-secondary btn-sm">⬅ Back to Timeslot List</a>
          </div>

        </div>
      </div>
    </div>
  </div>
</body>
</html>

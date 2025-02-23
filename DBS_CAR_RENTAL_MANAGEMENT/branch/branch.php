<?php
include('../php/conn.php');

//  Fetch All Locations & Hours
$locations = [];
$sql = "SELECT L.LocationID, L.City, L.Street, L.Province, L.ContactNumber, 
               H.DayOfWeek, H.OpeningTime, H.ClosingTime 
        FROM Locations L
        LEFT JOIN LocationHours H ON L.LocationID = H.LocationID
        ORDER BY L.LocationID, FIELD(H.DayOfWeek, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $locations[$row['LocationID']]['City'] = $row['City'];
        $locations[$row['LocationID']]['Street'] = $row['Street'];
        $locations[$row['LocationID']]['Province'] = $row['Province'];
        $locations[$row['LocationID']]['ContactNumber'] = $row['ContactNumber'];
        $locations[$row['LocationID']]['Hours'][] = [
            'Day' => $row['DayOfWeek'],
            'OpeningTime' => $row['OpeningTime'],
            'ClosingTime' => $row['ClosingTime']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Locations - Car Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function editLocation(id, city, street, province, contact) {
            //  Fill Form Fields with Existing Data
            document.getElementById('edit_location_id').value = id;
            document.getElementById('edit_city').value = city;
            document.getElementById('edit_street').value = street;
            document.getElementById('edit_province').value = province;
            document.getElementById('edit_contact').value = contact;

            //  Show Edit Form
            document.getElementById('editFormContainer').style.display = 'block';
        }

        function hideEditForm() {
            document.getElementById('editFormContainer').style.display = 'none';
        }
    </script>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center"> Manage Locations</h2>

    <!--  Show All Locations with Update/Delete -->
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Location ID</th>
                <th>City</th>
                <th>Street</th>
                <th>Province</th>
                <th>Contact Number</th>
                <th>Operating Hours</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($locations)) : ?>
            <?php foreach ($locations as $id => $location) : ?>
                <tr>
                    <td><?= $id ?></td>
                    <td id="city_<?= $id ?>"><?= $location['City'] ?></td>
                    <td id="street_<?= $id ?>"><?= $location['Street'] ?></td>
                    <td id="province_<?= $id ?>"><?= $location['Province'] ?></td>
                    <td id="contact_<?= $id ?>"><?= $location['ContactNumber'] ?></td>
                    <td>
                        <?php foreach ($location['Hours'] as $hour) : ?>
                            <?= $hour['Day'] ?>: <?= date("h:i A", strtotime($hour['OpeningTime'])) ?> - <?= date("h:i A", strtotime($hour['ClosingTime'])) ?><br>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <!--  Update Button -->
                        <button type="button" class="btn btn-warning" 
                            onclick="editLocation(<?= $id ?>, '<?= $location['City'] ?>', '<?= $location['Street'] ?>', '<?= $location['Province'] ?>', '<?= $location['ContactNumber'] ?>')">
                            Update
                        </button>

                        <!--  DELETE FORM -->
                        <form action="../php/locations.php" method="post" class="d-inline">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="location_id" value="<?= $id ?>">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr><td colspan="7" class="text-center"> No Locations Found!</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <!--  Hidden Update Form -->
    <div id="editFormContainer" style="display: none;">
        <h4>✏️ Edit Location</h4>
        <form action="../php/locations.php" method="post" id="editLocationForm">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="location_id" id="edit_location_id">

            <div class="mb-3">
                <label class="form-label">City:</label>
                <input type="text" name="city" id="edit_city" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Street:</label>
                <input type="text" name="street" id="edit_street" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Province:</label>
                <input type="text" name="province" id="edit_province" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Contact Number:</label>
                <input type="text" name="contact_number" id="edit_contact" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Save Changes</button>
            <button type="button" class="btn btn-secondary" onclick="hideEditForm()">Cancel</button>
        </form>
    </div>

    <!--  Add New Location Form -->
    <h4 class="mt-5">➕ Add New Location</h4>
    <form action="../php/locations.php" method="post" class="mb-4">
        <input type="hidden" name="action" value="create">

        <div class="mb-3">
            <label class="form-label">City:</label>
            <input type="text" name="city" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Street:</label>
            <input type="text" name="street" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Province:</label>
            <input type="text" name="province" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Contact Number:</label>
            <input type="text" name="contact_number" class="form-control" required>
        </div>

        <button type="submit" name="locationDetails" class="btn btn-success">Add Location</button>
    </form>
</div>
</body>
</html>

<?php 
include("./php/connectSQL.php");

/** 1. Parking Slot Data **/
$sql = "SELECT SlotID, CONCAT(Area, SlotCode) AS SlotName, Status, CurrentRFID FROM parkingslot ORDER BY SlotCode ASC";
$result = mysqli_query($conn, $sql);
$parkingSlots = mysqli_fetch_all($result, MYSQLI_ASSOC);

/** 2. Parking History + RFID Card + Owner Info **/
$sql = "SELECT ph.*, CONCAT(ps.Area, ps.SlotCode) AS SlotName, rc.OwnerName, rc.VehiclePlate, rc.Type
        FROM parkinghistory ph
        JOIN parkingslot ps ON ph.SlotID = ps.SlotID
        JOIN rfidcard rc ON ph.RFID = rc.RFID
        ORDER BY ph.TimeIn DESC";
$result = mysqli_query($conn, $sql);
$parkingHistory = mysqli_fetch_all($result, MYSQLI_ASSOC);

/** 3. RFID Card List **/
$sql = "SELECT * FROM rfidcard ORDER BY RFID ASC";
$result = mysqli_query($conn, $sql);
$rfidCards = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!-- Parking History Table -->
<h3 style="color:white;">Parking History</h3>
<div class="table-responsive">
    <table class="table table-bordered table-striped table-dark">
        <thead>
            <tr>
                <th>Slot</th>
                <th>RFID</th>
                <th>Owner</th>
                <th>Vehicle Plate</th>
                <th>Type</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Duration (min)</th>
                <th>Fee</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($parkingHistory as $history): ?>
                <tr>
                    <td><?= htmlspecialchars($history['SlotName']) ?></td>
                    <td><?= htmlspecialchars($history['RFID']) ?></td>
                    <td><?= htmlspecialchars($history['OwnerName']) ?></td>
                    <td><?= htmlspecialchars($history['VehiclePlate']) ?></td>
                    <td><?= htmlspecialchars($history['Type']) ?></td>
                    <td><?= htmlspecialchars($history['TimeIn']) ?></td>
                    <td><?= htmlspecialchars($history['TimeOut']) ?></td>
                    <td><?= htmlspecialchars($history['Duration']) ?></td>
                    <td><?= htmlspecialchars($history['Fee']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Parking Slots Table -->
<h3 style="color:white;">Parking Slots</h3>
<div class="table-responsive">
    <table class="table table-bordered table-striped table-dark" id="parkingSlotsTable">
        <thead>
            <tr>
                <th>Slot</th>
                <th>Status</th>
                <th>Current RFID</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($parkingSlots as $index => $slot): ?>
                <tr class="<?= $index >= 4 ? 'hidden-row' : '' ?>">
                    <td><?= htmlspecialchars($slot['SlotName']) ?></td>
                    <td><?= $slot['Status'] ? 'Occupied' : 'Available' ?></td>
                    <td><?= htmlspecialchars($slot['CurrentRFID']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (count($parkingSlots) > 4): ?>
        <button id="seeMoreBtn" class="btn btn-primary">See More</button>
    <?php endif; ?>
</div>

<style>
.hidden-row {
    display: none;
}
</style>

<script>
document.getElementById('seeMoreBtn')?.addEventListener('click', function() {
    document.querySelectorAll('#parkingSlotsTable .hidden-row').forEach(row => {
        row.style.display = 'table-row';
    });
    this.style.display = 'none';
});
</script>



<!-- RFID Cards Table -->
<h3 style="color:white;">RFID Cards</h3>
<div class="table-responsive">
    <table class="table table-bordered table-striped table-dark">
        <thead>
            <tr>
                <th>RFID</th>
                <th>Owner</th>
                <th>Vehicle Plate</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rfidCards as $card): ?>
                <tr>
                    <td><?= htmlspecialchars($card['RFID']) ?></td>
                    <td><?= htmlspecialchars($card['OwnerName']) ?></td>
                    <td><?= htmlspecialchars($card['VehiclePlate']) ?></td>
                    <td><?= htmlspecialchars($card['Type']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
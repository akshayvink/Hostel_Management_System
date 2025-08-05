<?php
session_start();
if (isset($_SESSION['hosteler_id'])) {
    echo "Session exists: " . $_SESSION['hosteler_id'];
} else {
    echo "Session NOT set.";
}
?>

<?php
session_start();
if (isset($_SESSION['enrollError'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['enrollError'] . '</div>';
    unset($_SESSION['enrollError']);
}

if (isset($_SESSION['enrollSuccess'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['enrollSuccess'] . '</div>';
    unset($_SESSION['enrollSuccess']);
}
echo 'hi';
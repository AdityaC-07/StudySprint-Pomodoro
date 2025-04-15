<?php
// Start the session if it hasn't been started already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set a default theme if none is set in the session
if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'dark'; // Default theme
}

// Retrieve the current theme from the session
$theme = $_SESSION['theme'];

// Define theme-specific variables
if ($theme === 'dark') {
    $bodyBg = "#1e1e1e"; // Dark background
    $textColor = "#ffffff"; // Light text color
    $cardBg = "#2d2d2d"; // Dark card background
    $accent = "#4CAF50"; // Green accent color
    $hoverAccent = "#388E3C"; // Darker green for hover
} elseif ($theme === 'green') {
    $bodyBg = "#ffffff"; // White background
    $textColor = "#333333"; // Dark text color
    $cardBg = "#f5f5f5"; // Light gray card background
    $accent = "#2e8b57"; // Sea Green accent color
    $hoverAccent = "#1d6b41"; // Darker sea green for hover
} elseif ($theme === 'light') {
    $bodyBg = "#ffffff"; // Light background
    $textColor = "#333333"; // Dark text color
    $cardBg = "#fff7e6"; // Light card background
    $accent = "#c49700"; // Gold accent color
    $hoverAccent = "#a07800"; // Darker gold for hover
} elseif ($theme === 'blue') {
    $bodyBg = "#e3f2fd"; // Light blue background
    $textColor = "#0d47a1"; // Dark blue text color
    $cardBg = "#bbdefb"; // Blue card background
    $accent = "#1976d2"; // Blue accent color
    $hoverAccent = "#0d47a1"; // Darker blue for hover
} elseif ($theme === 'solarized') {
    $bodyBg = "#fdf6e3"; // Solarized light background
    $textColor = "#657b83"; // Solarized text color
    $cardBg = "#eee8d5"; // Solarized card background
    $accent = "#b58900"; // Solarized yellow accent
    $hoverAccent = "#cb4b16"; // Solarized orange for hover
} else {
    // Fallback to dark theme if an unknown theme is set
    $bodyBg = "#1e1e1e";
    $textColor = "#ffffff";
    $cardBg = "#2d2d2d";
    $accent = "#4CAF50";
    $hoverAccent = "#388E3C";
}
?>
<?php
function image_fix_orientation($filename)
{
    $exif = exif_read_data($filename);
    if (!empty($exif["Orientation"])) {
        $image = imagecreatefromjpeg($filename);
        switch ($exif["Orientation"]) {
            case 3:
                $image = imagerotate($image, 180, 0);
                break;

            case 6:
                $image = imagerotate($image, -90, 0);
                break;

            case 8:
                $image = imagerotate($image, 90, 0);
                break;
        }
        imagejpeg($image, "modified-" . $filename, 90);
    }
}
echo "<h1>Script orientation image correction</h1>";
if (isset($_GET["image"])) {
    $filename = $_GET["image"];
    if (file_exists($filename)) {
        echo "<h2>Image Initial Info</h2>";
        echo "<hr>";
        echo "<image src='" . $filename . "'>";
        echo "<ul>";
        $exif = exif_read_data($filename, 0, true);
        foreach ($exif as $key => $section) {
            foreach ($section as $name => $value) {
                echo "<li>$key.$name: $value</li>";
            }
        }
        echo "</ul>";
        image_fix_orientation($filename);
        echo "<h2>Image Changed Info</h2>";
        echo "<hr>";
        echo "<image src='" . "modified-" . $filename . "'>";
        echo "<ul>";
        $exif = exif_read_data("modified-" . $filename, 0, true);
        foreach ($exif as $key => $section) {
            foreach ($section as $name => $value) {
                echo "<li>$key.$name: $value</li>";
            }
        }
        echo "</ul>";
    } else {
        echo "<p>The file does not exist!</p>";
    }
} else {
    echo "<p>Please give me the name of the image such as ?image=nameimage.jpg</p>";
}
?>

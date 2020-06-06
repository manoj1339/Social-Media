<?php
function resize($newWidth, $newHeight, $targetFile, $originalFile) {

    $info = getimagesize($originalFile);
    $mime = $info['mime'];

    switch ($mime) {
            case 'image/jpeg':
                    $image_create_func = 'imagecreatefromjpeg';
                    $image_save_func = 'imagejpeg';
                    $new_image_ext = 'jpg';
                    break;

            case 'image/png':
                    $image_create_func = 'imagecreatefrompng';
                    $image_save_func = 'imagepng';
                    $new_image_ext = 'png';
                    break;

            case 'image/gif':
                    $image_create_func = 'imagecreatefromgif';
                    $image_save_func = 'imagegif';
                    $new_image_ext = 'gif';
                    break;

            default:
                    throw new Exception('Unknown image type.');
    }
    $img = $image_create_func($originalFile);
        list($width, $height) = getimagesize($originalFile);

        $tmp = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        if (file_exists($targetFile))
        {
           unlink($targetFile);
        }
        $image_save_func($tmp, "$targetFile");
}

//echo resize(500, 350, 'postImages/dimension.jpg', 'postImages/postimage5bd451ae24b7c.jpg');
?>

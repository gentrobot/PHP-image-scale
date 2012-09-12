PHP-image-scale
===============

A simple PHP class for scaling images, keeping the aspect ratio intact.

Problem: Scale images of varying dimensions to fit within a pre-defined frame with fixed dimensions.

Uses:

1. Include the class using PHP include.

<?php include('ImageScale.class.php'); ?>

2. Create an object of the class and pass the name of the image file, width and height of the frame to fit in the scaled image.

<?php $scaleObject = new ImageScale('image file to scale',width of the frame,height of the frame);?>

3. Call the scale image method.

<?php $scaleObject->scaleImage();?>

4. Save image at the desired location in the desired file format with the desired output image quality.

<?php $scaleObject->save('name of the final image file to save',desired image quality from 0 to 100);

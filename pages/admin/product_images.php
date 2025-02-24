<div class="col-12 col-sm-4">
    <div class="custom-image">
        <img src="<?php if(!empty($productDetails['p_images'][$x-1])) echo htmlspecialchars($productDetails['p_images'][$x-1]); else echo 'assets/img/cat/dumy.png'; ?>" alt="Image Preview" class="image-preview" id="preview-image-<?php echo $x; ?>" />
        <input type="hidden" name="image_src-<?php echo $x; ?>" id="image_src-<?php echo $x; ?>" value="<?php if(!empty($productDetails['p_images'][$x-1])) echo htmlspecialchars($productDetails['p_images'][$x-1]); else echo 'assets/img/cat/dumy.png'; ?>"> 
        <button class="remove-image" type="button" id="remove-image-btn-<?php echo $x; ?>" style="<?php if(!empty($productDetails['p_images'][$x-1])) echo 'display:block;'; else echo 'display:none;'; ?>">✖</button>
        <input type="file" class="custom-file-input" id="image-input-<?php echo $x; ?>" name="image-<?php echo $x; ?>" accept=".jpg, .jpeg, .png, .gif" style="display:none;">
    </div>
</div>

<script>

const imageInput_<?php echo $x; ?> = document.getElementById('image-input-<?php echo $x; ?>');
const previewImage_<?php echo $x; ?> = document.getElementById('preview-image-<?php echo $x; ?>');
const removeButton_<?php echo $x; ?> = document.getElementById('remove-image-btn-<?php echo $x; ?>');

// hidden imput image src
const hiddenInput_<?php echo $x; ?> = document.getElementById('image_src-<?php echo $x; ?>');


// Click event to open file input when clicking on the dummy image
previewImage_<?php echo $x; ?>.addEventListener('click', function() {
    imageInput_<?php echo $x; ?>.click(); // Trigger file input click
});

// Change event for file input
imageInput_<?php echo $x; ?>.addEventListener('change', function(event) {
    const file = event.target.files[0];
    
    if (file) {
        const img = new Image();
        img.src = URL.createObjectURL(file);
        
        img.onload = function() {
            const width = img.naturalWidth;
            const height = img.naturalHeight;

            // Check for minimum dimensions
            if (width < 600 || height < 800) {
                alert("Image dimensions must be at least 600x800 pixels.");
            }else if (file.size > 150 * 1024) {
                alert("File size must be less than 150KB.");
            }else {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage_<?php echo $x; ?>.src = e.target.result; // Set the preview image to the selected file
                    hiddenInput_<?php echo $x; ?>.value = previewImage_<?php echo $x; ?>.src;
                    removeButton_<?php echo $x; ?>.style.display = 'block'; // Show the remove button
                };
                reader.readAsDataURL(file); // Read the file as a data URL
            }
        };

        img.onerror = function() {
            alert("There was an error loading the image.");
        };

    }
});

// Click event for the remove button
removeButton_<?php echo $x; ?>.addEventListener('click', function(event) {
    previewImage_<?php echo $x; ?>.src = 'assets/img/cat/dumy.png'; // Reset to dummy image
    removeButton_<?php echo $x; ?>.style.display = 'none'; // Hide the remove button
    imageInput_<?php echo $x; ?>.value = ''; // Clear the file input value
    hiddenInput_<?php echo $x; ?>.value = previewImage_<?php echo $x; ?>.src;
    event.preventDefault();
});

</script>
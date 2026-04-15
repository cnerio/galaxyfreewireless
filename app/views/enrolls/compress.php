<?php require APPROOT . '/views/inc/header.php'; ?>
<?php
$apply = false;
require APPROOT . '/views/inc/navbar.php';
?>
  <style>
    .preview-img {
      max-height: 150px;
      margin-top: 10px;
      border: 1px solid #ccc;
      padding: 5px;
      border-radius: 5px;
    }
    .remove-btn {
      margin-top: 10px;
      display: inline-block;
    }
  </style>


<div class="container  py-5 mt-5">
  <h3 class="mb-3">Upload Required Documents</h3>



  <form id="uploadForm">
    <!-- Proof of Identity -->
    <div class="mb-3">
      <img src="<?php echo $data['imageFile']; ?>" class="img-fluid" width="300" alt="">
      <input type="hidden" name="fileName" id="fileName" value="<?php echo $data['filename']; ?>">
      <input type="hidden" name="fileUrl" id="fileUrl" value="<?php echo $data['imageFile']; ?>">
    </div>

    <button type="submit" id="submitForm" class="btn btn-primary">Submit</button>
    <div id="response" class="mt-3 text-success"></div>
  </form>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
<script>
$("#submitForm").click(function(e){
  e.preventDefault();
  var filename = $("#fileName").val()
  var fileurl = $("#fileUrl").val()
  uploadEndpoint="<?php echo URLROOT;?>/enrolls/saveStoredDoc";
  customerId="<?php echo $data['customer_id'];?>"
  console.log(fileurl)
  compressImageFromUrlAndUpload(fileurl, uploadEndpoint,filename,customerId)
})

function compressImageFromUrlAndUpload(imageUrl, uploadEndpoint,filename,customerId) {

    const img = new Image();
    img.crossOrigin = 'Anonymous'; // Should be safe on same domain
    img.src = imageUrl;

    img.onload = function () {
        const canvas = document.createElement('canvas');
        const MAX_WIDTH = 800;
        const scale = MAX_WIDTH / img.width;

        canvas.width = MAX_WIDTH;
        canvas.height = img.height * scale;

        const ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

        const compressedBase64 = canvas.toDataURL('image/jpeg', 0.7); // Compress

        // Now upload the base64 string to the server
        fetch(uploadEndpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                filename: filename,
                customer_id: customerId,
                base64data: compressedBase64
                
            })
        })
        .then(res => res.json())
        .then(data => {
            console.log('Server response:', data);
            alert('Image saved!');
        })
        .catch(err => {
            console.error('Upload failed:', err);
        });
    };

    img.onerror = function () {
        console.error('Failed to load image:', imageUrl);
    };
}

</script>

</body>
</html>

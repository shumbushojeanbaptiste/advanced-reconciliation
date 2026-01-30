<form method="POST" id="upload_csv_data" enctype="multipart/form-data">
    <input type="hidden" name="action" value="save_bank_file">
    <input type="hidden" name="code" value="<?= $code ?>">
    <input type="hidden" name="account_id" value="<?= $account_id ?>">
    <input type="hidden" name="from_date" value="<?= $from_date ?>">
    <input type="hidden" name="to_date" value="<?= $to_date ?>">

    <h6 class="d-flex justify-content-between align-items-center">
        Attach CSV file
        <a href="../csv/bank_transaction_format.csv"
           class="btn btn-outline-secondary btn-sm"
           download>
            <span class="fe fe-download"></span> Download CSV format
        </a>
    </h6>

    <div class="form-group mt-2">
        <input type="file"
               name="csv_file"
               class="form-control"
               accept=".csv"
               required>
    </div>

    <button type="submit" class="btn btn-primary mt-2 btn-block">
        <span id="indicator_save"></span> Upload
    </button>

    <!-- Log / Feedback area -->
    <div id="upload_log" class="mt-3" style="white-space: pre-wrap;"></div>

    <!-- Progress -->
    <div class="mb-3" id="progressContainer" style="display:none;">
        <div class="progress" style="height:20px;">
            <div id="progressBar"
                 class="progress-bar progress-bar-striped progress-bar-animated"
                 role="progressbar"
                 style="width:0%">0%</div>
        </div>
    </div>
</form>


<script>
document.getElementById('upload_csv_data').addEventListener('submit', function(e) {
    e.preventDefault();

    const log = document.getElementById('upload_log');
    const fileInput = this.querySelector('input[name="csv_file"]');

    if (!fileInput.files.length) {
        log.innerHTML = "<span class='text-danger'>No file selected</span>";
        return;
    }

    const file = fileInput.files[0];

    // Safer CSV validation
    if (!file.name.toLowerCase().endsWith('.csv')) {
        log.innerHTML = "<span class='text-danger'>Only CSV files are allowed</span>";
        return;
    }

    log.innerHTML = "Uploading <b>" + file.name + "</b> ...";

    const formData = new FormData(this);

    $('#indicator_save').html("<img src='../img/ajax_loader.gif' width='15'>");
    $('#progressContainer').show();
    updateProgressBar(10);

    $.ajax({
        url: '../finance/controller/uploadController.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',

        xhr: function () {
            let xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function (evt) {
                if (evt.lengthComputable) {
                    let percent = Math.round((evt.loaded / evt.total) * 100);
                    updateProgressBar(percent);
                }
            }, false);
            return xhr;
        },

        success: function(response){
            if(response.success){
                log.innerHTML = "<span class='text-success'>" + response.message + "</span>";
                updateProgressBar(100);

                setTimeout(() => {
                    $('#upload_csv_data')[0].reset();
                    $('#progressContainer').hide();
                    updateProgressBar(0);
                    proceedToMakeAudit('<?= $account_id ?>','<?= $description ?>','<?= $reco_mode_id ?>','<?= $code ?>');
                }, 3000);

            } else {
                log.innerHTML = "<span class='text-danger'>" + response.message + "</span>";
                updateProgressBar(0);
            }
            $('#indicator_save').html('');
        },

        error: function(){
            log.innerHTML = "<span class='text-danger'>Server error while uploading file</span>";
            $('#indicator_save').html('');
            updateProgressBar(0);
        }
    });
});

// Progress bar helper
function updateProgressBar(percent){
    $('#progressBar')
        .css('width', percent + '%')
        .attr('aria-valuenow', percent)
        .text(percent + '%');
}
</script>

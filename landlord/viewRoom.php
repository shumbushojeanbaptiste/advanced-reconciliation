<!-- ======================= -->
<!-- VIEW ROOM MODAL -->
<!-- ======================= -->
<div class="modal fade modal-right modal-slide" id="viewRoom<?= $fetch_ten['ten_id'] ?>" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="viewRoomTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title"><i class="fe fe-user"></i> <?= htmlspecialchars($fetch_ten['te_names']) ?></h3>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form class="form-horizontal row-fluid" id="add_room" method="post" action="">
        <div class="modal-body">
          <input type="hidden" name="cpid" value="<?= $cpny_ID ?>">

          <div class="row">
            <div class="col-md-12">
              <div style="padding: 20px; border-radius: 8px; border: 2px solid #cb760d; margin:1% 0;">
                
                <div class="row mb-2 font-weight-bold">
                  <div class="col-md-2">#</div>
                  <div class="col-md-10">Room Code</div>
                
                </div>

                <?php
                $ii = 0;
                // Fetch all assigned rooms for this tenant with room info in a single query
                $sqlstm = $conn->prepare("
                  SELECT a.assgn_id, r.room_id, r.room_no 
                  FROM tbl_assign a
                  JOIN tbl_rooms r ON a.room_id = r.room_id
                  WHERE a.tent_id = :ten_id AND a.assgn_status = 1
                ");
                $sqlstm->execute([':ten_id' => $fetch_ten['ten_id']]);
                while ($room = $sqlstm->fetch(PDO::FETCH_ASSOC)):
                    $ii++;
                ?>
                <div class="row mb-2 align-items-center">
                  <div class="col-md-2"><?= $ii ?></div>
                  <div class="col-md-10"><b><?= htmlspecialchars($room['room_no']) ?></b></div>
                 
                </div>
                <?php endwhile; ?>

              </div>
            </div>
          </div>

          <input type="hidden" id="CPSN" name="CPSN" value="<?= $cmp_sn ?>">
          <div id="display_roomcode"></div>

        </div>

        <div class="modal-footer">
          <!-- Optional buttons if needed -->
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function () {
    $("#auto_code").change(function () {
        var c_id = <?= $company ?>;
        var CPSN = $('#CPSN').val();
        var auto_code = $(this).val();
        var last_CODE = $('#last_CODE').val();
        var floor_code = $('#floor_code').val();

        $(this).after('<div id="loader11"><img src="../img/ajax_loader.gif" alt="loading...." width="30" /></div>');

        $.get('load_floor_code.php', {
            auto_code: auto_code,
            CPSN: CPSN,
            last_CODE: last_CODE,
            floor_code: floor_code,
            c_id: c_id
        }, function (data) {
            $("#display_roomcode").html(data);
            $('#loader11').fadeOut(500, function() { $(this).remove(); });
        });
    });
});
</script>

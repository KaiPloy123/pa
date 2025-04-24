<?php
		if(isset($_POST['download_web_submit']))
		{
			$sql_edit_tmtopup = 'UPDATE download SET mc_download = "'.$_POST['mc_download'].'", ja32_download = "'.$_POST['ja32_download'].'", ja64_download = "'.$_POST['ja64_download'].'"';
			$query_edit_tmtopup = $connect->query($sql_edit_tmtopup);
			if($query_edit_tmtopup)
			{
				$msg = 'แก้ไขการตั้งค่า Download เรียบร้อยแล้ว';
				$alert = 'success';
				$msg_alert = 'สำเร็จ!';
			}
			else
			{
				$msg = 'แก้ไขการตั้งค่า WebSite ไม่สำเร็จ';
				$alert = 'error';
				$msg_alert = 'เกิดข้อผิดพลาด!';
				//* ประกาศ
				echo '<div class="alert alert-info"><i class="fa fa-spinner fa-spin fa-lg"></i> <strong>แก้ไขการตั้งค่า Wallet ไม่สำเร็จ</strong></div>';

				//* REFRESH
				echo "<meta http-equiv='refresh' content='5 ;'>";
			}
			?>
				<script>
					swal("<?php echo $msg_alert; ?>", "<?php echo $msg; ?>", "<?php echo $alert; ?>", {
						button: "Reload",
					})
					.then((value) => {
						window.location.href = window.location.href;
					});
				</script>
		
			<?php
         }
?>
                                <div class="row">
				<div class="col-md-12">
				</div>
			</div>
			<form name="download_web_submit" method="POST">
				<div class="row">
					<div class="col-md-12 mb-3">
			            <label for="mc_download">Link ดาวน์โหลดเกม Minecraft</label>
                                    <input type="text" class="form-control" id="mc_download" name="mc_download" required="" value="<?php echo $download['mc_download']; ?>">
			        </div>
			        <div class="col-md-6 mb-3">
			            <label for="ja32_download">Link ดาวน์โหลด Java32</label>
                                    <input type="text" class="form-control" id="ja32_download" name="ja32_download" required="" value="<?php echo $download['ja32_download']; ?>">
			        </div>
                                    <div class="col-md-6 mb-3">
			            <label for="ja64_download">Link ดาวน์โหลด Java64</label>
                                    <input type="text" class="form-control" id="ja64_download" name="ja64_download" required="" value="<?php echo $download['ja64_download']; ?>">
			        </div>
			        <div class="col-md-12 mb-3">
			        	<button name="download_web_submit" type="submit" class="btn btn-primary btn-block">
			        		แก้ไขการตั้งค่าระบบ
			        	</button>
			        </div>
			    </div>
			</form>
<?php
    require_once(__DIR__ . '/../_system/func_wallet/_truewallet.php');
    require_once '_system/func_wallet/_loginTW.php';

    $sql_wallet = 'SELECT * FROM wallet_account WHERE id = 1';
    $query_wallet = $connect->query($sql_wallet);

    if($query_wallet->num_rows == 1)
    {
        $f_wallet = $query_wallet->fetch_assoc();
        $wallet_email = $f_wallet['email'];
        $wallet_password = $f_wallet['password'];
        $wallet_phone = $f_wallet['phone'];
        $wallet_name = $f_wallet['name'];
        $wallet_message = $f_wallet['message'];
        $wallet_reference_token = $f_wallet['reference_token'];
    }

    /* ห้ามแก้ไข */
    $config_tw = array(
        'email' => $wallet_email,
        'password' => $wallet_password,
        'referen_token' => $wallet_reference_token
    );
    /* จบการห้าม */

    function curl($url) {
        global $config_tw;
        $ch = curl_init();  
        $post = [
            'email' => $config_tw['email'],
            'password' => $config_tw['password'],
            'referen_token' => $config_tw['referen_token']
        ];
        curl_setopt($ch, CURLOPT_URL, $url);    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $data = curl_exec($ch);     
        curl_close($ch);    
        return $data; 
    }

    // Create angpao table if not exists
    $sql_create_angpao = "CREATE TABLE IF NOT EXISTS `angpao` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `code` varchar(36) NOT NULL,
        `amount` int(11) NOT NULL,
        `created_by` varchar(255) NOT NULL,
        `created_at` datetime NOT NULL,
        `claimed_by` varchar(255) DEFAULT NULL,
        `claimed_at` datetime DEFAULT NULL,
        `status` enum('active','claimed','expired') NOT NULL DEFAULT 'active',
        PRIMARY KEY (`id`),
        UNIQUE KEY `code` (`code`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    $connect->query($sql_create_angpao);
?>
<script type="text/javascript">
    function NumbersOnly(e){
        var keynum;
        var keychar;
        var numcheck;
        if(window.event) {// IE
            keynum = e.keyCode;
        } else if(e.which) {// Netscape/Firefox/Opera
            keynum = e.which;
        }
        if(keynum == 13 || keynum == 8 || typeof(keynum) == "undefined"){
            return true;
        }
        keychar= String.fromCharCode(keynum);
        numcheck = /^[0-9]$/;  // อยากจะพิมพ์อะไรได้มั่ง เติม regular expression ได้ที่ line นี้เลยคับ
        return numcheck.test(keychar);
    }
</script>
<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <h5 class="m-0"><i class="fa fa-gift"></i> อังเปา (ซองแดง)</h5>
        <hr>
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="text-danger"><i class="fa fa-envelope text-danger"></i> รับอังเปา</h5>
                        <hr>
                        <p class="text-muted">กรอกรหัสซองอังเปาเพื่อรับเงินเข้าบัญชีของคุณ</p>
                        <form name="claim_angpao" method="POST">
                            <div class="form-group">
                                <input type="text" name="angpao_code" class="form-control" placeholder="รหัสซองอังเปา" required>
                            </div>
                            <button type="submit" name="btn_claim_angpao" class="btn btn-danger btn-block">
                                <i class="fa fa-envelope"></i> รับอังเปา
                            </button>
                        </form>
                    </div>
                </div>
                
                <?php if(isset($_SESSION['username']) && $player['status'] == "admin"): ?>
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="text-primary"><i class="fa fa-plus"></i> สร้างอังเปา (สำหรับแอดมิน)</h5>
                        <hr>
                        <form name="create_angpao" method="POST">
                            <div class="form-group">
                                <label>จำนวนเงิน</label>
                                <input type="number" name="angpao_amount" class="form-control" placeholder="จำนวนเงิน" required>
                            </div>
                            <button type="submit" name="btn_create_angpao" class="btn btn-primary btn-block">
                                <i class="fa fa-plus"></i> สร้างอังเปา
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="text-info"><i class="fa fa-list"></i> รายการอังเปา</h5>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>รหัส</th>
                                        <th>จำนวนเงิน</th>
                                        <th>สถานะ</th>
                                        <th>ผู้รับ</th>
                                        <th>วันที่สร้าง</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql_angpao = "SELECT * FROM angpao ORDER BY id DESC";
                                    $query_angpao = $connect->query($sql_angpao);
                                    while($angpao = $query_angpao->fetch_assoc()):
                                    ?>
                                    <tr>
                                        <td><?php echo $angpao['code']; ?></td>
                                        <td><?php echo number_format($angpao['amount']); ?></td>
                                        <td>
                                            <?php if($angpao['status'] == 'active'): ?>
                                                <span class="badge badge-success">ว่าง</span>
                                            <?php elseif($angpao['status'] == 'claimed'): ?>
                                                <span class="badge badge-danger">ถูกใช้แล้ว</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">หมดอายุ</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $angpao['claimed_by'] ? $angpao['claimed_by'] : '-'; ?></td>
                                        <td><?php echo $angpao['created_at']; ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
// Process claim angpao
if(isset($_POST['btn_claim_angpao'])) {
    if(!isset($_SESSION['username'])) {
        $msg = 'กรุณาเข้าสู่ระบบก่อนรับอังเปา';
        $alert = 'error';
        $msg_alert = 'เกิดข้อผิดพลาด!';
    } else {
        $angpao_code = $connect->real_escape_string($_POST['angpao_code']);
        
        // Check if code exists and is active
        $sql_check = "SELECT * FROM angpao WHERE code = '$angpao_code' AND status = 'active'";
        $query_check = $connect->query($sql_check);
        
        if($query_check->num_rows > 0) {
            $angpao_data = $query_check->fetch_assoc();
            $amount = $angpao_data['amount'];
            
            // Update angpao status
            $sql_update_angpao = "UPDATE angpao SET 
                status = 'claimed',
                claimed_by = '{$_SESSION['username']}',
                claimed_at = NOW()
                WHERE code = '$angpao_code'";
            
            // Update user points
            $sql_update_points = "UPDATE authme SET 
                points = points + $amount,
                topup = topup + $amount,
                rp = rp + $amount
                WHERE username = '{$_SESSION['username']}'";
            
            // Log activity
            $activities_action = "ANGPAO";
            $time_date = date("Y-m-d H:i");
            $sql_insert_log = "INSERT INTO activities (uid, username, action, date, topup_amount, transaction) 
                VALUES ('{$_SESSION['uid']}', '{$_SESSION['username']}', '$activities_action', '$time_date', '$amount', '$angpao_code')";
            
            if($connect->query($sql_update_angpao) && $connect->query($sql_update_points) && $connect->query($sql_insert_log)) {
                $msg = "คุณได้รับเงินจากอังเปา $amount บาท";
                $alert = 'success';
                $msg_alert = 'สำเร็จ!';
            } else {
                $msg = 'เกิดข้อผิดพลาดในการทำรายการ';
                $alert = 'error';
                $msg_alert = 'เกิดข้อผิดพลาด!';
            }
        } else {
            $msg = 'รหัสอังเปาไม่ถูกต้องหรือถูกใช้งานไปแล้ว';
            $alert = 'error';
            $msg_alert = 'เกิดข้อผิดพลาด!';
        }
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

// Process create angpao (admin only)
if(isset($_POST['btn_create_angpao'])) {
    if(!isset($_SESSION['username']) || $player['status'] != "admin") {
        $msg = 'คุณไม่มีสิทธิ์ในการสร้างอังเปา';
        $alert = 'error';
        $msg_alert = 'เกิดข้อผิดพลาด!';
    } else {
        $amount = intval($_POST['angpao_amount']);
        
        if($amount <= 0) {
            $msg = 'กรุณาระบุจำนวนเงินมากกว่า 0';
            $alert = 'error';
            $msg_alert = 'เกิดข้อผิดพลาด!';
        } else {
            // Generate unique code
            $angpao_code = substr(str_shuffle(MD5(microtime())), 0, 8);
            
            // Insert angpao
            $sql_insert = "INSERT INTO angpao (code, amount, created_by, created_at, status) 
                VALUES ('$angpao_code', $amount, '{$_SESSION['username']}', NOW(), 'active')";
            
            if($connect->query($sql_insert)) {
                $msg = "สร้างอังเปาสำเร็จ! รหัส: $angpao_code";
                $alert = 'success';
                $msg_alert = 'สำเร็จ!';
            } else {
                $msg = 'เกิดข้อผิดพลาดในการสร้างอังเปา';
                $alert = 'error';
                $msg_alert = 'เกิดข้อผิดพลาด!';
            }
        }
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
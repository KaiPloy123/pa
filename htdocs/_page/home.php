<div class="card border-0 shadow mb-4">
                        <div class="card-body">
                            <h5 class="m-0"><i class="fa fa-bullhorn"></i> ข่าวสารต่างๆ</h5>
                            <br>
                            <div class="table-responsive">
                                    <table class="table" style="color: black;">
                                        <tbody>
                                            <?php
                                                              $query_announce = $connect->query('SELECT * FROM announce ORDER BY id DESC LIMIT 5');
                                                              if($query_announce->num_rows > 0)
                                                              {
                                                                      $i = 1;
                                                                      while($announce = $query_announce->fetch_assoc())
                                                                      {
                                                                  ?>
                                                                    <tr><td width="65%"><span style="font-size: 14px;" class="badge badge-primary">ข่าวสาร</span> :&nbsp;<a style="font-size: 16px;"> <?php echo $announce['html']; ?> </a></td><td>( วันที่ : <?php echo $announce['date_create']; ?> )<a></a></td></tr>
                                                                  <?php
                                                                      }
                                                              }
                                                              else
                                                              {
                                                                ?>
                                                                  <tr>
                                                                      <td class="text-center" colspan="3">
                                                                        ยังไม่มีประกาศ
                                                                      </td>
                                                                  </tr>
                                                                <?php
                                                              }
                                                          ?>
                                            </tr>
                                        </tbody> 
                                      </table>
                            </div>
                        </div>
                    </div>
                    <div class="card border-0 shadow mb-4">
                        <div class="card-body">
                            <h5 class="m-0"><i class="fa fa-trophy"></i> อันดับรายเดือน Truemoney</h5>
                            <br>
                                                                                                            <?php
                                                                                            $sql_last_m = 'SELECT * FROM authme ORDER BY topup_m DESC LIMIT 5';
                                                                                            $query_last_m = $connect->query($sql_last_m);
                                                                                            ?>
                                                                                            <table class="table table-striped ranking_tb" border="0" style="font-size:13px;">
                                                                                              <thead>
                                                                                                <tr>
                                                                                                  <th scope="col">ชื่อผู้เล่น</th>
                                                                                                  <th scope="col">จำนวน</th>
                                                                                                </tr>
                                                                                              </thead>
                                                                                              <tbody>
                                                                                                <?php
                                                                                                if($query_last_m->num_rows > 0)
                                                                                                {
                                                                                                  while($list_topup = $query_last_m->fetch_assoc())
                                                                                                  {
                                                                                                    ?>
                                                                                                    <tr>
                                                                                                      <td>
                                                                                                        <img src="https://minotar.net/avatar/<?php echo $list_topup['username']; ?>/28" class="mr-3" width="28"><?php echo $list_topup['realname']; ?>
                                                                                                      </td>
                                                                                                      <td>
                                                                                                        <?php echo number_format($list_topup['topup_m'],2); ?> <i class="fas fa-coins text-dark"></i>
                                                                                                      </td>
                                                                                                    </tr>
                                                                                                    <?php
                                                                                                  }
                                                                                                }
                                                                                                else
                                                                                                {
                                                                                                  ?>
                                                                                                  <tr>
                                                                                                    <td>
                                                                                                      <img src="https://minotar.net/avatar/steve/28" class="mr-3" width="28">ไม่มีอันดับคนเติมเงินรายเดือน TrueMoney
                                                                                                    </td>
                                                                                                    <td>
                                                                                                      <?php echo number_format("0",2); ?> <i class="fas fa-coins text-dark"></i>
                                                                                                    </td>
                                                                                                  </tr>
                                                                                                  <?php
                                                                                                }
                                                                                                ?>
                                                                                              </tbody>
                                                                                            </table>
											</div>
                                                                                 </div>
                   
<?php
include_once(VIEWS_PATH . "validate-session.php");
include_once(VIEWS_PATH . "nav-user.php");
?>
<main class="py-5">
     <section id="listado" class="mb-5">
          <div class="container">
               <h2 class="mb-4">Day | Add</h2>
               <form action="<?php echo FRONT_ROOT . "Day/Add" ?>" method="post" class="bg-light-alpha p-5">
                    <div class="row">
                         <div class="col-lg-2"></div>
                         <div class="col-lg-8">
                              <div class="form-group">
                                    <label>Start Date</label>
                                    <?php echo "<input class='form-control mb-3' type='date' name='startDate' min='" . date('Y-m-d') ."'required>"; ?>
                                    <label>End Date</label>
                                    <?php echo "<input class='form-control mb-3' type='date' name='endDate' min='" . date('Y-m-d') . "'required'>"; ?>
                              </div>
                         </div>
                    </div>
                    <button type="submit" name="button" class="btn btn-success ml-auto d-block text-center">
                         <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                              <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z" />
                         </svg>
                         Add
                    </button>
                    <?php
                    if ($message != "") {
                    ?>
                         <div class='form-group text-center'>
                              <?php
                              if ($type == "") {
                              ?>
                                   <div class='alert alert-danger'>
                                        <p><?php echo $message ?></p>
                                   </div>
                              <?php
                              } else {
                              ?>
                                   <div class='alert alert-success'>
                                        <p><?php echo $message ?></p>
                                   </div>
                              <?php
                              }
                              ?>
                         </div>
                    <?php
                    }
                    ?>
               </form>
          </div>
     </section>
</main>
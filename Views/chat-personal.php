<?php
include_once(VIEWS_PATH . "validate-session.php");
include_once(VIEWS_PATH . "nav-user.php");

?>
<main class="py-5">
     <section id="listado" class="mb-5">
          <div class="container">
               <div class="mb-3">
               </div>
               <div class="container">
                    <h2 class="mb-4">Your chat's </h2>
                    <table class="table table-dark text-center">
                         <thead>
                              <th>User's</th>
                              <th>Time</th>
                              <th>Last message you received</th>
                         </thead>
                         <tbody>
                         <form action="<?php echo FRONT_ROOT."Chat/Add"?>" method="post">  
                              <?php
                                if(!empty($chatList)){
                                    foreach($chatList as $chat){
                                       ?>
                                       <tr>
                                        <td><?php echo $chat->getReciever_user_id()->getName()?></td>
                                        <td><?php echo $chat->getCreated_on()?><td>
                                        <td><?php echo $chat->getMessage()?><td>     
                                       </tr>
                                       
                                       <?php     
                                    }
                                }
                                else{
                                    echo 'Sorry, you currently have no chats, come back later...';
                                }
                              ?>
                                                            
                           </form>    
                         </tbody>
                    </table>
               </div>
     </section>
</main>

<?php include('footer.php') ?>
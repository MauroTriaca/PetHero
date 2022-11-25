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
                         <form action="<?php echo FRONT_ROOT."Chat/ShowChatPersonalView"?>" method="post">  
                              <?php
                                if(!empty($chatList)){
                                    foreach($chatList as $chat){
                                       ?>
                                      
                                       
                                       <tr>
                                        <td><?php echo $chat->getReciever_user_id()->getName()?></td>
                                        <td><?php echo $chat->getCreated_on()?><td>
                                        <td><?php echo $chat->getMessage()?><td>
                                        <td><button type="submit" name="reciever_user_id" value="<?php echo $chat->getReciever_user_id()->getId()?>"class="btn btn-success">
                                        <svg xmlns="http://www.w3.org/2000/svg"  width="20" height="20"  viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M256 32C114.6 32 .0272 125.1 .0272 240c0 47.63 19.91 91.25 52.91 126.2c-14.88 39.5-45.87 72.88-46.37 73.25c-6.625 7-8.375 17.25-4.625 26C5.818 474.2 14.38 480 24 480c61.5 0 109.1-25.75 139.1-46.25C191.1 442.8 223.3 448 256 448c141.4 0 255.1-93.13 255.1-208S397.4 32 256 32zM256.1 400c-26.75 0-53.12-4.125-78.38-12.12l-22.75-7.125l-19.5 13.75c-14.25 10.12-33.88 21.38-57.5 29c7.375-12.12 14.37-25.75 19.88-40.25l10.62-28l-20.62-21.87C69.82 314.1 48.07 282.2 48.07 240c0-88.25 93.25-160 208-160s208 71.75 208 160S370.8 400 256.1 400z"/></svg>
                                        Send Message
                                        </button></td>     
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
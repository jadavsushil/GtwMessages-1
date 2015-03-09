<div class="col-md-12 col-xs-12 text-center profile-div">
<?php 
foreach ($usersList as $user):
?>
    <?php 
    $notification = (isset($user->unread_message) && !empty($user->unread_message))?'<i class="badge">'.$user->unread_message.'</i>':'';
    if(isset($user->file->filename) && !empty($user->file->filename)){
        echo $this->Html->link($this->Html->image('/files/uploads/'.$user->file->filename,['class'=>'img-circle img-responsive center-block']). $notification . '<span>' .$user->first . '</span>',['plugin'=>'GtwMessage','controller'=>'messages','action'=>'compose',$user->id],['escape'=>false,'class'=> (isset($recipientID) && ($recipientID == $user['id']))?'bg-danger':'' ]);
    }else{
        echo $this->Html->link($this->Html->image('http://i.imgur.com/dCVa3ik.jpg',['class'=>'img-circle img-responsive center-block']). $notification . '<span>' .$user->first . '</span>',['plugin'=>'GtwMessage','controller'=>'messages','action'=>'compose',$user->id],['escape'=>false]);
    }
    ?>
<?php endforeach; ?>
</div>
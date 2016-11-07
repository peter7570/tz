<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
?>
<div class="site-index">                                        
<?php
?>
    <div class="body-content">
    
    <h2><?= $user->username. ' '. $user->id; ?> </h2>
        <?php $uid = $user->id; ?>   
        <?= GridView::widget(
          [
             'dataProvider' => $friends,
             'columns' => [
                'id',
                'username',
                'email',
              [
                'label' => 'ActionColumn',
                'format' => 'raw',
                'value' => function($model) use ($uid) {
                  if($model->getFriends1($uid) !== NULL && ArrayHelper::isIn($model->id,$model->getFriends1($uid))) {  
                    return Html::a(
                    'RemoveFriend',
                    [Url::to(['site/removefriend', 'fid' => $model->id, 'uid' => $uid])]
                   ); 
                  } else {
                     return Html::a(
                    'AddFriend',
                    [Url::to(['site/addfriend', 'fid' => $model->id, 'uid' => $uid])]
                   ); 
                  }
                }
              ],
              [
                'label' => 'ActionColumn',
                'format' => 'raw',
                'value' => function($model){    
                     return Html::a(
                    'View',
                    [Url::to(['/site/privateoffice/', 'id' => $model->id])]
                   ); 
                 
                }
              ],
              [
                'label' => 'ActionColumn',
                'format' => 'raw',
                'value' => function($model){    
                     return Html::a(
                    'Delete',
                    [Url::to(['/site/deleteuser/', 'id' => $model->id])]
                   ); 
                 
                }
              ],
            ]
          ]
        );
        ?>

    </div>
</div>

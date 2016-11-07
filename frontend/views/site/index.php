<?php

use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

?>
<div class="site-index">

<?php $dataProvider = new ActiveDataProvider([
    
    'query' => $model::find()->where('user.id != :id',[':id'=>Yii::$app->user->id]),
   // 'query' => ArrayHelper::remove($model::find(), Yii::$app->user->id),
]);
?>

    <div class="body-content">
    
        <?php if (Yii::$app->user->isGuest) { ?>
    
        <div>Lorem ipsum dolor sit amet, nec in errem explicari interpretaris. Ne mea modo dissentias definitionem, etiam quodsi scripta mei et. Enim quidam vivendum mel eu, diam facer moderatius an sea. Ad labores dolores suavitate vis, per an populo definiebas, quo sumo laoreet vituperata ea.

             Mea at facilis deserunt aliquando. Est debitis gubergren appellantur ne, te pro facete mediocritatem, alterum posidonium ad cum. Habeo etiam his id, in mea viderer oporteat. At dico molestiae vim, has impetus voluptaria ad. Indoctum dissentias definitionem eam an, dicit corpora ius ex.

             No possim ancillae hendrerit quo, nec postea quaeque constituto at. Velit option usu ad, in duo postulant omittantur referrentur. Quo ex solet oportere forensibus. Eos ne vidit erroribus, te cibo etiam saperet quo, in mea tation nostrud. Verear noluisse insolens et eos, eam te dicant sanctus, sea ad duis minim legere. Sale everti meliore te pri, cu est mundi oratio, movet partem veritus vim eu.

             Ut congue pericula vim, cu usu falli aeterno scripserit. No his nostro oportere, eu quod discere posidonium pro, nam at utamur lucilius temporibus. Pro natum quaestio te, sit clita omnes molestie no. Vis ignota euismod ponderum eu, viris iudicabit no vis, amet vocent salutatus ne pro. No aeque omittantur theophrastus nam. Maiorum tibique insolens eum an.

             Eirmod meliore deleniti mel ad, nec omnes dissentias ei, homero recteque percipitur duo cu. Cu usu prima option consequuntur, aliquid equidem copiosae ne nam, an his summo falli nonumes. Cum dolores convenire repudiare at. Usu alterum molestie ne, posse utamur gubergren cum ad. Eam omnes utamur temporibus cu.

        </div> 
    
        <?php } else { 

        echo   \yii\grid\GridView::widget(
          [
             'dataProvider' => $dataProvider,
             'columns' => [
                'id',
                'username',
                'email',
              [
                'label' => 'ActionColumn',
                'format' => 'raw',
                'value' => function($model){    
                  if($model->getFriends() !== NULL && ArrayHelper::isIn($model->id,$model->getFriends())) {  
                    return Html::a(
                    'RemoveFriend',
                    [Url::to(['/site/removefriend/', 'fid' => $model->id, 'uid' => Yii::$app->user->id])]
                   ); 
                  } else {
                     return Html::a(
                    'AddFriend',
                    [Url::to(['/site/addfriend/', 'fid' => $model->id, 'uid' => Yii::$app->user->id])]
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
        
        
        } ?>
    
    

    </div>
    
    
</div>

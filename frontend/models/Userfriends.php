<?php

namespace frontend\models;

use Yii;
use yii\helpers\Url;

class UserFriends extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'user_friends';
    }

    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['friend_id'], 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'friend_id' => 'Friend_id',
        ];
    }
    
    public function addFriend($fid,$uid)
    {
         $this->user_id = $uid;
         $this->friend_id = $fid;
         $this->save();
         return $this;
    }
    
    public function removeFriend($fid,$uid)
    {
         self::deleteAll('user_id = :user_id AND friend_id = :friend_id', 
         array(':user_id' => $uid, ':friend_id' => $fid));
         return true;
    }
    

    
     
}

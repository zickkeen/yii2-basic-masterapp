<?php

use yii\db\Schema;
use yii\db\Migration;

class m200920_013933_userDataInsert extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        for($i=1;$i<50;$i++)
        {
            
            $username = $this->generate(5);
            $token = $this->generate(30);
            $password = Yii::$app->security->generatePasswordHash($username);
            $data[] = [
                    'id' => $i,
                    'username' => $username,
                    'auth_key' => $token,
                    'password_hash' => $password,
                    'verification_token' => $token,
                    'email' =>  $username.'@mail.com',
                    'status' => '10',
                    'created_at' => mktime(),
                    'updated_at' => mktime(),
                    'last_login' => null,
                    'last_ip' => null,
                ];
        }
        
        $this->batchInsert('{{%user}}',
            ["id", "username", "auth_key", "password_hash", "verification_token", "email", "status", "created_at", "updated_at", "last_login", "last_ip"],
            $data,
        );
    }

    public function safeDown()
    {
        $this->truncateTable('{{%user}} CASCADE');
    }

    private function generate($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

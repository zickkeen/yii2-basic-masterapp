<?php

use yii\db\Schema;
use yii\db\Migration;

class m200919_082506_user extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable('{{%user}}',[
            'id'=> $this->primaryKey(11),
            'username'=> $this->string(255)->notNull(),
            'auth_key'=> $this->string(32)->notNull(),
            'password_hash'=> $this->string(255)->notNull(),
            'verification_token'=> $this->string(255)->null()->defaultValue(null),
            'email'=> $this->string(255)->notNull(),
            'status'=> $this->smallInteger(6)->notNull()->defaultValue(10),
            'created_at'=> $this->integer(11)->notNull(),
            'updated_at'=> $this->integer(11)->notNull(),
            'last_login'=> $this->integer(11)->null()->defaultValue(null),
            'last_ip'=> $this->string(40)->null()->defaultValue(null),
        ], $tableOptions);

        $this->createIndex('username','{{%user}}',['username'],true);
        $this->createIndex('email','{{%user}}',['email'],true);
        $this->createIndex('verification_token','{{%user}}',['verification_token'],false);
    }

    public function safeDown()
    {
            $this->dropTable('{{%user}}');
    }
}

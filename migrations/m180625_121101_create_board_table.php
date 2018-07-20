<?php

use yii\db\Migration;

class m180625_121101_create_board_table extends Migration
{
    const BOARD_TABLE = '{{%board}}';
	const BOARD_GROUP_TABLE = '{{%board_group}}';
	const BOARD_DATA_TABLE = '{{%board_data}}';
	const BOARD_COMMENT_TABLE = '{{%board_comment}}';
	const BOARD_UPLOAD_TABLE = '{{%board_upload}}';
	const BOARD_STAT_MONTH_TABLE = '{{%board_stat_month}}';
	const BOARD_STAT_DAY_TABLE = '{{%board_stat_day}}';
	const USER_TABLE = '{{%user}}';
    
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable(self::BOARD_GROUP_TABLE, [
            'id' => $this->primaryKey(),
            'slug' => $this->string(200)->notNull(),
            'title' => $this->string(200)->notNull(),
            'visible' => $this->integer()->notNull()->defaultValue(1)->comment('0-hidden,1-visible'),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $tableOptions);
		
        $this->createIndex('board_group_slug', self::BOARD_GROUP_TABLE, 'slug');
        $this->createIndex('board_group_visible', self::BOARD_GROUP_TABLE, 'visible');
        $this->addForeignKey('fk_board_group_created_by', self::BOARD_GROUP_TABLE, 'created_by', self::USER_TABLE, 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_board_group_updated_by', self::BOARD_GROUP_TABLE, 'updated_by', self::USER_TABLE, 'id', 'SET NULL', 'CASCADE');
        $this->insert(self::BOARD_GROUP_TABLE, ['id' => 1, 'slug' => 'default', 'title'=>'Default', 'visible'=>1, 'created_at' => time(), ]);

        $this->createTable(self::BOARD_TABLE, [
            'id' => $this->primaryKey(),
            'group_id' => $this->integer(),
			'bid' => $this->string(30)->notNull()->defaultValue(''),
			'name' => $this->string(200)->notNull()->defaultValue(''),
			'category'=> $this->text(),
			'skin' => $this->string(32), 
			'm_skin' => $this->string(32), 
			'addinfo' => $this->text(),
			'bbsvar' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $tableOptions);
		
        $this->createIndex('board_group_id', self::BOARD_TABLE, 'group_id');
        $this->createIndex('board_bid', self::BOARD_TABLE, 'bid');
        $this->addForeignKey('fk_board_group_id', self::BOARD_TABLE, 'group_id', self::BOARD_GROUP_TABLE, 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_board_created_by', self::BOARD_TABLE, 'created_by', self::USER_TABLE, 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_board_updated_by', self::BOARD_TABLE, 'updated_by', self::USER_TABLE, 'id', 'SET NULL', 'CASCADE');
		$this->insert(self::BOARD_TABLE, ['id' => 1, 'group_id' => '1', 'bid'=>'test', 'name'=>'Test Board', 'skin'=>'default', 'm_skin'=>'m_default', 'created_at' => time(), ]);

		$this->createTable(self::BOARD_DATA_TABLE, [
            'id' => $this->primaryKey(),
			'board_id' => $this->integer(),
			'bid' => $this->string(30)->notNull()->defaultValue(''),
			'depth' => $this->integer()->notNull()->defaultValue(0),
			'display' => $this->integer()->notNull()->defaultValue(1)->comment('0-hidden,1-display'),
			'hidden' => $this->integer()->notNull()->defaultValue(0)->comment('0-not_hidden,1-hidden'),
			'name' => $this->string(32)->notNull()->defaultValue(''),
			'nic' => $this->string(32)->notNull()->defaultValue(''),
			'pw' => $this->string(32)->defaultValue(''),
			'title'=> $this->string(200)->notNull()->defaultValue(''),
			'category'=> $this->string(100)->notNull()->defaultValue(''),
			'content'=> $this->text()->notNull()->defaultValue(''),
			'html'=> $this->string(4),
			'tag'=> $this->string(200),
			'hit' => $this->integer(), 
			'down' => $this->integer(), 
			'comment' => $this->integer(), 
			'addinfo' => $this->text(),
			'bbsvar' => $this->text(),
			'ip' => $this->string(25),
			'agent' => $this->string(150),
			'adddata' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $tableOptions);
        
        $this->createIndex('board_data_bid', self::BOARD_DATA_TABLE, 'bid');
        $this->createIndex('board_data_display', self::BOARD_DATA_TABLE, 'display');
		$this->createIndex('board_data_hidden', self::BOARD_DATA_TABLE, 'hidden');
        $this->createIndex('board_data_category', self::BOARD_DATA_TABLE, 'category');
		$this->addForeignKey('fk_board_data_board_id', self::BOARD_DATA_TABLE, 'board_id', self::BOARD_TABLE, 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_board_data_created_by', self::BOARD_DATA_TABLE, 'created_by', self::USER_TABLE, 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_board_data_updated_by', self::BOARD_DATA_TABLE, 'updated_by', self::USER_TABLE, 'id', 'SET NULL', 'CASCADE');
		
        # $this->addForeignKey('fk_board_bid', self::BOARD_TABLE, 'bid', self::BOARD_DATA_TABLE, 'bid');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_board_bid', self::BOARD_TABLE);

		$this->dropForeignKey('fk_board_data_created_by', self::BOARD_DATA_TABLE);
		$this->dropForeignKey('fk_board_data_updated_by', self::BOARD_DATA_TABLE);

        $this->dropForeignKey('fk_board_group_id', self::BOARD_TABLE);
        $this->dropForeignKey('fk_board_created_by', self::BOARD_TABLE);
        $this->dropForeignKey('fk_board_updated_by', self::BOARD_TABLE);

        $this->dropForeignKey('fk_board_group_created_by', self::BOARD_GROUP_TABLE);
        $this->dropForeignKey('fk_board_group_updated_by', self::BOARD_GROUP_TABLE);

        $this->dropTable(self::BOARD_DATA_TABLE);
        $this->dropTable(self::BOARD_TABLE);
        $this->dropTable(self::BOARD_GROUP_TABLE);
    }
}
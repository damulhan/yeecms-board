<?php

use yii\db\Migration;

/**
 * Class m180626_051558_add_board_menu_links
 */
class m180626_051558_add_board_menu_links extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%menu_link}}', ['id' => 'board', 'menu_id' => 'admin-menu',
			'image' => 'pencil', 
			'created_by' => 1, 'order' => 3]);
		$this->insert('{{%menu_link}}', ['id' => 'board-group', 'menu_id' => 'admin-menu', 
			'link' => '/board/group/index', 
			'parent_id' => 'board', 
			'created_by' => 1, 'order' => 1]);
        $this->insert('{{%menu_link}}', ['id' => 'board-board', 'menu_id' => 'admin-menu', 
			'link' => '/board/default/index', 
			'parent_id' => 'board', 
			'created_by' => 1, 'order' => 2]);
		$this->insert('{{%menu_link}}', ['id' => 'board-data', 'menu_id' => 'admin-menu', 
			'link' => '/board/data/index', 
			'parent_id' => 'board', 
			'created_by' => 1, 'order' => 3]);
			
        $this->insert('{{%menu_link_lang}}', ['link_id' => 'board', 'label' => 'Boards', 'language' => 'en-US']);
        $this->insert('{{%menu_link_lang}}', ['link_id' => 'board-group', 'label' => 'Board Group', 'language' => 'en-US']);
        $this->insert('{{%menu_link_lang}}', ['link_id' => 'board-board', 'label' => 'Board List', 'language' => 'en-US']);
        $this->insert('{{%menu_link_lang}}', ['link_id' => 'board-data', 'label' => 'Board Writings', 'language' => 'en-US']);
			
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%menu_link}}', ['like', 'id', 'board-group']);
        $this->delete('{{%menu_link}}', ['like', 'id', 'board-board']);
        $this->delete('{{%menu_link}}', ['like', 'id', 'board-data']);
        $this->delete('{{%menu_link}}', ['like', 'id', 'board']);
		
        #return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180626_051558_add_board_menu_links cannot be reverted.\n";

        return false;
    }
    */
}

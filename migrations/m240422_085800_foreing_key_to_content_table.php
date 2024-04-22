<?php

use yii\db\Migration;

/**
 * Class m240422_085800_foreing_key_to_content_table
 */
class m240422_085800_foreing_key_to_content_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	    $this->addForeignKey(
		    'fk-content-category_fk',
		    'content',
		    'category_fk',
		    'category',
		    'id',
		    'SET NULL'
	    );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
	    $this->dropForeignKey('fk-post-author_id', 'post');
    }
}

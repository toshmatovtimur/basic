<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%content}}`.
 */
class m240422_084828_add_category_fk_column_to_content_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('content', 'category_fk', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('content', 'category_fk');
    }
}

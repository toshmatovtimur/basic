<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ContentSearch represents the model behind the search form of `app\models\Content`.
 */
class ContentSearch extends Content
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fk_status', 'fk_user_create'], 'integer'],
            [['header', 'alias', 'date_create', 'date_publication', 'text_short', 'text_full', 'date_update_content', 'tags'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Content::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'date_create' => $this->date_create,
            'date_publication' => $this->date_publication,
            'date_update_content' => $this->date_update_content,
            'fk_status' => $this->fk_status,
            'fk_user_create' => $this->fk_user_create,
        ]);

        $query->andFilterWhere(['ilike', 'header', $this->header])
            ->andFilterWhere(['ilike', 'alias', $this->alias])
            ->andFilterWhere(['ilike', 'text_short', $this->text_short])
            ->andFilterWhere(['ilike', 'text_full', $this->text_full])
            ->andFilterWhere(['ilike', 'tags', $this->tags]);

        return $dataProvider;
    }
}

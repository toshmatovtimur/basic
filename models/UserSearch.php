<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form of `app\models\User`.
 */
class UserSearch extends User
{
	public $role;
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['firstname', 'role', 'middlename', 'lastname', 'birthday', 'sex', 'username', 'password', 'role.role_user', 'date_last_login', 'created_at', 'updated_at', 'access_token', 'auth_key'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = User::find();
	    $query->joinWith(['role']);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
	        'pagination' => [
		        'pageSize' => 5,
	        ],
        ]);

	    $dataProvider->sort->attributes['role'] = [
		    // The tables are the ones our relation are configured to
		    // in my case they are prefixed with "tbl_"
		    'asc' => ['role_user' => SORT_ASC],
		    'desc' => ['role_user' => SORT_DESC],
	    ];



        $this->load($params);

        if (!$this->validate())
        {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'birthday' => $this->birthday,
            'date_last_login' => $this->date_last_login,
            'fk_role' => $this->fk_role,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['ilike', 'firstname', $this->firstname])
            ->andFilterWhere(['ilike', 'middlename', $this->middlename])
            ->andFilterWhere(['ilike', 'lastname', $this->lastname])
            ->andFilterWhere(['ilike', 'sex', $this->sex])
            ->andFilterWhere(['ilike', 'username', $this->username])
            ->andFilterWhere(['ilike', 'password', $this->password])
            ->andFilterWhere(['ilike', 'access_token', $this->access_token])
            ->andFilterWhere(['ilike', 'auth_key', $this->auth_key])
	        ->andFilterWhere(['ilike', 'role_user', $this->role]);

        return $dataProvider;
    }
    
}

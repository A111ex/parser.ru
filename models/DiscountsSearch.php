<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Discounts;

/**
 * DiscountsSearch represents the model behind the search form about `app\models\Discounts`.
 */
class DiscountsSearch extends Discounts
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'providers_id'], 'integer'],
            [['goods_type_type', 'params', 'values'], 'safe'],
            [['coef'], 'number'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Discounts::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'providers_id' => $this->providers_id,
            'coef' => $this->coef,
        ]);

        $query->andFilterWhere(['like', 'goods_type_type', $this->goods_type_type])
            ->andFilterWhere(['like', 'params', $this->params])
            ->andFilterWhere(['like', 'values', $this->values]);

        return $dataProvider;
    }
}

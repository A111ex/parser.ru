<?php
namespace app\models;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Offers;
/**
 * offersSearch represents the model behind the search form about `app\models\offers`.
 */
class OffersSearch extends Offers
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'quantity', 'providers_id', 'goods_id'], 'integer'],
            [['fix_price', 'price'], 'number'],
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
        $query = Offers::find();
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
            'quantity' => $this->quantity,
            'fix_price' => $this->fix_price,
            'providers_id' => $this->providers_id,
            'goods_id' => $this->goods_id,
            'price' => $this->price,
        ]);
        return $dataProvider;
    }
}
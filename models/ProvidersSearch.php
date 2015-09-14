<?php
namespace app\models;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Providers;
/**
 * providersSearch represents the model behind the search form about `app\models\providers`.
 */
class ProvidersSearch extends Providers
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'date_last_down'], 'integer'],
            [['meta', 'name', 'id_script'], 'safe'],
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
        $query = Providers::find();
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
            'date_last_down' => $this->date_last_down,
        ]);
        $query->andFilterWhere(['like', 'meta', $this->meta])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'id_script', $this->id_script]);
        return $dataProvider;
    }
}
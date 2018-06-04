<?php

/**
 * This is the model class for table "2gom_con_rel_contest_payments".
 *
 * The followings are the available columns in table '2gom_con_rel_contest_payments':
 * @property string $id_contest
 * @property string $id_tipo_pago
 * @property string $txt_config_1
 * @property string $txt_config_2
 * @property string $txt_config_3
 * @property string $txt_config_4
 * @property string $txt_config_5
 */
class ConRelContestPayments extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '2gom_con_rel_contest_payments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_contest, id_tipo_pago', 'required'),
			array('id_contest, id_tipo_pago', 'length', 'max'=>10),
			array('txt_config_1, txt_config_2, txt_config_3, txt_config_4, txt_config_5', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_contest, id_tipo_pago, txt_config_1, txt_config_2, txt_config_3, txt_config_4, txt_config_5', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_contest' => 'Id Contest',
			'id_tipo_pago' => 'Id Tipo Pago',
			'txt_config_1' => 'Txt Config 1',
			'txt_config_2' => 'Txt Config 2',
			'txt_config_3' => 'Txt Config 3',
			'txt_config_4' => 'Txt Config 4',
			'txt_config_5' => 'Txt Config 5',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id_contest',$this->id_contest,true);
		$criteria->compare('id_tipo_pago',$this->id_tipo_pago,true);
		$criteria->compare('txt_config_1',$this->txt_config_1,true);
		$criteria->compare('txt_config_2',$this->txt_config_2,true);
		$criteria->compare('txt_config_3',$this->txt_config_3,true);
		$criteria->compare('txt_config_4',$this->txt_config_4,true);
		$criteria->compare('txt_config_5',$this->txt_config_5,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ConRelContestPayments the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

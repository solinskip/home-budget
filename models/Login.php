<?php

namespace app\models;

use Yii;
use yii\base\Model;

class Login extends Model
{
    public $username;
    public $password;

    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required', 'message' => '{attribute} nie może pozostać bez wartości.'],
            [['password'], 'validatePassword'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Nazwa użytkownika',
            'password' => 'Hasło',
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $login = Yii::$app->user->login($this->getUser(), 3600 * 24);
            Yii::$app->user->identity->logged_at = Yii::$app->formatter->asTimestamp(date('Y-d-m h:i:s'));
            Yii::$app->user->identity->save();
            return $login;
        }

        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Nieprawidłowy login lub hasło.');
            }
        }
    }
}

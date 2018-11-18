<?php

namespace app\models;

use Yii;
use yii\base\Model;

class Signup extends Model
{
    public $username;
    public $email;
    public $password;

    public function rules()
    {
        return [
            [['username', 'password'], 'required', 'message' => '{attribute} nie może pozostać bez wartości.'],
            [['username'], 'unique', 'targetClass' => 'app\models\User', 'message' => 'Podana nazwa użytkownika jest już zajętą.'],
            [['email'], 'unique', 'targetClass' => 'app\models\User', 'message' => 'Podany adres email jest już zajęty.'],
            [['username'], 'string', 'max' => 50],
            [['username', 'email'], 'trim'],
            [['email'], 'email', 'message' => 'Podany adres email jest niepoprawny.'],
            [['password'], 'string', 'min' => 6, 'message' => '{attribute} musi zawierać przynajmniej 6 znaków.'],
            [['password'], 'string', 'max' => 255, 'message' => '{attribute} może zawierać 255 znaków.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Nazwa użytkownika',
            'email' => 'Adres email',
            'password' => 'Hasło',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null
     * @throws \yii\base\Exception
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);

        return $user->save() ? $user : null;
    }
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;
    protected $table = 'users';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'pepper',
        'created_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

        /**
     * Check credentials
     * 
     * @param string $email
     * @param string $pass
     * 
     * @return array
     */
    public function signIn(string $email, string $pass): array
    {
        $user = $this->getUserByEmail($email);
        if(!$user) return ["status" => 403, "id" => null];

        $pass = $this->createPassword($pass, $user->pepper);
        if($pass === $user->password) return ["status" => 200, "id" => $user->id];

        return ["status" => 403, "id" => null];
    }

    /**
     * Create new user
     * 
     * @param string $email
     * @param string $pass
     * @param string $name
     * 
     * @return array
     */
    public function signUp(string $email, string $pass, string $name): array
    {
        $oldUser = $this->getUserByEmail($email);
        if($oldUser) return ['status' => 409, 'id' => null];

        $pepper = $this->createHashFromDate();
        $passHash = $this->createPassword($pass, $pepper);
        $newUser = static::create([
            'name' => $name,
            'email' => $email,
            'password' => $passHash,
            'pepper' => $pepper,
            'created_at' => date(DATE_ATOM)
        ]);

        if($newUser) return ['status' => 202, 'id' => $newUser->id];
        return ['status' => 500, 'id' => null];
    }

    /**
     * Change name of user
     * 
     * @param string $name
     * @param int $id
     * 
     * @return array
     */
    public function changeUserName(string $name, int $id): array
    {
        $user = $this->getUserById($id);
        if(!$user) return ["status" => 404, "msg" => "User doesn't exist"];
        $user->name = $name;
        $user->save();

        if($user->isClean()) return ["status" => 200, "msg" => ""];
        return ["status" => 500, "msg" => "Something goes wrong"];
    }

    /**
     *  Change user password
     *  @param string $oldPass
     *  @param string $newPass
     *  @param int $id
     * 
     *  @return array
     */
    public function changeUserPass(string $oldPass, string $newPass, int $id): array
    {
        $user = $this->getUserById($id);
        if(!$user) return ['status' => 409, 'data' => ['isUpdated' => false, 'msg' => 'User doesn\'t exist']];

        $pass = $this->createPassword($oldPass, $user->paper);
        if($user->pass === $pass) {
            $user->pass = $this->createPassword($newPass, $user->paper);
            $user->save();

            if($user->isClean()) return ["status" => 200, 'data' => ['isUpdated' => true, "msg" => ""]];
            return ["status" => 500, 'data' => ['isUpdated' => false, "msg" => 'Something goes wrong']];
        }

        return ['status' => 403, 'data' => ['isUpdated' => false, "msg" => 'Password is not correct']];
    }

    /**
     *  Return user public data
     *  @param int $id
     * 
     *  @return array
     */
    public function returnUserData(string $id): array
    {
        $user = $this->getUserById($id);
        if(!$user) return ['name' => '', 'email' => '', 'id' => ''];

        return ['name' => $user->name, 'email' => $user->email, 'id' => $user->id];
    }

    /**
     *  Return user new pass by user email
     *  @param string $email
     * 
     *  @return array
     */
    public function restorePass(string $email): array
    {
        $user = $this->getUserByEmail($email);
        if(!$user) return ['status' => 400, 'data' => ['isUpdated' => false, 'name' => '', 'pass' => '', 'msg' => 'User doesn\'t exist']];

        $newPass = $this->createHashForRecovery($email);
        $user->pass = $this->createPassword($newPass, $user->paper);
        $user->save();

        if($user->isClean()) return ['status' => 200, 'data' => ['isUpdated' => true, 'name' => $user->name, 'pass' => $newPass, 'msg' => '']];
        return ["status" => 500, 'data' => ['isUpdated' => false, 'name' => '', 'pass' => '', 'msg' => 'Something goes wrong']];
    }

    /**
     * Return User by email
     * 
     * @param string $email
     * 
     * @return User
     */
    public function getUserByEmail(string $email) {
        return static::where('email', $email)->first();
    }

    /**
     * Return User by id
     * 
     * @param int $id
     * 
     * @return User
     */
    public function getUserById(int $id) {
        return static::find($id);
    }

    private function createPassword(string $pass, string $paper): string {
        $salt = config('auth.salt');
        $hash = md5($pass);
        return $paper.$hash.$salt;
    }

    private function createHashFromDate(): string {
        return md5(date(DATE_ATOM));
    }

    private function createHashForRecovery(string $email): string {
        $salt = config('auth.salt2');
        return substr(md5($email.$salt), 10);
    }
    

}

<?php
namespace App\Domain\Client\Model;

use App\Domain\User\Model\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property User teachers
 */
class Client extends User
{
    protected $table = 'users';

    public function teachers(): BelongsToMany
    {
        return $this
            ->belongsToMany(User::class, 'user_clients','client_id','user_id')
            ->withPivot([
                'name',
                'surname'
            ]);
    }
}

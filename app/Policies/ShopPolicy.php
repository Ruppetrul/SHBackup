<?php

namespace App\Policies;

use App\Models\Shop;
use App\Models\User;

class ShopPolicy
{
    private function isOwner(User $user, Shop $shop): bool
    {
        return $shop->owner_id === $user->id;
    }

    public function view(User $user, Shop $shop): bool
    {
        return $this->isOwner($user, $shop);
    }

    public function productUpdate(User $user, Shop $shop): bool
    {
        return $this->isOwner($user, $shop);
    }

    public function categoriesStore(User $user, Shop $shop): bool
    {
        return $this->isOwner($user, $shop);
    }

    public function addTelegramToken(User $user, Shop $shop): bool
    {
        return $this->isOwner($user, $shop);
    }

    public function productDelete(User $user, Shop $shop): bool
    {
        return $this->isOwner($user, $shop);
    }

    public function shopDelete(User $user, Shop $shop): bool
    {
        return $this->isOwner($user, $shop);
    }
}

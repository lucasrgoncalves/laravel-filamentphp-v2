<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Models\UserOrder;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('12345678')
        ]);

        // Cria 5 usuários
        $users = User::factory()->count(50)->create();
        $faker = app(Faker::class);

        foreach ($users as $user) {
            // Cria um pedido para o usuário
            $order = UserOrder::factory()->create([
                'user_id' => $user->id,
            ]);

            // Agora podemos associar produtos ao pedido, se necessário
            $products = Product::inRandomOrder()->take(4)->get();

            foreach ($products as $product) {
                $amount = rand(1, 5);

                // Cria os itens do pedido
                $order->items()->create([
                    'product_id' => $product->id,
                    'amount' => $amount,
                    'order_value' => $product->price * $amount,
                    'created_at' => $faker->dateTimeBetween('-1 year', 'now')
                ]);
            }
        }
    }
}

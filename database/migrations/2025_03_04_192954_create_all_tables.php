<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{    
    public function up()
    {
        // Organizations Table
        Schema::create('organizations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('contact')->nullable();
            $table->string('full_address')->nullable();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('restaurant_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('restaurant_id')->constrained('restaurants')->onDelete('cascade');
            $table->uuid('organization_id')->nullable()->constrained('organizations')->onDelete('cascade'); // Si un restaurant livre une entreprise spécifique
            $table->uuid('day_id')->constrained('days')->onDelete('cascade'); // Clé étrangère vers la table days
            $table->time('order_deadline'); // Heure limite de commande
            $table->timestamps();
        });

        // Users Table (sans ENUM, mais avec relation vers `roles`)
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->decimal('balance', 10, 2)->default(0.00);
            $table->uuid('organization_id')->nullable()->constrained('organizations')->onDelete('cascade');
            $table->json('preferences')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // User Roles Table (Many-to-Many entre users et roles)
        Schema::create('user_roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->constrained('users')->onDelete('cascade');
            $table->uuid('role_id')->constrained('roles')->onDelete('cascade');
            $table->timestamps();
        });



        // Transactions Table
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->uuid('transaction_type_id')->constrained('transaction_types');
            $table->timestamps();
        });

        // Restaurants Table
        Schema::create('restaurants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('contact')->nullable();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Dishes Table
        Schema::create('dishes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->uuid('restaurant_id')->constrained('restaurants')->onDelete('cascade');
            $table->timestamps();
        });

        // Dish Options Table
        Schema::create('dish_options', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('dish_id')->constrained('dishes')->onDelete('cascade');
            $table->string('name');
            $table->decimal('extra_price', 10, 2)->default(0.00);
            $table->timestamps();
        });

        // Orders Table
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->constrained('users')->onDelete('cascade');
            $table->uuid('status_id')->constrained('order_statuses');
            $table->timestamps();
        });

        // Order Contents Table
        Schema::create('order_contents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('order_id')->constrained('orders')->onDelete('cascade');
            $table->uuid('dish_id')->constrained('dishes')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });

        // Ratings Table
        Schema::create('ratings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->constrained('users')->onDelete('cascade');
            $table->uuid('restaurant_id')->nullable()->constrained('restaurants')->onDelete('cascade');
            $table->uuid('dish_id')->nullable()->constrained('dishes')->onDelete('cascade');
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->timestamps();
        });

        // Favorites Table
        Schema::create('favorites', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->constrained('users')->onDelete('cascade');
            $table->uuid('dish_id')->constrained('dishes')->onDelete('cascade');
            $table->timestamps();
        });

        // Order Statuses Table
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->timestamps();
        });

        // Transaction Types Table
        Schema::create('transaction_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->timestamps();
        });

        // Days Table
        Schema::create('days', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->timestamps();
        });

         // Roles Table
         Schema::create('roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->timestamps();
        });
        
    }
    
        public function down() {
        Schema::dropIfExists('organizations');
        Schema::dropIfExists('users');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('restaurants');
        Schema::dropIfExists('dishes'); 
        Schema::dropIfExists('dish_options');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_contents');
        Schema::dropIfExists('ratings');
        Schema::dropIfExists('favorites');
        Schema::dropIfExists('order_statuses');
        Schema::dropIfExists('transaction_types');
        Schema::dropIfExists('days');
        Schema::dropIfExists('roles');
        
        }
};
